<?
	// this doc only visible when embedded in homepage like its supposed to -- no cheating!
	if(($_SERVER["REQUEST_URI"] != "/funzap/") && ($_SERVER["REQUEST_URI"] != "/funzap/index.php"))
	{
		header( 'Location: http://www.MatthewZipkin.com/funzap' ) ;
		exit();
	}
?>