<?php
// check whether user is logged in, redirect to login if not.
require "user-check.php";

// this code will only execute after the submit button is clicked
if (isset($_POST['submit'])) {
	
	// include the config file that we created before
	require "../config.php";
	
	if( empty(trim($_POST['artistname'])) or empty(trim($_POST['worktitle'])) ){
        $input_err = "Please enter artist's name and work title.";
    }
	
	if( !empty($_FILES["imagelocation"]["name"]) ){
		
		include "img-upload.php";

	}
	////
	
	if( empty($input_err) and empty($upload_err) ){
		try {

			// FIRST: Connect to the database
			$connection = new PDO($dsn, $username, $password, $options);
			
			$userid = $_SESSION['id'];
			$artistname = $_POST['artistname'];
			$worktitle = $_POST['worktitle'];
			$workdate = $_POST['workdate'];
			$worktype = $_POST['worktype'];
			
			// SECOND: Get the contents of the form and store it in an array
			$new_work = array(
				"userid" => $userid,
				"artistname" => $artistname,
				"worktitle" => $worktitle,
				"workdate" => $workdate,
				"worktype" => $worktype,
				"imagelocation" => $imgid
				//"imagelocation" => basename( $_FILES["imagelocation"]["name"])
			);

			// THIRD: Turn the array into a SQL statement
			$sql = "
					INSERT 
					INTO works (userid, artistname, worktitle, workdate, worktype, imagelocation) 
					VALUES (:userid, :artistname, :worktitle, :workdate, :worktype, :imagelocation)
			";

			// FOURTH: Now write the SQL to the database
			$statement = $connection->prepare($sql);
			$statement->execute($new_work);

		} catch(PDOException $error) {
			// if there is an error, tell us what it is
			echo $sql . "<br>" . $error->getMessage();
		}
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
<!--form to collect data for each artwork-->
<form class="add" method="post" enctype="multipart/form-data">
	<?php
	// conditional: if there is an error add the message.
	echo (!empty($input_err)) ? '<p class="error">' . $input_err . '</p>' : ''; 
	echo (!empty($upload_err)) ? '<p class="error">' . $upload_err . '</p>' : ''; 
	?>
	<div class="form-group">
		<label for="artistname">Artist Name</label>
		<input type="text" class="form-input" name="artistname" id="artistname" value="<?php echo $artistname; ?>">
	</div>
	<div class="form-group">
		<label for="worktitle">Work Title</label>
		<input type="text" class="form-input" name="worktitle" id="worktitle" value="<?php echo $worktitle; ?>">
	</div>
	<div class="form-group">
		<label for="workdate">Work Date</label>
		<input type="text" class="form-input" name="workdate" id="workdate" value="<?php echo $workdate; ?>">
	</div>
	<div class="form-group">
		<label for="worktype">Work Type</label>
		<input type="text" class="form-input" name="worktype" id="worktype" value="<?php echo $worktype; ?>">
	</div>
	<!-- <div class="form-group">
		<label for="checktest">Checktest</label>
		<input type="checkbox" name="checktest" value="checktest">
	INSERT INTO works(checktest) VALUES :checktest	
	</div> -->
	<div class="form-group">
		<label for="worktype">Work Image</label>
		<input type="file" class="form-input" name="imagelocation" id="imagelocation">
	</div>
	<input type="submit" class="btn" name="submit" value="Add to collection">
</form>

<?php include "templates/footer.php"; ?>