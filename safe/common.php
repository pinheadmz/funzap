<?
	// enable cookies
	session_start();
	
	// if no logged in user, redirect to homepage
	if (!isset($_SESSION["id"]))
	{
		header( 'Location: http://www.MatthewZipkin.com/funzap' ) ;
		exit();
	}
?>