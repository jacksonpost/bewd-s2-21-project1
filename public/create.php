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
    // SECOND: Get the contents of the form and store it in an array
    $new_work = array(
        "artistname" => $_POST['artistname'],
        "worktitle" => $_POST['worktitle'],
        "workdate" => $_POST['workdate'],
        "worktype" => $_POST['worktype'],
    );
    // THIRD: Turn the array into a SQL statement
    $sql = "INSERT INTO works (artistname, worktitle, workdate, worktype) 
            VALUES (:artistname, :worktitle, :workdate, :worktype)";
    // FOURTH: Now write the SQL to the database
    $statement = $connection->prepare($sql);
    $statement->execute($new_work);
    
    } catch(PDOException $error) {
        // if there is an error, tell us what it is
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>
<?php include "templates/header.php"; ?>

<h2>Add a work</h2>

<?php if (isset($_POST['submit']) && $statement) {
    echo "<p class='text-success'>Work successfully added.</p>"; 
} ?>

<!--form to collect data for each artwork
    To do:
    - prevent empty inputs
    - restrict data types
    - what if unknown? date?
-->
<form class="add" method="post">
    <div class="form-group">
        <label for="artistname">Artist Name</label>
        <input class="form-input" required type="text" name="artistname" id="artistname">
    </div>
    <div class="form-group">
        <label for="worktitle">Work Title</label>
        <input class="form-input" required type="text" name="worktitle" id="worktitle">
    </div>
    <div class="form-group">
        <label for="workdate">Work Date</label>
        <input class="form-input" required type="text" name="workdate" id="workdate">
    </div>
    <div class="form-group">
        <label for="worktype">Work Type</label>
        <input class="form-input" required type="text" name="worktype" id="worktype">
    </div>
    <input class="btn" type="submit" name="submit" value="Add to collection">
</form>
<?php include "templates/footer.php"; ?>