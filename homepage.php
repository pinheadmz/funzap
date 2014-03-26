<?
	// check for logged in user, enable cookies
	require_once("safe/common.php");

	// load user info into variables
	$email = $_SESSION["email"];
	$id = $_SESSION["id"];
	$fname = $_SESSION["fname"];
	$lname = $_SESSION["lname"];

	// make shorter string to fit in button on website
	if (strlen($email) >= 20)
	{
		$email = substr($email, 0, 20);
		$email .= "...";
	}
		

?>

<link type="text/css" href="css/homepage.css" rel="stylesheet" />
<script type="text/javascript" src="js/homepage.js"></script>

<!-- ** if mobile -->
<script type="text/javascript">

if (navigator.userAgent.match(/Android/i) ||
             navigator.userAgent.match(/webOS/i) ||
             navigator.userAgent.match(/iPhone/i) ||
             navigator.userAgent.match(/iPod/i) ||
             navigator.userAgent.match(/iPad/i) ||
             navigator.userAgent.match(/BlackBerry/) || 
             navigator.userAgent.match(/Windows Phone/i) || 
             navigator.userAgent.match(/ZuneWP7/i)
             ) {
		// bind touch events to hover events
		// http://stackoverflow.com/questions/3501586/css-dynamic-navigation-with-hover-how-do-i-make-it-work-in-ios-safari
		$("#menuaccount").live('touchstart', function(){$("#menuaccount").toggleClass('hover');	});
               }
</script>
<!-- ** end if -->

<div id="homepagemenuc">
	<ul id="homepagmenu">
	<li id="menuuploadpic">Upload Photo</li>
	<li id="menumakezap">Make a <h2>Zap!</h2></li>
	<li id="menuaccount">Account: <?= $email ?>
		<ul>
		<li id="menuchangepw"> Change Password </li>
		<li id="menulogout"> Logout </li>
		</ul>
	</li> 
	</ul>
</div>

<div id="homepagec">
	<div id="changepw" class="homepageform">
		<form id="changepwform">
			<table>
				<tr><td>Old Password:</td><td><input type="password" id="oldpw" name="password" spellcheck="false" placeholder="Old password"></td></tr>
				<tr><td>New Password:</td><td><input type="password" id="changepw1" name="password" spellcheck="false" placeholder="New password"></td></tr>
				<tr><td>Re-Enter New Password:</td><td><input type="password" id="changepw2" name="password" spellcheck="false" placeholder="Re-enter new password"></td></tr>
				<tr><td colspan="2">
					<div id="changepwsubmit" class="bluebutton">Change Password</div>
					<div id="changepwcancel" class="greenbutton cancel" >Cancel</div>
					</td></tr>
			</table>
		</form>
	</div>
	
	<div id="uploadpic" class="homepageform">
		<form id="uploadpicform" action="uploadpic.php" method="post" enctype="multipart/form-data" target="target">
			<table>
				<tr><td><input type="file" name="pic" id="pic"></td></tr>
				<tr><td><div id="uploadpicsubmit" class="bluebutton" >Upload</div>
					<div id="uploadpiccancel" class="greenbutton cancel" >Cancel</div>
					</td></tr>
			</table>
		</form>
	</div>
	
	<div id="makezap" class="homepageform">
		<form id="makezapform">
			<table><tr><td>
				<table style="height:270px">
					<tr><td>1. Choose a <h2>Zap!</h2>:</td><td class="makezapoption">Jigsaw Puzzle</div></td></tr>
					<tr><td id="makezapchoosepic">2. Select a photo:</td><td class="makezapoption"><div id="makezappic" class="tinypic"></div></td></tr>
					<tr><td>3. Choose difficulty:</td><td class="makezapoption"><ul>
						<label for="p6"><li><input type="radio" name="piecenumber" value="6" id="p6" class="makezapdiff">Easy (6 pieces)</li></label>
						<label for="p12"><li><input type="radio" name="piecenumber" value="12" id="p12" class="makezapdiff" checked="checked">Medium (12 pieces)</li></label>
						<label for="p24"><li><input type="radio" name="piecenumber" value="24" id="p24" class="makezapdiff">Hard (24 pieces)</li></label>
						<label for="p48"><li><input type="radio" name="piecenumber" value="48" id="p48" class="makezapdiff">Insane (48 pieces)</li></label>
					</ul></td></tr>
				</table></td><td>
				<table style="height:270px">
					<tr><td>4. Choose a prize:</td><td class="makezapoption"><ul>
						<label for="pzpic"><li><input type="radio" name="prize" value="pic" class="makezapprize" id="pzpic">
							Another photo:<br><div id="tinyprizepic" class="tinypic"></div></li></label>
						<label for="pzurl"><li><input type="radio" name="prize" value="url" class="makezapprize" id="pzurl" placeholder="http://www.MatthewZipkin.com/funzap">
							A link:<br><input type="url" name="prizeurl" id="prizeurl" value="http://www.MatthewZipkin.com/funzap" style="opacity:0.5"></li></label>
						<label for="pzmsg"><li><input type="radio" name="prize" value="msg" class="makezapprize" id="pzmsg" checked="checked" placeholder="You Win!">
							A simple message:<br><input type="text" name="prizemsg" id="prizemsg" value="You win!"></li></label>
					</ul></td></tr>
				</table></td></tr>
				<tr><td colspan="2">
					<table style="width:940px">
						<tr><td>5. Send to a friend:</td>
							<td class="makezapoption">Name:&nbsp;<input type="text" id="makezapfriendname" placeholder="Your friend's name"> Email:&nbsp;<input type="email" id="makezapfriendemail" placeholder="Your friend's Email"></td></tr>
					</table>
				</td></tr>
				<tr><td colspan="2">
				<div id="makezapsubmit" class="bluebutton">Preview <h2>Zap!</h2></div>
				<div id="makezapcancel" class="greenbutton cancel" >Cancel</div>
				</td></tr>
			</table>
		</form>	
	</div>

	<div id="response"><img src='i/loading.gif'>  Loading  <img src='i/loading.gif'></div>

<? // include image carousel
include("safe/carousel.php"); ?>

</div>

<!-- secret iframe to hide asynchronous server responses -->
<iframe id="target" name="target" src="" style="display:none"></iframe>
