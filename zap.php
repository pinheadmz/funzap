<?
	// enable shit
	require_once("safe/sqlcommon.php");

	// script must be called with pic id in GET
	if (empty($_GET))
		exit();

	// get details from GET variable sent by browser
	$name = mysql_real_escape_string($_GET["zap"]);

	// get details for this zap from database
	$q = "SELECT * FROM jigsaw WHERE name = '$name'";
	$result = mysql_query($q);
	
	// invalid zap id number
	if (mysql_num_rows($result) == 0)
	{
		echo("Invalid FunZap ID number");
		exit();
	}
	
	$row = mysql_fetch_array($result);
	$owner = $row["owner"];
	$pic = $row["pic"];
	$photox = $row["photox"];
	$photoy = $row["photoy"];
	$piecenumber = $row["piecenumber"];
	$prize = $row["prize"];
	$prizemsg = $row["prizemsg"];
	$friendname = $row["friendname"];
	$friendemail = $row["friendemail"];


	// get senders user info 
	$q = "SELECT * FROM login WHERE id = '$owner'";
	$row = mysql_fetch_array(mysql_query($q));
	$ownerfname = $row["fname"];
	$ownerlname = $row["lname"];

	// "encrypt" prize data
	// http://php.net/manual/en/function.bin2hex.php
	$prizemsg = bin2hex($prizemsg);
	
?>

<!DOCTYPE html>
<html>
<!-- 
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************
-->
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Fun Zap! Jigsaw</title>
	
	<!-- jQuery and jQuery UI for draggables -->
	<script type="text/javascript" src="include/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="include/jquery-ui-1.8.21.custom.min.js"></script>
	
	<!-- for mobile touch application: http://touchpunch.furf.com/ -->
	<script type="text/javascript" src="include/jquery.ui.touch-punch.min.js"></script>
	
	<!-- server inserts global variable values before main script is loaded-->
	<script type="text/javascript">
		// initialize globals
		var photox = <?= $photox ?>;
		var photoy = <?= $photoy ?>;
		var piecenumber = <?= $piecenumber ?>;
		var pic = "url('<?= $pic ?>')";
		var prize = "<?= $prize ?>";
		var prizemsg = "<?= $prizemsg ?>";
	</script>

	<!-- my code -->
	<link type="text/css" href="css/funzap.css" rel="stylesheet" />
	<link type="text/css" href="css/jigsaw.css" rel="stylesheet" />
	<script type="text/javascript" src="js/jigsaw.js"></script>

<? include("safe/header.php"); ?>
	
	<!-- "message headers" -->
	<div id="msgheader">
	Hello, <?= $friendname ?>!<br>
	This <h2>Fun Zap!</h2> message was made just for you by your friend <?= $ownerfname ?> <?= $ownerlname ?><br>
	<a href="http://www.MatthewZipkin.com/funzap" target="_blank">Click here to register for <h2>Fun Zap!</h2>, log in, and send your own personalized, playable photo messages!</a>
	</div>
	
	<!-- main puzzle container, centered -->
	<div id="puzzlec"></div>

	<!-- prize container, centered -->
	<div id="prizec"></div>
</html>