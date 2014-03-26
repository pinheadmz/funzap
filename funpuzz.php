<!DOCTYPE html>
<html>
<!-- 
***************************************
*  designed by MATTHEW ZIPKIN 2012    *
* matthew(dot)zipkin(at)gmail(dot)com *
***************************************
-->
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Fun Puzz!</title>
	
	<!-- jQuery and jQuery UI for draggables -->
	<script type="text/javascript" src="include/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="include/jquery-ui-1.8.21.custom.min.js"></script>
	
	<!-- for mobile touch application: http://touchpunch.furf.com/ -->
	<script type="text/javascript" src="include/jquery.ui.touch-punch.min.js"></script>

	<!-- my code -->
	<link type="text/css" href="css/funpuzz.css" rel="stylesheet" />
	<script type="text/javascript" src="js/funpuzz.js"></script>

	<!-- options panel, top above puzzle -->
	<div id="options">
	<div id="optionscover"></div>
		<form id="puzzleoptions">
			<input type="radio" name="piecenumber" id="piecenumber48" value="48"><label for="piecenumber48">48 Pieces</label>
			<input type="radio" name="piecenumber" id="piecenumber12" value="12" checked="checked"><label for="piecenumber12">12 Pieces</label>
		</form>
		<form id="uploadpic" action="uploadpic.php" method="post" enctype="multipart/form-data" style="display:inline" target="response">
			<label for="file">Choose pic:</label>
			<input type="file" name="pic" id="pic">
		</form>
			<input type="button" onclick="uploadpic();"  value="upload!">

			
	</div>
	
	

	<!-- main puzzle container, centered -->
	<div id="puzzlec"></div>
	
	<!-- secret iframe to hide asynchronous server responses -->
	<iframe id="response" name="response" src="" style="display:none"></iframe>

</html>