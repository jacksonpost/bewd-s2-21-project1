<!DOCTYPE html>
<html>
<head></head>
<body>

<?php

if ( isset($_GET['q']) ) {

	require "../config.php";
	require "common.php";

	try {
		$connection = new PDO($dsn, $username, $password, $options);
		$query = escape($_GET['q']);

        $sql = "SELECT DISTINCT * FROM works WHERE 
        (artistname LIKE '%" . $query . "%'
            OR 
        worktitle LIKE '%" . $query . "%'
            OR
        workdate LIKE '%" . $query . "%'
            OR
        worktype LIKE '%" . $query . "%')";
		
        $statement = $connection->prepare($sql);
		$statement->execute();

		// Put it into a $result object that we can access in the page
		$result = $statement->fetchAll();

	} catch(PDOException $error) {
		echo $sql . "<br>" . $error->getMessage();
	}

    // If there are results, show enough of the item data to allow the user to decide to click/not
    if ($result && $statement->rowCount() > 0) {
        echo "<h2>" . $query . "</h2>";
        echo "<p>results: " . $statement->rowCount() . "</p>";
        foreach($result as $row) {
            echo "<div><a href='record.php?identifier=" . $row['artistname'] . "'>" . $row['worktitle'] . "</a></div>";
        }
    }else{
        echo "<p>Sorry, no results for: <b>" . $query . "</b></p>";
    }
};
?>

</body>
</html>