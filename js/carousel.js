/*
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************
*/

// ***** globals
var picbuttons = "<div class='deletepic'>DELETE</div><div class='rotatepic'>ROTATE</div>";
var prizebutton = "<div class='prizepic'><h2>PRIZE!</h2></div>";
var selectedpic = null;
var selectedprizepic = null;

// ***** runs on page load
$(document).ready(function(){
	// make carousel touch sensitive
	$("#carousel").draggable({axis: "x"});
	
	// add buttons to current pics
	$(".carouselpic").append(picbuttons);
	
	// add functions to those buttons and pics
	$(".deletepic").live("click", function(){deletepic($(this).parent().attr('id'));});
	$(".rotatepic").live("click", function(){rotatepic($(this).parent().attr('id'));});
	$(".carouselpic img").live("click", function(){selectpic($(this).parent().attr('id'));});
	$(".prizepic").live("click", function(){prizepic($(this).parent().attr('id'));});

	// placeholder message in funzap maker
	$("#makezappic").html("<span style='color:red'>choose a photo below</span>");

	// check for zero pics
	checkpicnum();
});

// ***** add new pic to the image carousel
function carouseladd(name, link)
{
	// create new div with 0 width and add it to the carousel
	var newdiv = "<div id='" + name + "' class='carouselpic' style='width:0px;'><img src='" + link + "'>" + picbuttons + "</div>";
	$("#carousel").prepend(newdiv);
	
	// generate new target width for carousel once new div is in place
	var newwidth = $("#carousel").width() + 230;
	
	// open new image div and lengthen carousel
	$("#carousel").width(newwidth);
	$("#" + name).animate({width: "200px"}, 1500);

	// increment pic counter & change message
	numberpics++;
	checkpicnum();
}

// ***** selects a single pic from carousel
function selectpic(picid)
{
	$(".carouselpic.select").removeClass("select");
	// in case picid is null
	if (picid)
	{
		$("#makezappic").html("");
		$("#" + picid).addClass("select");
		// put tiny thumb in makezap window
		var picurl = $("#" + picid).find('img').attr('src');
		$("#makezappic").css("background-image", "url('" + picurl + "')" );
	}
	else
	{
		$("#makezappic").css("background-image", "none");
		$("#makezappic").html("<span style='color:red'>choose a photo below</span>");
	}
	// update global variable
	selectedpic = picid;	
}

// ***** selects a single pic from carousel for PRIZE for ZAP
function prizepic(picid)
{
	$(".prizepic.select").removeClass("select");
	// in case picid is null
	if (picid)
	{
		$("#tinyprizepic").html("");
		$("#" + picid + " > .prizepic").addClass("select");
		// put tiny thumb in makezap window
		var picurl = $("#" + picid).find('img').attr('src');
		$("#tinyprizepic").css("background-image", "url('" + picurl + "')" );
	}
	else
	{
		$("#tinyprizepic").css("background-image", "none");
		$("#tinyprizepic").html("<span style='color:red'>choose a photo below</span>");
	}
	// update global variable
	selectedprizepic = picid;	
}

// ***** deletes pic from user account, tells server to delete pic file and DB entry
function deletepic(picid)
{
	// are you sure?
	if (!confirm("Delete this photo?"))
		return false;
	
	// wait message
	cleanup();
	respond(loading);
	
	// send command to server
	$.post("modifypic.php",{name: picid, func: "del"}, function(response){deletepicrespond(response);},"json");	
}

// ***** handle server response to delete pic command
function deletepicrespond(response)
{
	// server commands message only or load content
	switch (response['do'])
	{
	case 0:
		respond(response["message"]);
		break;
	case 1:
		respond(response["message"]);
		// check if deleted pic was selected and update variable
		if ($("#" + response["name"]).hasClass('select'))
			selectpic(null);
		// remove corresponding pic from carousel and container width
		$("#" + response["name"]).animate({width: "0px"}, 1500, 'linear', function(){
			$("#" + response["name"]).remove();
			var newwidth = $("#carousel").width() - 230;
			$("#carousel").width(newwidth);
		});

		// decrement pic counter & check
		numberpics--;
		checkpicnum();
		break;
	}

	// hide message after 2 seconds
	setTimeout(function(){cleanup();},2000);
}

// ***** rotates pic on server 90deg clockwise and reloads image on page
function rotatepic(picid)
{
	// wait message
	cleanup();
	respond(loading);
	
	// send command to server
	$.post("modifypic.php",{name: picid, func: "rot"}, function(response){rotatepicrespond(response);},"json");	
}

// ***** handle server response to delete pic command
function rotatepicrespond(response)
{
	// server commands message only or load content
	switch (response['do'])
	{
	case 0:
		respond(response["message"]);
		break;
	case 1:
		respond(response["message"]);
		// get image thumbnail URL in carousel
		var imgurl = $("#" + response["name"]).find('img').attr('src');
		// force image reload using meaningless GET parameter
		$("#" + response["name"]).find('img').attr('src', imgurl + "?x=0");
		// do the same for the tiny thumb in make window if selected
		if ($("#" + response["name"]).hasClass('select'))
			$("#makezappic").css("background-image", "url('" + imgurl + "?x=0')" );
		break;
	}

	// hide message after 2 seconds
	setTimeout(function(){cleanup();},2000);
}

// ***** add or subtract buttons to select photo for a prize b = boolean. highlight selected pic if applicable
function prizebuttons(b)
{
	if(!b)
	{
		$(".prizepic").remove();
	}
	else
	{
		$(".carouselpic").append(prizebutton);
		if (selectedprizepic != null)
			$("#" + selectedprizepic + " > .prizepic").addClass("select");
	}
}


// **** if theres no pics, add a message
function checkpicnum()
{
	if (numberpics == 0)
	{
		// add upload message to carousel
		$("#carouselc").append("<div id='uploadsomething' style='color:red;font-size:30px;'>You have no photos. Click UPLOAD PHOTO above to begin!</div>");
	}
	else
	{
		$("#uploadsomething").remove();
	}
}





