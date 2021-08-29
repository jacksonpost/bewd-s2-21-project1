<?php

require "user-check.php";

// this code will only execute after the submit button is clicked
if (isset($_POST['submit'])) {
    // include the config file that we created before
    require "../config.php";
    // this is called a try/catch statement
    try {
    // FIRST: Connect to the database
    $connection = new PDO($dsn, $username, $password, $options);

    // make sure users only see their own collection items
    $sql = "SELECT * FROM works WHERE userid =" . $_SESSION['id'];
    //$sql = "SELECT * FROM works";

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
<?php
if (isset($_POST['submit'])) {
    //if there are some results
    if ($result && $statement->rowCount() > 0) { ?>
    <h2>Results</h2>
    <?php
    // This is a loop, which will loop through each result in the array
        foreach($result as $row) {
            ?>
            <div class="result">
                <?php
                if( $row["imagelocation"] !== NULL && $row["imagelocation"] !== "" ){
                    echo "<img src='uploads/" . $row["imagelocation"] . "' alt='" . $row['worktitle'] ." by " . $row['artistname'] . "'>";
                }
                else
                {
                    echo "<p class='small'>No image available.</p>";
                }
                ?>
                <p>Artist Name: <?php echo $row['artistname']; ?></p>
                <p>Work Title: <?php echo $row['worktitle']; ?></p>
                <p>Work Date: <?php echo $row['workdate']; ?></p>
                <p>Work type: <?php echo $row['worktype']; ?></p>
            </div>
                <?php
                // this willoutput all the data from the array
                //echo '<pre>'; var_dump($row);
                ?>
            <?php 
        } //close the foreach
    }
    else
    {
        echo "<p>No items in collection</p>";
    }
};
?>
<form method="post">
    <input type="submit" name="submit" value="View all">
</form>
<?php include "templates/footer.php"; ?>