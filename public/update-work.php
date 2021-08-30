<?php 

    require "user-check.php";

    //simple if/else statement to check if the id is available
    if (isset($_GET['id'])) {
        //yes the id exists 

         // include the config file that we created
        require "../config.php";
        require "common.php";

         // run when submit button is clicked
        if (isset($_POST['submit'])) {

            if( !empty($_FILES["imagelocation"]["name"]) ){
                include "img-upload.php";
            }else {
                $imgid = $_POST["imagelocation"];
            }

            try {
                $connection = new PDO($dsn, $username, $password, $options);  
                
                // todo: replace form date with timestamp, format appropriately and consistently
                $work =[
                    "id"         => $_POST['id'],
                    "artistname" => $_POST['artistname'],
                    "worktitle"  => $_POST['worktitle'],
                    "workdate"   => $_POST['workdate'],
                    "worktype"   => $_POST['worktype'],
                    "date"	   => $_POST['date'],
                    "imagelocation" => $imgid
                  ];
            
                // old sql, has id set, no img upload
                // $sql = "UPDATE `works` 
                //         SET id = :id, 
                //             artistname = :artistname, 
                //             worktitle = :worktitle, 
                //             workdate = :workdate, 
                //             worktype = :worktype, 
                //             date = :date 
                //         WHERE id = :id";
                
                $sql = "UPDATE `works` 
                        SET artistname = :artistname, 
                            worktitle = :worktitle, 
                            workdate = :workdate, 
                            worktype = :worktype, 
                            date = :date,
                            imagelocation = :imagelocation
                        WHERE id = :id";
            
                //prepare sql statement
                $statement = $connection->prepare($sql);
            
                //execute sql statement
                $statement->execute($work);

            } catch(PDOException $error) {
                echo $sql . "<br>" . $error->getMessage();
            }
        }
        // if submit hasn't been clicked yet
        else
        {        
            try {
                $connection = new PDO($dsn, $username, $password, $options);
                
                $id = $_GET['id'];
                // uid restricts access to items
                $uid = $_SESSION['id'];
                
                //select statement to get the right data
                $sql = "SELECT * FROM works WHERE id = :id AND userid = :uid";
                
                // prepare the connection
                $statement = $connection->prepare($sql);
                
                //bind the id to the PDO id
                $statement->bindValue(':id', $id);
                $statement->bindValue(':uid', $uid);
                
                // now execute the statement
                $statement->execute();
                
                // attach the sql statement to the new work variable so we can access it in the form
                $work = $statement->fetch(PDO::FETCH_ASSOC);
                
            } catch(PDOException $error) {
                echo $sql . "<br>" . $error->getMessage();
            }
        }

        // quickly show the id on the page
        //echo $_GET['id'];
        
        include "templates/header.php"; 
        ?>

        <?php if (isset($_POST['submit']) && $statement) : ?>
            <p>Work successfully updated.</p>
        <?php endif; ?>

        <h2>Edit: <?php echo escape($work['worktitle']); ?></h2>
        <h3>Work ID: <?php echo escape($work['id']); ?></h3>

        <form class="add" method="post" enctype="multipart/form-data">
            <input readonly type="hidden" name="imagelocation" id="imagelocation" value="<?php echo escape($work['imagelocation']); ?>" >
        <?php
            if( $work["imagelocation"] !== NULL && $work["imagelocation"] !== "" ){
                echo "<img src='uploads/" . $work["imagelocation"] . "' alt='" . $work['worktitle'] ." by " . $work['artistname'] . "'>";
            }
            else
            {
                echo "<p class='small'>No image available.</p>";
            }
        ?>
            <div class="form-group">
        <!--    	<label for="id">ID</label>-->
        <!-- Make the ID hidden and readonly so the user doesn't edit the wrong item in the DB -->
                <input readonly type="hidden" name="id" id="id" value="<?php echo escape($work['id']); ?>" >
            </div>
            <div class="form-group">
                <label for="artistname">Artist Name</label>
                <input type="text" class="form-input" name="artistname" id="artistname" value="<?php echo escape($work['artistname']); ?>">
            </div>
            <div class="form-group">
                <label for="worktitle">Work Title</label>
                <input type="text" class="form-input" name="worktitle" id="worktitle" value="<?php echo escape($work['worktitle']); ?>">
            </div>
            <div class="form-group">
                <label for="workdate">Work Date</label>
                <input type="text" class="form-input" name="workdate" id="workdate" value="<?php echo escape($work['workdate']); ?>">
            </div>
            <div class="form-group">
                <label for="worktype">Work Type</label>
                <input type="text" class="form-input" name="worktype" id="worktype" value="<?php echo escape($work['worktype']); ?>">
            </div>
            <div class="form-group">
                <label for="date">Date Modified</label>
                <input type="text" class="form-input" name="date" id="date" value="<?php echo escape($work['date']); ?>">
            </div>
            <div class="form-group">
                <label for="worktype">Upload/update Work Image</label>
                <input type="file" class="form-input" name="imagelocation" id="imagelocation">
            </div>
            <input type="submit" class="btn" name="submit" value="Save">

        </form>

<?php

    } else {
        // no id, show error
        echo "No id - something went wrong";
        //exit;
    }
    
    include "templates/footer.php";
?>