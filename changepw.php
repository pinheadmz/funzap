<?
	// check for logged in and redirect
	require_once("safe/common.php");
	// access database
	require_once("safe/sqlcommon.php");

	// redirect back to login page if post is empty
	if (empty($_POST))
	{
		header( 'Location: http://www.MatthewZipkin.com/funzap' ) ;
		exit();
	}

	// clean up entry from webpage 
	$oldpw = mysql_real_escape_string($_POST["oldpw"]);
	$changepw1 = mysql_real_escape_string($_POST["changepw1"]);

	// initialize response variables
	$do = 0;
	$msg = "";
	$link = "/";
	
	// get user info from database to check current password
	// prepare SQL query
	$id = $_SESSION["id"];
	$q = "SELECT * FROM login WHERE id='$id'";
	
	// execute query
	$result = mysql_query($q);	
	
	// grab row
	$row = mysql_fetch_array($result);

	// compare hash of user's input against hash that's in database using database hash as SALT
	if (crypt($oldpw, $row["password"]) == $row["password"])
	{
		// if old pw is good, set new pw in database
		$hash = crypt($changepw1);
		// prepare sql
		$q = "UPDATE login SET password='";
		$q .= $hash;
		$q .= "' WHERE id='";
		$q .= $id;
		$q .= "'";
		// submit sql
		$check = mysql_query($q);
		
		// report outcome of databse update to browser
		if (!$check)
		{
			$msg = "Database update failed";
		}
		else
		{
			$msg = "Password change success";
			$do = 1;
		}
	}
	else
	{
		// message to browser with simple display message command
		$msg = "<span style='color:red'>Incorrect Password Entered</span>";
	}

	// send message and commands back to browser
	$response["message"] = $msg;
	$response["do"] = $do;
	$response["link"] = $link;
	echo(json_encode($response));
?>