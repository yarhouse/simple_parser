<?php

/* This is removing any spot where a new line goes into whitespace
 * so that any information that is a part of a key will be set into the value
 */
function remove_whitespace($string){
	return preg_replace('/\n\s+/', ' ', $string);		
}

function parse_headers($content){
	
	$content = remove_whitespace($content);
	
	/* Seperate lines now have all their trailing information
	 * connected to them, explode the string into arrays by each
	 * remaining new line.
	 */
	$lines = explode("\n", $content);
	
	// Loop the $lines into singular $line to work with
	foreach ($lines as $line){
	
		// Explode the lines in to 2 pieces at the first :
		$pieces = explode(":", $line, 2);
				
		/* This handles the issue with the purely blank lines in the header
		 * that don't have a : to explode (lol). This IF statement will skip those
		 * lines when putting the value of $pieces[0] into the $parsed_content keys
		 */
		if (count($pieces) > 1){
			/* This is putting the value of pieces0 as parsed_content.
			 * 'To' will be called, and the recipient will be output
			 */			
			$parsed_content[$pieces[0]] = trim($pieces[1]);
			
			/* I am super proud that I figured out this part. I kept running tests
			 * with different types of emails, and found that I didn't have
			 * a way to handle cc's. When I made a variable for it, non-cc emails
			 * threw an error. Took a bit more digging on the net and my php book,
			 * but I got a way to handle cc's and bcc's down for all emails.
			 * I kept the code to single lines for cleanliness.
			 */
			if (isset($parsed_content['Date'])==NULL){$parsed_content['Date'] = ' ';}
			if (isset($parsed_content['Subject'])==NULL){$parsed_content['Subject'] = ' ';}
			if (isset($parsed_content['From'])==NULL){$parsed_content['From'] = ' ';}
			if (isset($parsed_content['To'])==NULL){$parsed_content['To'] = ' ';}
			if (isset($parsed_content['Cc'])==NULL){$parsed_content['Cc'] = ' ';}
			if (isset($parsed_content['Bcc'])==NULL){$parsed_content['Bcc'] = ' ';}
		}	
	}
		
	// Set up the format to output the info, only using the variables passed.
	// This could also take more forms of data to pass if need be updated
	$date = "<b>Date: </b>".htmlentities($parsed_content['Date'])."<br />";
	$subject = "<b>Subject: </b>".htmlentities($parsed_content['Subject'])."<br />";
	$from = "<b>From: </b>".htmlentities($parsed_content['From'])."<br />";
	$to = "<b>To: </b>".htmlentities($parsed_content['To'])."<br />";
	$cc = "<b>Cc: </b>".htmlentities($parsed_content['Cc'])."<br />";
	$bcc = "<b>Bcc: </b>".htmlentities($parsed_content['Bcc'])."<br />";
	
	// Structure the order of output for the header, easy to change up.
	$header_output = $date.$from.$to.$cc.$bcc.$subject;
	
	// Get the final product out
	return $header_output;
}

function parse_body($data){
	
	// Getting the string completly seperated into arrays....
	$lines = explode("\n", $data);
	
	// ... to cherry pick the very first array line to use as the boundary
	$boundary = $lines[0];
	
	/* One test email gave a problem because the 'boundary=' code was wrapped in
	 * double-quotes, but wasn't in the body section.
	 * Decided that I would trim the double quotes from the boundary variable.
	 * Need to rewrite the script to break the header from the body
	 * at a different point, as the only thing lost from that test
	 * email was the date. The date was placed AFTER the initial boundary call.
	 */
	$standerdized_boundary = str_replace('"', '', $boundary);
	
	// Put everything back together
	$body = implode("\n", $lines);
	
	/* Re-explode on boundary obtained from the first explode.
	 * In the future, find a preg_xxxxx function that would just grab the boundary code.
	 * A regex to capture/find it could be /(?<=boundary=).+/, but impliment it.
	 */
	$sections = explode($standerdized_boundary, $body);
	
	// Switch out the newlines with breaks for format readablility
	$message = preg_replace("/\n/", '<br />', $sections[2]);
	
	// PUT IT BACK OUT THERE
	return ($message);
}	

?>

