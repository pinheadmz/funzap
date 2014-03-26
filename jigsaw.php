<?
	// global
	$baseurl = "http://www.MatthewZipkin.com/funzap/";

	// enable shit
	require_once("safe/common.php");
	require_once("safe/sqlcommon.php");

	// script must be called with puzzle details in POST
	if (empty($_POST))
		exit();

	// user id
	$owner = $_SESSION["id"];
	$ownername = $_SESSION["fname"] . " " . $_SESSION["lname"];
	$owneremail = $_SESSION["email"];
	
	// get details from variable sent by browser
	$pic = mysql_real_escape_string($_POST["pic"]);
	$piecenumber = mysql_real_escape_string($_POST["piecenumber"]);
	$prize = mysql_real_escape_string($_POST["prize"]);
	$prizemsg = mysql_real_escape_string($_POST["prizemsg"]);
	$friendname = mysql_real_escape_string($_POST["friendname"]);
	$friendemail = mysql_real_escape_string($_POST["friendemail"]);

	// get filenames for main pic from database
	$q = "SELECT * FROM pics WHERE name = '$pic'";
	$row = mysql_fetch_array(mysql_query($q));
	$orign = $row["file"];
	$thumbn = $row["thumb"];
	$picowner = $row["owner"];
	$side = $row["side"];
	$picname = $row["name"];
	
	// get info for prize pic if applicable
	if ($prize == "pzpic")
	{
		$q = "SELECT * FROM pics WHERE name = '$prizemsg'";
		$row = mysql_fetch_array(mysql_query($q));
		$pzorign = $row["file"];
		$pzthumbn = $row["thumb"];
		$pzpicowner = $row["owner"];
		$pzside = $row["side"];
		$pzpicname = $row["name"];

		// check permission
		if ($owner != $pzpicowner)
		{	
			// at least one photo to use is not owned by current logged in user
			$msg = "Permission to use this photo denied";
			respond($msg, 0, "/", "");
			exit();
		}
		
		// put actual photo URL into prize variable
		$prizemsg = $pzorign;
		
	}


	// check permission - main photo for puzzle
	if ($owner != $picowner) 
	{	
		// at least one photo to use is not owned by current logged in user
		$msg = "Permission to use this photo denied";
		respond($msg, 0, "/", "");
		exit();
	}
	
	// create 800 pixel max version of image file for this app
	// load pic and its details
	include("safe/SimpleImage.php");
	$jigsawpic = new SimpleImage();
	$jigsawpic->load($orign);
	$w = $jigsawpic->getWidth();
	$h = $jigsawpic->getHeight();
	//resize
	if ($w > $h)
		$jigsawpic->resizeToWidth(800);
	elseif ($h >= $w)
		$jigsawpic->resizeToHeight(800);
	// create new filename from old filename, especially its filetype extension
	$orign = explode(".", $orign);
	$jigsawpicfile = "picuploads/jigsaw/" . $picname . "_jigsaw." . $orign[1];
	$jigsawpic->save($jigsawpicfile);

	// get revised dimensions from resized photo
	$photox = $jigsawpic->getWidth();
	$photoy = $jigsawpic->getHeight();
	
	// enter all this shit into db
	// sexy new name, user id plus 12 digit random number, must be unique for database
	do {
	$name = $_SESSION['id'] + 100000000000;
	$name .= "_";
	$name .= rand(100000, 999999);
	$name .= rand(100000, 999999);
	} while (mysql_num_rows(mysql_query("SELECT * FROM jigsaw WHERE name = '$name';")) != 0);
	
	// finish photo url
	$jigsawpicfile = $baseurl . $jigsawpicfile;
	
	// prepare query
	$q = "INSERT INTO jigsaw (name, owner, pic, photox, photoy, piecenumber, prize, prizemsg, friendname, friendemail)
		VALUES ('$name', '$owner', '$jigsawpicfile', '$photox', '$photoy', '$piecenumber', '$prize', '$prizemsg', '$friendname', '$friendemail');";
	$check = mysql_query($q);
	
	// report any DB errors
	if (!$check)
	{
		// FAILED UPDATE
		$msg = "Database update failed";
		respond($msg, 0, "/", "");
		exit();
	}
	else
	{
		// GOOD UPDATE
		$link = $baseurl . "zap.php?zap=" . $name;
		$do = 1;
		$msg = "New Zap complete!";
		respond($msg, 1, $link, $name);
		exit();
	}



// ***** send response back to webpage
function respond($msg = "", $do = 0, $link = "/", $name = "")
{
	// send message and commands back to browser
	$response["message"] = $msg;
	$response["do"] = $do;
	$response["link"] = $link;
	$response["name"] = $name;
	echo(json_encode($response));
}
?>
