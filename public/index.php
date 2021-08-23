<?php 
require "user-check.php";

include "templates/header.php";

// show some collection...

// include the config file that we created before
require "../config.php";

?>

<?php

try {
    $connection = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT * FROM works";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

if ($result && $statement->rowCount() > 0) { ?>
    <h2 class="text-large">My Collection</h2>
    <div class="results">
    <?php
    // This is a loop, which will loop through each result in the array
    foreach($result as $row) {
    ?>
        <div class="item">
            <p>Artist:<?php echo $row['artistname']; ?></p>
            <p>Work Title:<?php echo $row['worktitle']; ?></p>
            <p>Work Date:<?php echo $row['workdate']; ?></p>
            <p>Work type:<?php echo $row['worktype']; ?></p>
        </div>
    
    <?php }; //close the foreach
};

?>

<?php
include "templates/footer.php"; 
?>
