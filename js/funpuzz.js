/*
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************
*/

// initialize globals
var photox = 600;
var photoy = 800;
var bgsize = photox + "px " + photoy + "px";
var piecenumber = 0;
var totalpixels = 0;
var piecepixels = 0;
var piecesize = 0;
var piecerows = 0;
var piececols = 0;
var msgcounter = 0;
// inital default image before user upload
var pic = "url('i/photo.jpg')";

// **** uploads pic to server
function uploadpic()
{
	// submit form which sends pic file to server
	$('#uploadpic').submit();

	// attach event listener to target iframe to proceed once its done loading server response
	$("#response").bind("load",function(){picuploadresponse();});
}


// **** processes server response to uploaded pic, and then updates puzzle graphics
function picuploadresponse()
{
	// pull content from body of html of iframe, should just be URL of new image for puzzle
	var res = $("#response").contents().find("body").html();
	
	// set puzzle piece picture to new image URL
	pic = "url(" + res + ")";
	
	// set puzzle container image to new pic and resize
	$("#puzzlec").css("background-image", pic );
	$("#puzzlec").css("background-size", bgsize);

}


// **** makes a clickable message button appear in #puzzlec
function floatmsg(msg, action)
{
	// creates the div with the message and some javascript function
	$("#puzzlec").prepend("<div id='floatmsg" + msgcounter + "' class='floatmsg' onclick='" + action + "'>" + msg + "</div>");
	
	//fades it in
	$("#floatmsg" + msgcounter).animate({opacity: "1"},1000,"linear");
	
	// change id name of every msg div so they dont conflict
	msgcounter++;
}

// **** on load, display first message to user
$(document).ready(function(){floatmsg("Click here to start!","startpuzzle()");});

// **** start the game
function startpuzzle()
{
	// hide all floating messages, remove background image of complete photo and hide #puzzlec & options menu while we change the scenery
	$(".floatmsg").animate({opacity: "0"},500,"linear",
		function(){$("#puzzlec").animate({opacity: "0"},500,"linear",
			function(){$("#optionscover").css("z-index","100");
			$("#optionscover").animate({opacity: "0.8"},500,"linear",
				function(){$(".floatmsg").css("display","none");
				$("#puzzlec").css("background-image","none");
				slicepuzzle();}
			);}
		);}
	);

}

// **** create puzzle piece divs row by row with correct portion of photo
function slicepuzzle()
{
	// get number of pieces from input form
	piecenumber = $("input[name=piecenumber]:checked").val();
	
	// total number of pixels in the photo
	totalpixels = photox * photoy;
	
	// total number of pixels in each puzzle piece
	piecepixels = totalpixels / piecenumber;
	
	// x and y dimension of square piece
	piecesize = Math.sqrt(piecepixels);

	// number of rows and columns of pieces inside photo
	piecerows = photoy / piecesize;
	piececols = photox / piecesize;
		
	// create puzzle pieces row by row
	for (i = 0; i < piecerows; i++)
	{
		for (j = 0; j < piececols; j++)
		{
			// create piece and number it by id
			$("#puzzlec").append("<div class='piece' id='piece" + i + j + "'></div>");
			
			// set user-selected (or default) background-image of each piece and resize
			$("#piece" + i + j).css("background-image", pic);
			$("#piece" + i + j).css("background-size", bgsize);
			
			// set position of imaage inside of piece
			var xpos = (-1 * piecesize) * j;
			var ypos = (-1 * piecesize) * i;
			var bgpos = xpos + "px " + ypos + "px";
			$("#piece" + i + j).css("background-position", bgpos);
			
			// here's that amazing jQuery magic for dragging DIV's and snapping to grid
			$("#piece" + i + j).draggable({containment: "#puzzlec"});
			$("#piece" + i + j).draggable({snap: "#puzzlec"});
			$("#piece" + i + j).draggable({snap: ".piece"});		
		}
	}
	
	// set the width and height for all pieces in the css class, including 1px border on each edge
	$(".piece").css("width", piecesize-2);
	$(".piece").css("height", piecesize-2);
	
	// fade in completed puzzle
	$("#puzzlec").animate({opacity: "1"},500,"linear");

	// randomize piece placement!
	shakepuzzle();

	// start checking for solutions
	$(".piece").mouseup(function(){solution();});
}

// **** moves all the pieces to random places
function shakepuzzle()
{
	// move each piece, row by row, a random amount on x and y axis
	for (i = 0; i < piecerows; i++)
	{
		for (j = 0; j < piececols; j++)
		{
			// get current position to make sure future position is still in bounds
			var xcurr = $("#piece" + i + j).position().left;
			var ycurr = $("#piece" + i + j).position().top;
			
			// loop to keep pieces inside of boundaries after movement
			do
			{
				// pick a few random numbers to move piece on each axis
				var xmove = Math.floor( (Math.random()*1000)+1) -500;
				var ymove = Math.floor( (Math.random()*1400)+1) -700;

				//test out new location
				var xnew = xmove + xcurr;
				var ynew = ymove + ycurr;

				// debug
				//console.log("xcurr " + xcurr + " ycurr " + ycurr + " xmove " + xmove + " ymove " + ymove);
			}
			while (xnew > (photox - piecesize) || xnew < 0 || ynew < 0 || ynew > (photoy - piecesize));

		
			// animate movement! this is fun			
			$("#piece" + i + j).animate({top: ymove},1000,"linear");
			$("#piece" + i + j).animate({left: xmove},1000,"linear");
		}
	}
}

// **** checks for solved puzzle
function solution()
{
	// initialize correct-piece counter
	var wincounter = piecenumber;	
	
	// check each puzzle piece row by row
	for (i = 0; i < piecerows; i++)
	{
		for (j = 0; j < piececols; j++)
		{
			// get current position
			var xcurr = $("#piece" + i + j).position().left;
			var ycurr = $("#piece" + i + j).position().top;
			
			// get desired position
			var ywin = i * piecesize;
			var xwin = j * piecesize;
			
			// compare and score
			if ((Math.abs(xcurr - xwin) <= 10) && (Math.abs(ycurr - ywin) <= 10))
				wincounter--;
		}
	}
	
	// debug
	// alert("Incorrect Pieces Remaining: " + wincounter);

	// you win! maybe.
	if (wincounter == 0)
	{
		// freeze pieces
		$(".piece").draggable({disabled: true});

		// freeze solution checking
		$(".piece").unbind("mouseup");
		
		// alert user
		youwin();
	}

}


// **** winning message
function youwin()
{
	floatmsg("You Did it!","startover()");
}

// **** restart game
function startover()
{
	// remove all puzzle pieces	
	$("div").remove(".piece");
	
	// rebuild pieces and reshuffle
	startpuzzle();
}










