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

	// open sql connection and get user cookie info
	require_once("safe/common.php");
	require_once("safe/sqlcommon.php");	

	// clean up entry from page 
	$name = mysql_real_escape_string($_POST["name"]);
	$function = mysql_real_escape_string($_POST["func"]);

	// call appropriate function
	switch ($function){
	case "del":
		delete($name);
		break;
	case "rot":
		rotate($name);
		break;
	}

// ***** delete pic file and its thumb file
function delete($name)
{
	// get filenames for this pic from database
	$q = "SELECT * FROM pics WHERE name = '$name'";
	$row = mysql_fetch_array(mysql_query($q));
	$orign = $row["file"];
	$thumbn = $row["thumb"];
	$owner = $row["owner"];
		
	// check permission
	if ($_SESSION["id"] != $owner)
	{	
		// photo to delete is not owned by current logged in user
		$msg = "Permission to delete was denied";
		respond($msg, 0, "/");
		exit();
	}
	
	// delete files from disk and return results
	$o = @unlink($orign);
	$t = @unlink($thumbn);
	
	// delete record from databse if both files are indeed gone
	if ($o && $t)
	{
		// FILE DELETE SUCCESS UPDATES DB
		$q = "DELETE FROM pics WHERE name = '$name'";
		$result = mysql_query($q);
		
		// respond to browser
		if ($result)
		{
			$msg = "Photo deleted";
			respond($msg, 1, $name);
		}
		else
		{
			$msg = "Files deleted but databse error";
			respond($msg, 0, "/");
		}
	}
	else
	{
		// FAIL TO DELETE ONE OR BOTH FILES
		// translate boolean into visible character
		$o = ($o ? 1 : 0);
		$t = ($t ? 1 : 0);
		$msg = "File deletion error: o=" . $o . " t=" . $t;
		respond($msg, 0, "/");
	}	
}

// ***** rotate pic file and its thumb file
function rotate($name)
{
	// get filenames for this pic from database
	$q = "SELECT * FROM pics WHERE name = '$name'";
	$row = mysql_fetch_array(mysql_query($q));
	$orign = $row["file"];
	$thumbn = $row["thumb"];
	$side = $row["side"];
	$owner = $row["owner"];

	// check permission
	if ($_SESSION["id"] != $owner)
	{	
		// photo to rotate is not owned by current logged in user
		$msg = "Permission to rotate was denied";
		respond($msg, 0, "/");
		exit();
	}

	// rotate files from disk
	rotateImage($orign, $orign, -90);
	rotateImage($thumbn, $thumbn, -90);
	
	// update record in databse if both files are indeed rotated
	// UPDATE DB
	// toggle orientation paramater
	$side = (($side == "L") ? "P" : "L");
	$q = "UPDATE pics SET side = '$side' WHERE name = '$name'";
	$result = mysql_query($q);
	
	// respond to browser
	if ($result)
	{
		$msg = "Photo rotated";
		respond($msg, 1, $name);
	}
	else
	{
		$msg = "Files rotated but databse error";
		respond($msg, 0, "/");
	}
}

// ***** send response back to webpage
function respond($msg = "", $do = 0, $name = "/")
{
	// send message and commands back to browser
	$response["message"] = $msg;
	$response["do"] = $do;
	$response["name"] = $name;
	echo(json_encode($response));
}

// ***** rotate an image the easy way
// http://roshanbh.com.np/2008/06/rotate-image-in-php.html
function rotateImage($sourceFile,$destImageName,$degreeOfRotation)
{
	//function to rotate an image in PHP
	//developed by Roshan Bhattara (http://roshanbh.com.np)
	// MODIFIED BY MAZ

	//get the detail of the image
	$imageinfo = getimagesize($sourceFile);
	switch($imageinfo['mime'])
	{
	//create the image according to the content type
	case "image/jpg":
	case "image/jpeg":
	case "image/pjpeg": //for IE
		$src_img = imagecreatefromjpeg("$sourceFile");
		//rotate the image according to the spcified degree
		$src_img = imagerotate($src_img, $degreeOfRotation, 0);
		//output the image to a file
		imagejpeg($src_img,$destImageName,100);
		break;
	case "image/gif":
		$src_img = imagecreatefromgif("$sourceFile");
		//rotate the image according to the spcified degree
		$src_img = imagerotate($src_img, $degreeOfRotation, 0);
		//output the image to a file
		imagegif($src_img,$destImageName);
		break;
	case "image/png":
	case "image/x-png": //for IE
		$src_img = imagecreatefrompng("$sourceFile");
		//rotate the image according to the spcified degree
		$src_img = imagerotate($src_img, $degreeOfRotation, 0);
		//output the image to a file
		imagepng($src_img,$destImageName,0);
		break;
	}
	
	// unload image object from memory
	imagedestroy($src_img);
	$src_img = null;
}

?>