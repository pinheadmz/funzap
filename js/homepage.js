/*
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************
*/

// **** globals
var zapid = null;

// ***** runs on page load
$(document).ready(function(){
	// bind functions to menu buttons
	$("#menuuploadpic").click(function(){
		cleanup();
		$("#uploadpic").slideDown();
	});
	$("#menumakezap").click(function(){
		cleanup();
		$("#makezap").slideDown();
	});
	$("#menuchangepw").click(function(){
		cleanup();
		$("#changepw").slideDown();
	});
	$("#menulogout").click(function(){window.location.href = "funzaplogout.php" ;});
	$("#previewcancel").click(function(){
		$("#zappreview").slideUp('fast',function(){
			$("#zappreviewframe").attr('src','');
		});
	});
	$("#previewgo").click(function(){
		previewgo();
	});
	
	// show prize selector buuttons if option is selected, blur out unused text field options
	$(".makezapprize").change(function(){
		if ($("#pzpic").prop("checked"))
		{
			if (selectedprizepic == null)
				$("#tinyprizepic").html("<span style='color:red'>choose a photo below</span>");
			prizebuttons(true);
			$("#prizemsg").css("opacity","0.5");
			$("#prizeurl").css("opacity","0.5");
			$("#tinyprizepic").css("opacity", "1");
		}
		else if ($("#pzurl").prop("checked"))
		{
			$("#tinyprizepic").html("");
			prizebuttons(false);
			$("#prizemsg").css("opacity","0.5");
			$("#prizeurl").css("opacity","1");
			$("#tinyprizepic").css("opacity", "0.5");
		}
		else if ($("#pzmsg").prop("checked"))
		{
			$("#tinyprizepic").html("");
			prizebuttons(false);
			$("#prizemsg").css("opacity","1");
			$("#prizeurl").css("opacity","0.5");
			$("#tinyprizepic").css("opacity", "0.5");
		}

	// move highlight to selected option
	$('input[name="prize"]').parent().removeClass('hover');
	$('input[name="prize"]:checked').parent().addClass('hover');
	});
	
	// move highlight on difficulty options
	$(".makezapdiff").change(function(){
		// move highlight to selected option
		$('input[name="piecenumber"]').parent().removeClass('hover');
		$('input[name="piecenumber"]:checked').parent().addClass('hover');
	});
	
	
	// hijack enter key from submit forms to running js code submits
	//$("form").submit(function(){return false;});

	// clear messages when user clicks on input field
	$(":input").focus(function(){$("#response").slideUp('fast');});

	// bind functions to changepw form buttons, cancel is GLOBAL
	$(".cancel").click(function(){cleanup();});
	$("#changepwsubmit").click(function(){changepw();});
	$("#uploadpicsubmit").click(function(){uploadpic();});
	$("#makezapsubmit").click(function(){makezap();});

	// bind enter key to form submissions http://api.jquery.com/keypress/
	$("#changepwform").keypress(function(e){
		if (e.which == 13)
			changepw();
	});
	$("#uploadpicform").keypress(function(e){
		if (e.which == 13)
			uploadpic();
	});
	$("#makezapform").keypress(function(e){
		if (e.which == 13)
			makezap();
	});

	// ESC key always cancels
	$(document).keyup(function(e){
		if (e.which == 27)
			cleanup();
	});
	
});

// ***** hides all dialog boxes and shit like that
function cleanup()
{
	$("#changepw input").val(''); 
	$("#uploadpic input").val(''); 
	$(".homepageform").slideUp(); 
	$("#response").slideUp('fast');
	$("#pzmsg").prop("checked", true);
	$("#p12").prop("checked", true);
	$(".makezapprize").trigger('change');
	$(".makezapdiff").trigger('change');
}

// ***** submit password change
function changepw()
{
	// hides cursor from input fields for prettiness
	$(":input").blur();

	// fields must not be blank
	if(!$("#oldpw").val() || !$("#changepw1").val() || !$("#changepw2").val())
	{
		respond("<span style='color:red;'>Please fill out all three fields</span>");
		return false;
	}

	// new passwords must match
	var oldpw = $("#oldpw").val();
	var changepw1 = $("#changepw1").val();
	var changepw2 = $("#changepw2").val();

	if (changepw1 == changepw2)
	{
		// display progress bar
		respond("<img src='i/loading.gif'>  Loading  <img src='i/loading.gif'>");
		// post form data to server
		$.post("changepw.php",{oldpw: oldpw, changepw1: changepw1, changepw2: changepw2},function(response){changepwrespond(response);},"json");
	}
	else
		respond("<span style='color:red;'>New passwords do not match</span>");
}

