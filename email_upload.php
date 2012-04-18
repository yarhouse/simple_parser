<?php

include_once 'functions.inc.php';

	// Checks if the form was submitted
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
		// Checks if the file was uploaded without any errors
		if(isset($_FILES['file'])
		&& is_uploaded_file($_FILES['file']['tmp_name'])
		&& $_FILES['file']['error']==UPLOAD_ERR_OK){
		
			// Checks if the file is a text file
			if($_FILES['file']['type']=='text/plain'){
			
				// Give you some info about your email		
				echo "Upload: " . $_FILES["file"]["name"] . "<br />";
  			echo "Type: " . $_FILES["file"]["type"] . "<br />";
 				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
  			echo "Stored in: " . $_FILES["file"]["tmp_name"];
  			echo "<hr />";
  			
				// Opening the file into one single string
				$content = file_get_contents($_FILES['file']['tmp_name']);

				// Seperating the email into 2 workable sections - header and content
				$halves = explode('boundary=', $content, 2);

				// Processing functions
				$headers = parse_headers($halves[0]);
				$content = parse_body($halves[1]);

				echo $headers;
				echo "<hr />";
				echo $content;
			}
			else{
				echo "Uploaded file was not a text file."	;
			}
		}
		else{
			echo "No file uploaded!";
		}
	}
	else{
?>
<img src="parser.gif" /><br /><br />
<form action="email_upload.php" method="post"
		enctype="multipart/form-data">
	<label for="file">Filename:</label>
	<input type="file" name="file" id="file" /><br /><br />
	<input type="submit" name="submit" value="Submit" />
</form>

<?php } ?>

			

