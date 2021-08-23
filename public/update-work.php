<?php 

    require "user-check.php";

    //simple if/else statement to check if the id is available
    if (isset($_GET['id'])) {
        //yes the id exists 

         // include the config file that we created last week
        require "../config.php";
        require "common.php";

         // run when submit button is clicked
        if (isset($_POST['submit'])) {
            try {
                $connection = new PDO($dsn, $username, $password, $options);  
                
                //grab elements from form and set as varaible
                $work =[
                    "id"         => $_POST['id'],
                    "artistname" => $_POST['artistname'],
                    "worktitle"  => $_POST['worktitle'],
                    "workdate"   => $_POST['workdate'],
                    "worktype"   => $_POST['worktype'],
                    "date"   => $_POST['date'],
                ];
            
                // create SQL statement
                $sql = "UPDATE `works` 
                        SET id = :id, 
                            artistname = :artistname, 
                            worktitle = :worktitle, 
                            workdate = :workdate, 
                            worktype = :worktype, 
                            date = :date 
                        WHERE id = :id";
            
                //prepare sql statement
                $statement = $connection->prepare($sql);
            
                //execute sql statement
                $statement->execute($work);

            } catch(PDOException $error) {
                echo $sql . "<br>" . $error->getMessage();
            }
        }

        try {
            // standard db connection
            $connection = new PDO($dsn, $username, $password, $options);
            
            // set if as variable
            $id = $_GET['id'];
            
            //select statement to get the right data
            $sql = "SELECT * FROM works WHERE id = :id";
            
            // prepare the connection
            $statement = $connection->prepare($sql);
            
            //bind the id to the PDO id
            $statement->bindValue(':id', $id);
            
            // now execute the statement
            $statement->execute();
            
            // attach the sql statement to the new work variable so we can access it in the form
            $work = $statement->fetch(PDO::FETCH_ASSOC);
            
        } catch(PDOExcpetion $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
        
        // quickly show the id on the page
        //echo $_GET['id'];
        
        include "templates/header.php"; 
        ?>

        <form method="post">
        
        <label for="id">ID</label>
        <input type="text" name="id" id="id" value="<?php echo escape($work['id']); ?>" >
        
        <label for="artistname">Artist Name</label>
        <input type="text" name="artistname" id="artistname" value="<?php echo escape($work['artistname']); ?>">

        <label for="worktitle">Work Title</label>
        <input type="text" name="worktitle" id="worktitle" value="<?php echo escape($work['worktitle']); ?>">

        <label for="workdate">Work Date</label>
        <input type="text" name="workdate" id="workdate" value="<?php echo escape($work['workdate']); ?>">

        <label for="worktype">Work Type</label>
        <input type="text" name="worktype" id="worktype" value="<?php echo escape($work['worktype']); ?>">
        
        <label for="date">DB Date</label>
        <input type="text" name="date" id="date" value="<?php echo escape($work['date']); ?>">

        <input type="submit" name="submit" value="Save">

        </form>

<?php

    } else {
        // no id, show error
        echo "No id - something went wrong";
        //exit;
    }
    
    include "templates/footer.php";
?>