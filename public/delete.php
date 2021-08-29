<?php
require "user-check.php";
require "../config.php";
require "common.php";

// This code will only run if the delete button is clicked
if (isset($_GET["id"])) {
    // this is called a try/catch statement 
    try {
        // define database connection
        $connection = new PDO($dsn, $username, $password, $options);
        
        // set id variable
        $id = $_GET["id"];
        
        // Create the SQL 
        $sql = "DELETE FROM works WHERE id = :id";

        // Prepare the SQL
        $statement = $connection->prepare($sql);
        
        // bind the id to the PDO
        $statement->bindValue(':id', $id);
        
        // execute the statement
        $statement->execute();

        // Success message
        $success = "Work successfully deleted";

    } catch(PDOException $error) {
        // if there is an error, tell us what it is
        echo $sql . "<br>" . $error->getMessage();
    }
}
else
{
    try {
        // FIRST: Connect to the database
        $connection = new PDO($dsn, $username, $password, $options);
        // SECOND: Create the SQL
        $sql = "SELECT * FROM works";
        // THIRD: Prepare the SQL
        $statement = $connection->prepare($sql);
        $statement->execute();
        // FOURTH: Put it into a $result object that we can access in the page
        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        // if there is an error, tell us what it is
        echo $sql . "<br>" . $error->getMessage();
    }
}

?>
<?php include "templates/header.php"; ?>

<h2>Delete (this is permanent!)</h2>

<?php
// if (isset($_POST['submit'])) {
//if there are some results
// if ($result && $statement->rowCount() > 0) { ?>
<!-- <h2>Results</h2> -->

<div class="results">
<?php
if(isset($result)){
    foreach($result as $row) {
    ?>
        <div class="item">
            <?php
            if( $row["imagelocation"] !== NULL && $row["imagelocation"] !== "" ){
                echo "<img src='uploads/" . $row["imagelocation"] . "' alt='" . $row['worktitle'] ." by " . $row['artistname'] . "'>";
            }
            else
            {
                echo "<p class='small'>No image available.</p>";
            }
            ?>
            <p>Artist:<?php echo $row['artistname']; ?></p>
            <p>Work Title:<?php echo $row['worktitle']; ?></p>
            <p>Work Date:<?php echo $row['workdate']; ?></p>
            <p>Work type:<?php echo $row['worktype']; ?></p>
        
            <p>
                <a onClick="return confirm('Do you really want to delete this item?');" href='delete.php?id=<?php echo $row['id']; ?>'>Delete</a>
            </p>
        </div>
        
        <?php //}; //close the foreach
        //};
    };
}
?>
</div>
<!-- <form method="post">
    <input type="submit" name="submit" value="View all">
</form> -->
<?php include "templates/footer.php"; ?>