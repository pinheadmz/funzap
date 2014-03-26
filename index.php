<? // start cookies before HTML is processed
	session_start();
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
<title>Fun Zap! Make & Send Personalized Games with Your Friends</title>
	
<!-- jQuery and jQuery UI for draggables and cookies -->
<script type="text/javascript" src="include/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="include/jquery-ui-1.8.21.custom.min.js"></script>
<script type="text/javascript" src="include/jquery.cookie.js"></script>

<!-- for mobile touch application: http://touchpunch.furf.com/ -->
<script type="text/javascript" src="include/jquery.ui.touch-punch.min.js"></script>

<!-- my code -->
<link type="text/css" href="css/funzap.css" rel="stylesheet" />
<script type="text/javascript" src="js/funzap.js"></script>
	
<!-- ** if mobile -->
<script type="text/javascript">

if (navigator.userAgent.match(/Android/i) ||
             navigator.userAgent.match(/webOS/i) ||
             navigator.userAgent.match(/iPhone/i) ||
             navigator.userAgent.match(/iPod/i) ||
             navigator.userAgent.match(/BlackBerry/) || 
             navigator.userAgent.match(/Windows Phone/i) || 
             navigator.userAgent.match(/ZuneWP7/i)
             ) {
                // set viewport for mobile
		document.write("<meta name='viewport' content='width=980, initial-scale=1.0, user-scalable=yes'>");
               }
</script>
<!-- ** end if -->
<div id="freezec"><div id="freeze"></div><div id="freezemsg"><img src='i/loading.gif'>  Loading  <img src='i/loading.gif'></div></div>

<!-- floating lightbox div for completed zap preview -->
<div id='zappreview'>
	<div id='previewshade'></div>
	<div id='previewcontrols'><h2>Zap!</h2> Preview...<br>
		<div id='previewgo' class='bluebutton'>Send this <h2>Zap!</h2></div>
		<div id='previewcancel' class='greenbutton'>Cancel</div>
	</div>
	<iframe id='zappreviewframe' src=''></iframe>
</div>

<div id="container">

<? include("safe/header.php"); ?>

<div id="mainc">
	
	<? // send login page if not logged in, content if logged in
	if (isset($_SESSION["id"]))
	{
		require("homepage.php");
	}			
	else
		require("login.php");
	 ?>
		
</div>
	<div id="footer">About | Support | Careers | FAQ | Donate </div>
</div> <!-- container -->

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28095888-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>


</html>
