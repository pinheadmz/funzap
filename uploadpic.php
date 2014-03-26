<?
/*
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************

reference:
http://www.w3schools.com/php/php_file_upload.asp
*/

	// redirect back to login page if you are not sending me anything
	if (empty($_FILES))
	{
		header( 'Location: http://www.MatthewZipkin.com/funzap' ) ;
		exit();
	}

// cookies and database
require_once('safe/common.php');
require_once('safe/sqlcommon.php');

// initialize response variables
$do = 0;
$msg = "";
$link = "/";

// file must be gif or jpeg under 10 MB
if ((($_FILES["pic"]["type"] == "image/gif")
|| ($_FILES["pic"]["type"] == "image/jpeg")
|| ($_FILES["pic"]["type"] == "image/png")
|| ($_FILES["pic"]["type"] == "image/pjpeg"))
&& ($_FILES["pic"]["size"] < 10000000))
{
	// return error code
	if ($_FILES["pic"]["error"] > 0)
	{
		echo "Return Code: " . $_FILES["pic"]["error"] . "<br />";
	}
	else
	{
		
		// load image from post data
		// reference: http://www.white-hat-web-design.co.uk/blog/resizing-images-with-php/
		// determine file type and dimensions
		$file = $_FILES["pic"]["tmp_name"];
		$pic = getimagesize($file);
		$w = $pic[0];
		$h = $pic[1];
		$ext = image_type_to_extension($pic[2]);
		
		// orientation
		$side = ($w > $h ? "L" : "P");

		// sexy new name, user id plus 12 digit random number, must be unique for database
		do {
		$name = $_SESSION['id'] + 100000000000;
		$name .= "_";
		$name .= rand(100000, 999999);
		$name .= rand(100000, 999999);
		} while (mysql_num_rows(mysql_query("SELECT * FROM pics WHERE name = '$name';")) != 0);
		
		// save original and thumbnail, one at a time for php memory limit	
		include("safe/SimpleImage.php");
		$orig = new SimpleImage();
		$orig->load($file);
		// resize original to 2000px MAX on longest side for php memory limit when editing
		if (($w > 2000) && ($w > $h))
			$orig->resizeToWidth(2000);
		elseif (($h > 2000) && ($h > $w))
			$orig->resizeToHeight(2000);
		$orign = "picuploads/orig/" . $name . $ext;
		$orig->save($orign);
		
		// unload object from memory before continuing
		unset($orig);
		
		$thumb = new SimpleImage();
		$thumb->load($file);
		// resize thumbnail to 200px on longest side
		if ($w > $h)
			$thumb->resizeToWidth(200);
		else
			$thumb->resizeToHeight(200);
		$thumbn = "picuploads/thumb/" . $name . "_thumb" . $ext;
		$thumb->save($thumbn);
		
		// unload object from memory before continuing
		unset($thumb);
		
		// update database
		// prepare query
		$q = "INSERT INTO pics (name, file, thumb, side, owner) VALUES ('";
		$q .= $name . "','";
		$q .= $orign . "','";
		$q .= $thumbn . "','";
		$q .= $side . "','";
		$q .= $_SESSION['id'] . "');";
		
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
			// GOOD UPDATE
			$link = $thumbn;
			$do = 1;
			$msg = "Upload complete!";
		}

	}
}
else
{
	$msg = "Invalid file";
}

// send message and commands back to browser
$response["message"] = $msg;
$response["do"] = $do;
$response["link"] = $link;
$response["name"] = $name;
// browser has to deal with iframe target instead of regular AJAX, so...
echo(json_encode($response));

?>