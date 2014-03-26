<?
/*
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************
*/

	// global
	$baseurl = "http://www.MatthewZipkin.com/funzap/";

	// redirect back to login page if post is empty
	if (empty($_POST))
	{
		exit();
	}

	// cookies and database
	require_once('safe/common.php');
	require_once('safe/sqlcommon.php');

	// user id
	$owner = $_SESSION["id"];
	$ownername = $_SESSION["fname"] . " " . $_SESSION["lname"];
	$owneremail = $_SESSION["email"];	

	// get details from variable sent by browser
	$zapid = mysql_real_escape_string($_POST["zapid"]);
	
	// get zap info from DB
	$q = "SELECT * FROM jigsaw WHERE name = '$zapid'";
	$row = mysql_fetch_array(mysql_query($q));
	$zapowner = $row["owner"];
	$friendname = $row["friendname"];
	$friendemail = $row["friendemail"];

	// check permission
	if ($owner != $zapowner) 
	{	
		// zap was not made by this logged in user
		$msg = "Permission to send this zap denied";
		respond($msg, 0);
		exit();
	}
	
	// SEND THE FRIEND AN EMAIL WITH THE ZAP LINK!
	$link = $baseurl . "zap.php?zap=" . $zapid;

	// prepare confirmation HTML email http://css-tricks.com/sending-nice-html-email-with-php/
	$to = $friendemail;
	$subject = $ownername . " sent you a FunZap! playable photo message";
	$message = "<html><body style='background-color: #ffcb73'><center><h1>Hello, ";
	$message .= $friendname . "!";
	$message .= "<h1>Welcome to FunZap!</h1><br><h2>It's the fun way to make and share your own personalized games with your friends.</h2><br>";
	$message .= "<h4>Your friend " . $ownername . " sent you a FunZap! It's a photo message that's a PLAYABLE GAME made by your friend, just for you!<br><br>";
	$message .= "Click the link below to receive your FunZap! message...</h4><br><br>";
	$message .= "<table style='border: 1px solid black; background-color:#FFA100;'><tr><td><h3><a href='" . $link ."'>" . $link . "</a></td></tr>";
	$message .= "</td></tr></table></center></body></html>";
	$headers = 'From: '. $owneremail . "\r\n" . 'Reply-To: ' . $owneremail . "\r\n" . 'X-Mailer: PHP/' . phpversion();
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$extraheaders = "-f" . $owneremail;

	// attempt to send email and catch return value
	$em = @mail($to,$subject,$message,$headers,$extraheaders);
	
	// if its all good, unpdate DB and send browser confirmation message
	if (!$em)
	{
		$msg = "Failed to send email";
		respond($msg, 0);
		exit();
	}
	else
	{
		// update DB
		$q = "UPDATE jigsaw SET sent = 1 WHERE name = '$zapid'";
		$result = mysql_query($q);
		
		// send browser msg
		if (!$result)
		{
			$msg = "Email send OK but failed to update databse";
			respond($msg, 0);
			exit();
		}
		else
		{
			$msg = "Your <h2>FunZap!</h2> has been sent to your friend!";
			respond($msg, 1);
			exit();
		}
		
	}


// ***** send response back to webpage
function respond($msg = "", $do = 0)
{
	// send message and commands back to browser
	$response["message"] = $msg;
	$response["do"] = $do;
	echo(json_encode($response));
}
?>