// ***** process server response to change pw attempt
function changepwrespond(response)
{
	// server commands message only or load content
	switch (response['do'])
	{
	case 0:
		respond(response["message"]);
		break;
	case 1:
		respond(response["message"]);
		// clear all input fields and close dialog and response box, after 2 second
		setTimeout(function(){cleanup();},2000);
		break;
	}
}

// ***** upload pic
function uploadpic()
{
	// loading message to user
	respond(loading);
	
	// check file has been chosen
	if(!$("#pic").val())
	{
		respond("Please choose a file to upload");
		return false;
	}
	
	// check file extension is valid
	var ext = $("#pic").val();
	ext = ext.split('.').pop().toLowerCase();
	if (ext != 'gif' && ext != 'jpg' && ext != 'jpeg' && ext != 'png')
	{
		respond("File must PNG, GIF or JPG");
		return false;
	} 
		
	// check file size if browser is compatible
	// http://stackoverflow.com/questions/3717793/javascript-file-upload-size-validation
	input = document.getElementById('pic');
	if ((typeof window.FileReader == 'function') && (input.files))
	{
		if (input.files[0].size > 10000000)
		{
			respond("File must be less than 10MB");
			return false;
		}		
	}
	
	// freeze controls
	freeze();
		
	// submit form which sends pic file to server
	$('#uploadpicform').submit();

	// attach event listener to target iframe to proceed once its done loading server response
	$("#target").bind("load",function(){uploadpicrespond();});	
}

// ***** process server response for pic upload attempt
function uploadpicrespond()
{
	// turn off event listener
	$("#target").unbind("load");
	
	// release controls
	unfreeze();
	
	// pull content from body of html of iframe and rebuild array from json string
	var target = $("#target").contents().find("body").html();
	var response = jQuery.parseJSON(target);
	
	// server commands message only or load content
	switch (response['do'])
	{
	case 0:
		respond(response["message"]);
		break;
	case 1:
		respond(response["message"]);
		carouseladd(response["name"], response["link"]);
		setTimeout(function(){cleanup();},2000);
		break;
	}
}

// ***** make zap!
function makezap()
{
	respond(loading);
	
	// get info from input form
	var piecenumber = $("input[name=piecenumber]:checked").val();
	var prize = $(".makezapprize:checked").attr('id');
	var friendname = $("#makezapfriendname").val();
	var friendemail = $("#makezapfriendemail").val();
	var pzurl = $("#prizeurl").val();
	var pzmsg = $("#prizemsg").val();
	
	// check for form completion
	if (selectedpic == null)
	{
		respond("<span style='color:red;'>Select a photo from the gallery below</span>");
		return false;
	}
	if (!friendname || !validateEmail(friendemail))
	{
		respond("<span style='color:red;'>Enter valid name and email for your friend</span>");
		return false;
	}
	if ((prize == "pzpic") && (selectedprizepic == null))
	{
		respond("<span style='color:red;'>Choose a prize photo below</span>");
		return false;
	}
	if ((prize == "pzurl") && (!pzurl))
	{
		respond("<span style='color:red;'>Enter a valid prize URL</span>");
		return false;
	}
	if ((prize == "pzmsg") && (!pzmsg))
	{
		respond("<span style='color:red;'>Enter a prize message</span>");
		return false;
	}
	
	// put the right msg value for prize chosen  -- double triple ternary!
	var prizemsg = ((prize == "pzpic") ? selectedprizepic : ((prize == "pzurl") ? pzurl : pzmsg));
	
	// send all data to server to make the Zap!
	$.post("jigsaw.php",{pic: selectedpic, piecenumber: piecenumber, prize: prize, prizemsg: prizemsg, friendname: friendname, friendemail: friendemail},function(response){makezaprespond(response);},"json");
}

// **** process server response to make zap attempt
function makezaprespond(response)
{
	// server commands message only or load content
	switch (response['do'])
	{
	case 0:
		respond(response["message"]);
		break;
	case 1:
		respond(response["message"]);
		pview(response["link"]);
		zapid = response["name"];
		break;
	}
}

// **** lightbox preview content for finished zaps approval
function pview(url)
{
	// load content into iframe and display
	$("#zappreviewframe").attr('src', url);
	$("#zappreview").slideDown();
}

// **** send that zap!
function previewgo()
{
	freeze();
	// get zap id
	$.post("sendzap.php",{zapid: zapid},function(response){previewgorespond(response);},"json");
}

// **** process server respnse to sending zap!
function previewgorespond(response)
{
	unfreeze();
	$("#zappreview").slideUp('fast',function(){
		$("#zappreviewframe").attr('src','');
	});

	// server commands message only or load content
	switch (response['do'])
	{
	case 0:
		respond(response["message"]);
		break;
	case 1:
		cleanup();
		respond(response["message"]);
		setTimeout(function(){cleanup();},2000);
		break;
	}
}
