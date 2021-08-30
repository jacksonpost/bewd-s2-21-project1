<?php 
require "user-check.php";
require "../config.php";

include "templates/header.php";
?>

<?php

try {
    $connection = new PDO($dsn, $username, $password, $options);

    $uid = $_SESSION['id'];
    $sql = "SELECT * FROM works WHERE userid = :uid";

    $statement = $connection->prepare($sql);
    $statement->bindValue(':uid', $uid);
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
            <p><a href='update-work.php?id=<?php echo $row['id']; ?>'>Edit</a></p>
        </div>
    
    <?php }; //close the foreach
};

?>

<?php
include "templates/footer.php"; 
?>
