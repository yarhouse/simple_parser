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
				$upload = "<b>Upload:</b> " . $_FILES["file"]["name"] . "<br />";
				$type = "<b>Type:</b> " . $_FILES["file"]["type"] . "<br />";
	 			$size = "<b>Size:</b> " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
 				$storage = "<b>Stored in:</b> " . $_FILES["file"]["tmp_name"];
  
  			// Structure the order of output for the info, also easy to change up.
				$info= $upload.$type.$size.$storage;

				// Opening the file into one single string
				$content = file_get_contents($_FILES['file']['tmp_name']);

				// Seperating the email into 2 workable sections - header and content
				$halves = explode('boundary=', $content, 2);

				// Processing functions
				$headers = parse_headers($halves[0]);
				$content = parse_body($halves[1]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link type="text/css" rel="stylesheet" media="screen" href="style.css" />
<title>Parser</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<div class="header"><p><?php echo $headers; ?></p></div>
	<div class="info"><p><?php echo $info; ?></p></div>
	<div class="content"><p><?php echo $content; ?></p></div>				
</body>
</html>

<?php
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
<link type="text/css" rel="stylesheet" media="screen" href="style.css" />
<title>Parser</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<div id="content">
		<p class="about">This is a simple email parser. Upload a text (.txt) file containing a raw email, or use one from my testing directory. <a href="./emails/" >Select an email to download!</a> Make sure to look at the raw file so you can see the difference of whats parsed.</p>
		<div class="form">
			<form action="index.php" method="post" enctype="multipart/form-data">
				<div class="file_input"><input type="file" name="file" id="file" /></div>
				<div class="submit_button"><input type="submit" name="submit" value="Let's get to parsing!" /></div>
			</form>
		</div>
	</div>
  <p>
		<a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px"
        src="http://jigsaw.w3.org/css-validator/images/vcss-blue" class="validator" alt="Valid CSS!" /></a>
	</p>
	<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
      src="http://www.w3.org/Icons/valid-xhtml10" class="validator" alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
  </p>
</body>
</html>

<?php } ?>

			

