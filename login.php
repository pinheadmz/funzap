<?
	// check for logged in user, enable cookies
	require_once("safe/urlcommon.php");
?>

<link type="text/css" href="css/login.css" rel="stylesheet" />
<script type="text/javascript" src="js/login.js"></script>
<div id="logincc">
<div id="loginc">
	<div id="hello" onclick="window.open('http://www.matthewzipkin.com/funzap/zap.php?zap=100000000015_164204142488')">
		<span class="top">It's the ultimate social game,<br>
		because YOU make it<br>for your friend!</span><br><br>
		<span class="mid">You pick a photo, you make the rules, you make a prize!</span><br><br>
		<span class="bot">Sign up for free, and start sending your friends<br>the funnest messages they've ever received!</span><br><br>
		<img src="i/hello.jpg">
	</div>

	<div id="login">
		<form id="loginform">
			<div id="forgot">Forgot your password?</div>
			<table>
				<th colspan="2"> Registered users, log in: </th>
				<tr><td>Email:</td><td><input type="email" id="loginemail" name="email" spellcheck="false" placeholder="Email"></td></tr>
				<tr><td>Password:</td><td><input type="password" id="loginpassword" name="password" spellcheck="false" placeholder="Password"></td></tr>
				<tr><td colspan="2">
					<div id="loginsubmit" class="bluebutton">Log In!</div>
					</td></tr>
			</table>
		</form>
		<form id="signupform" autocomplete="on">
			<table>
				<th colspan="2"> New users, sign up for free:</th>
				<tr><td>First name:</td><td><input type="text" id="signupfname"  name="fname" spellcheck="false" placeholder="First name"></td></tr>
				<tr><td>Last name:</td><td><input type="text" id="signuplname"  name="lname" spellcheck="false" placeholder="Last name"></td></tr>
				<tr><td>Email:</td><td><input type="email" id="signupemail"  name="email" spellcheck="false" placeholder="Email"></td></tr>
				<tr><td colspan="2">
					<div id="signupsubmit" class="greenbutton">Sign Up!</div>
					</td></tr>
			</table>
		</form>
	</div>
	<div id="response"><img src='i/loading.gif'>  Loading  <img src='i/loading.gif'></div>
</div>
</div>