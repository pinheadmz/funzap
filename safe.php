<?
	// buffer output until ready
	ob_start();

	//enable cookies and loged in user
	require_once("safe/common.php");
	require_once("safe/sqlcommon.php");	

	//grab image id from URL GET info and add path to file
	$i = "safe/";
	$i .= $_GET["i"];
	
	//build headers for file output
	// http://stackoverflow.com/questions/9742076/php-send-get-request-and-get-picture-in-return
	$size = getimagesize($i);
	
	// send file
	header('Content-type: '.$size['mime']);
	readfile($i);
	
	// go!
	ob_end_flush();
?>