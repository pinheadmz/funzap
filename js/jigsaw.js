/*
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************
*/

// **** globals
var bgsize = photox + "px " + photoy + "px";
var msgcounter = 0;
var piecerows = 0;
var piececols = 0;
var piecesizex = 0;
var piecesizey = 0;


// **** on load, load variables from PHP into css
$(document).ready(function(){
	$("#puzzlec").css("background-image", pic);
	$("#puzzlec").css("width", photox);
	$("#puzzlec").css("height", photoy);
	$("#prizec").css("width", photox);
	$("#prizec").css("height", photoy);
	floatmsg("Click here to start!","startpuzzle()");


	// decode secret number
	var decodedprize = hex2a(prizemsg);
	
	// get prize ready -- depends on prize type
	switch (prize)
	{
	case "pzpic":
		$("#prizec").html("Your friend wanted you to have this photo as a prize for finishing this <h2>FunZap!</h2> puzzle...<br><div id='prizecimg'></div>");
		$("#prizecimg").css("background-image", "url('" + decodedprize + "')");
		break;
	case "pzurl":
		$("#prizec").html("Your friend wanted you to have this link as a prize for finishing this <h2>FunZap!</h2> puzzle...<br><br><br><a href='" + decodedprize + "'>[LINK!]</a>");
		break;
	case "pzmsg":
		$("#prizec").html("Your friend wanted you to have this message as a prize for finishing this <h2>FunZap!</h2> puzzle...<br><br><br>" + decodedprize);
		break;	
	}
});

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

// **** start the game
function startpuzzle()
{
	// hide all floating messages, remove background image of complete photo and hide #puzzlec & options menu while we change the scenery
	$(".floatmsg").animate({opacity: "0"},500,"linear",
		function(){$("#puzzlec").animate({opacity: "0"},500,"linear",
			function(){$(".floatmsg").css("display","none");
				$("#puzzlec").css("background-image","none");
				slicepuzzle();
			});
		});
}

// **** create puzzle piece divs row by row with correct portion of photo
function slicepuzzle()
{
	// calculate number of pieces in each row and column based on total number of pieces
	// find middle factors of piecenum so rows and columns are even
	var piecegrid = midfactors(piecenumber);
	
	// bigger number of grid pair goes for bigger photo dimension
	piececols = ((photox > photoy) ? piecegrid["big"] : piecegrid["small"]);
	piecerows = ((photox <= photoy) ? piecegrid["big"] : piecegrid["small"]);
	
	// calculate dimensions of each piece based on total photo size and number of pieces in grid
	piecesizex = photox / piececols;
	piecesizey = photoy / piecerows;
	
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
			var xpos = (-1 * piecesizex) * j;
			var ypos = (-1 * piecesizey) * i;
			var bgpos = xpos + "px " + ypos + "px";
			$("#piece" + i + j).css("background-position", bgpos);
			
			// here's that amazing jQuery magic for dragging DIV's and snapping to grid
			$("#piece" + i + j).draggable({containment: "#puzzlec"});
			$("#piece" + i + j).draggable({snap: "#puzzlec"});
			$("#piece" + i + j).draggable({snap: ".piece"});		
		}
	}
	
	// set the width and height for all pieces in the css class, including 1px border on each edge
	$(".piece").css("width", piecesizex-2);
	$(".piece").css("height", piecesizey-2);
	
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
			}
			while (xnew > (photox - piecesizex) || xnew < 0 || ynew < 0 || ynew > (photoy - piecesizey));

		
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
			var ywin = i * piecesizey;
			var xwin = j * piecesizex;
			
			// compare and score
			if ((Math.abs(xcurr - xwin) <= 20) && (Math.abs(ycurr - ywin) <= 20))
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
	floatmsg("You Did It!<br>Click here to claim your prize!","pzclaim()");
}

// **** restart game
function startover()
{
	// remove all puzzle pieces	
	$("div").remove(".piece");
	
	// rebuild pieces and reshuffle
	startpuzzle();
}


// **** pick the two middle factors of a number
// help from http://www.codingforums.com/showthread.php?t=220940
function midfactors(n)
{
	while (true)
	{
		// initialize
		var p1, p2;
		
		// start at the bottom, and count up towards number looking for factors
		for (var f1 = 1; f1 < n; f1++)
		{
			// try dividing current iteration number
			var f2 = n / f1;
			
			// check for no remainder, then its a factor
			if (f2 == Math.floor(f2))
			{
				// one pair of factors is found
				p1 = f1;
				p2 = f2;
			}
			
			// check if its the middle pair
			if (f2 <= f1)
				break;			
		}
	
		// return last pair of factors
		var result = new Array();
		result["small"] = ((p2 > p1) ? p1 : p2);
		result["big"] = ((p2 >= p1) ? p2 : p1);
		return result;
	}
}

// **** claim your prize
function pzclaim()
{
	// reset hidden div stuff
	$("#prizec").css("display", "none");
	$("#prizec").css("visibility", "visible");


	// hide floating messages and puzzle, replace with prize
	$(".floatmsg").css("display","none");
	$("#puzzlec").slideUp('slow');
	$("#prizec").slideDown('slow');
}

// **** decode hexxxxed message
// http://stackoverflow.com/questions/3745666/how-to-convert-from-hex-to-ascii-in-javascript
function hex2a(hex)
{
    var str = '';
    for (var i = 0; i < hex.length; i += 2)
        str += String.fromCharCode(parseInt(hex.substr(i, 2), 16));
    return str;
}





