/*
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************
*/

// ***** global globals
var loading = "<img src='i/loading.gif'>  Loading  <img src='i/loading.gif'>";

// ***** runs on page load
$(document).ready(function(){

});

// ***** loads content from server into mainc in a pretty way
function loadtomainc(content)
{
	// hide container while we change the scenes
	$('#mainc').slideUp('slow', function(){
		// load content into hidden container
		$('#mainc').load(content, function(){
			// expose content when fully loaded
			$('#mainc').delay(1000).slideDown('slow');
		});
	});
	
}

// ***** check email against valid regex 
function validateEmail(email)
{ 
	// http://stackoverflow.com/questions/46155/validate-email-address-in-javascript
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

// ***** blurs and blocks entire page
function freeze()
{
	// get current position of current response window to cover it exactly
	var newtop = $("#response").offset().top;
	$("#freezemsg").css("top", newtop);
	$("#freezec").css("display", "block");
	$("#freezec").css("z-index", 9000);
	$("#response").slideUp(0);
}
function unfreeze()
{
	$("#freezec").css("display", "none");
	$("#freezec").css("z-index", -9000);
}

// ***** opens response div with new message
function respond(message)
{
	// hide current message
	$("#response").slideUp('fast', function(){
		// update message
		$("#response").html(message);
	});

	// show new message
	$("#response").slideDown('fast');

}

