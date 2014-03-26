<?
/*
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************
*/

	// redirect back to login page if you are not sending me signup info
	if (empty($_POST))
	{
		header( 'Location: http://www.MatthewZipkin.com/funzap' ) ;
		exit();
	}

	// open sql connection
	require_once("safe/sqlcommon.php");

	// clean up entry from login page 
	$email = mysql_real_escape_string($_POST["email"]);
	$fname = mysql_real_escape_string($_POST["fname"]);
	$lname = mysql_real_escape_string($_POST["lname"]);

	// validate email address again with PHP
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		$msg = "<span style='color:red;'>Invalid email address</span>";
	else
	{
		// check if user exists already
		// prepare sql
		$q = "SELECT * FROM login WHERE email='$email'";
		
		// execute query
		$result = mysql_query($q);

		// check number of entries with that user email
		if (mysql_num_rows($result) >= 1)
			$msg = "<span style='color:red;'>This email is already signed up!</span>";
		else
		{
				
			// generate temporary 6-digit number password
			$password = rand(100000,999999);
			
			// encrypt password for secure databse with SQL escape for later user login
			$hash = crypt(mysql_real_escape_string($password));
			
			// prepare SQL query to insert new account name
			$q = "INSERT INTO login (email, password, fname, lname) VALUES ('";
			$q .= $email;
			$q .= "', '";
			$q .= $hash;
			$q .= "', '";
			$q .= $fname;
			$q .= "', '";
			$q .= $lname;
			$q .= "');";
	
			// submit query
			$check = mysql_query($q);
					
			// report any SQL error or continue
			if (!$check)
			{
				// FAILED UPDATE
				$msg = "Database update failed";
			}
			else
			{
				// prepare confirmation HTML email http://css-tricks.com/sending-nice-html-email-with-php/
				$to = $email;
				$subject = "Welcome to FunZap - Login Information";
				$message = "<html><body style='background-color: #ffcb73'><center><h1>Hello, ";
				$message .= $fname . " " . $lname . " and...";
				$message .= "<h1>Welcome to FunZap!</h1><br><h2>It's the fun way to make and share your own personalized games with your friends.</h2><br>";
				$message .= "<h4>Log in with the <b>temporary</b> password below and start sending your friends the coolest messages around.<br><br>";
				$message .= "<i>Be sure to change your password after you log in by clicking the <b>Account</b> tab</i></h4><br><br>";
				$message .= "<table style='border: 1px solid black; background-color:#FFA100;'><tr><td><h3>http://www.MatthewZipkin.com/funzap</td></tr>";
				$message .= "<tr><td><h3>Email: ";
				$message .= $email;
				$message .= "</td></tr><tr><td><h3>Temporary Password: ";
				$message .= $password;
				$message .= "</td></tr></table></center></body></html>";
				$headers = 'From: Matthew.Zipkin@gmail.com' . "\r\n" . 'Reply-To: Matthew.Zipkin@gmail.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$extraheaders = "-fMatthew.Zipkin@gmail.com";
			
				// attempt to send email and catch return value
				$em = @mail($to,$subject,$message,$headers,$extraheaders);
				// translate return value into user-friendly string
				$msg = ($em ? "Confirmation email has been sent" : "<span style='color:red;'>Unable to send email</span>");
			}		
		}
			
	}

	// send message back to browser
	$response["message"] = $msg;
	echo(json_encode($response));
?>