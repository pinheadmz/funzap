<?
/*
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************
*/

// enable cookies
require_once("safe/common.php");

//end session
session_unset();
session_destroy();

// redirect user back to homepage
header( 'Location: http://www.MatthewZipkin.com/funzap' );

?>