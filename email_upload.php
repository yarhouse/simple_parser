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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Parser</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<div>
		<form action="email_upload.php" method="post" enctype="multipart/form-data">
			<div><label for="file">Filename:</label></div>
			<div><input type="file" name="file" id="file" /></div>
			<div><input type="submit" name="submit" value="Submit" /></div>
		</form>
	</div>
	<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
      src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
  </p>
</body>
</html>

<?php } ?>

			

