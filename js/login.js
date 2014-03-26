/*
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************
*/

// ***** runs on page load
$(document).ready(function(){
	// bind submit actions to form buttons
	$("#loginsubmit").click(function(){login();});
	$("#signupsubmit").click(function(){signup();});
	$("#forgot").click(function(){forgotpw();});

	// hijack enter key from submit forms to running js code submits
	$("form").submit(function(){return false;});

	// clear messages when user clicks on input field
	$(":input").focus(function(){$("#response").slideUp('fast');});

	// bind enter key to form submissions http://api.jquery.com/keypress/
	$("#loginform").keypress(function(e){
		if (e.which == 13)
			login();
	});
	$("#signupform").keypress(function(e){
		if (e.which == 13)
			signup();
	});

	// check for login info in cookie and load info into fields
	$l = (($.cookie("email")) ? $.cookie("email") : null);
	$p = (($.cookie("password")) ? $.cookie("password") : null);
	$("#loginemail").val($l);
	$("#loginpassword").val($p);
});



// ***** submit login form
function login()
{
	// grab info from form fields
	var email = $("#loginemail").val();
	var password = $("#loginpassword").val();

	// store info in cookie for later
	$.cookie("email", email, {expires: 7, domain: 'matthewzipkin.com', path: '/'});
	
	// hides cursor from input fields for prettiness
	$(":input").blur();

	// password must not be blank
	if(!password)
	{
		respond("<span style='color:red;'>Please enter a password</span>");
		return false;
	}

	// verify email before continuing
	if (validateEmail(email))
	{
		// display progress bar
		respond(loading);
		// post form data to server
		$.post("funzaplogin.php",{email: email, password: password},function(response){loginrespond(response);},"json");
	}
	else
		respond("<span style='color:red;'>Invalid email address</span>");
}


// ***** submit signup form
function signup()
{
	// hides cursor from input fields for prettiness
	$(":input").blur();
		
	// verify email before continuing
	var email = $("#signupemail").val();
	var fname = $("#signupfname").val();
	var lname = $("#signuplname").val();
	if (validateEmail(email))
	{
		if(!fname || !lname)
			respond("<span style='color:red;'>Please enter first & last name</span>");
		else
		{
			// display progress bar
			respond(loading);
			// post form data to server
			$.post("funzapsignup.php",{email: email, fname: fname, lname: lname},function(response){signuprespond(response);},"json");
		}
	}
	else
		respond("<span style='color:red;'>Invalid email address</span>");
}

// ***** process server response to signup attempt
function signuprespond(response)
{
	respond(response["message"]);
}

// ***** process server response to login attempt
function loginrespond(response)
{
	// server commands message only or load content
	switch (response['do'])
	{
	case 0:
		respond(response["message"]);
		break;
	case 1:
		respond(response["message"]);
		setTimeout(function(){loadtomainc(response["link"]);},1000);
		break;
	}
}

// **** reset PW
function forgotpw()
{
	// hides cursor from input fields for prettiness
	$(":input").blur();
		
	// verify email before continuing
	var email = $("#loginemail").val();

	if (validateEmail(email))
	{
		if (confirm('Click OK to reset your password'))
		{
			// display progress bar
			respond(loading);
			// post form data to server
			$.post("forgotpw.php",{email: email},function(response){forgotpwrespond(response);},"json");
		}
	}
	else
		respond("<span style='color:red;'>Invalid email address</span>");
}


// **** process pw reset
function forgotpwrespond(response)
{
	respond(response["message"]);
}
