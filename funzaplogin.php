<?
/*
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************
*/

	// redirect back to login page if post is empty
	if (empty($_POST))
	{
		header( 'Location: http://www.MatthewZipkin.com/funzap' ) ;
		exit();
	}

	// open sql connection
	require_once("safe/sqlcommon.php");	

	// clean up entry from login page 
	$email = mysql_real_escape_string($_POST["email"]);
	$password = mysql_real_escape_string($_POST["password"]);
	
	// prepare SQL query 
	$q = "SELECT * FROM login WHERE email='$email'";
	
	// execute query
	$result = mysql_query($q);
	
	// initialize response variables
	$do = 0;
	$msg = "";
	$link = "/";

	// if we found user, check password
	if (mysql_num_rows($result) == 1)
	{	
		// grab row
		$row = mysql_fetch_array($result);

		// compare hash of user's input against hash that's in database using database hash as SALT
		if (crypt($_POST["password"], $row["password"]) == $row["password"])
		{
			// enable cookies for all subdomains
			session_start();
			
			// remember that user's now logged in by caching user's info in SESSION variable
			$_SESSION["id"] = $row["id"];
			$_SESSION["email"] = $row["email"];
			$_SESSION["fname"] = $row["fname"];
			$_SESSION["lname"] = $row["lname"];
			
			// send message to browser with load homeapge command and homepage content from file
			$msg = "<img src='i/loading.gif'>  Logging In...  <img src='i/loading.gif'>";
			$do = 1;
			$link = "homepage.php";
		}
		else
		{
			// message to browser with simple display message command
			$msg = "<span style='color:red'>Incorrect Password Entered</span>";
		}
	}
	else
	{
		// message to browser with simple display message command
		$msg = "<span style='color:red'>Unknown email:</span> Sign up now!";
	}

	// send message and commands back to browser
	$response["message"] = $msg;
	$response["do"] = $do;
	$response["link"] = $link;
	echo(json_encode($response));
?>