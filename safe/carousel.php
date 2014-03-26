<?
// user infos and database
require_once("safe/sqlcommon.php");

// get all of this user's pics into object
// prepare query
$q = "SELECT * FROM pics WHERE owner='";
$q .= $id . "' ";
$q .= "ORDER BY time DESC;";

// submit query
$result = mysql_query($q);

// count user's pics to build carousel
$pics = mysql_num_rows($result);

// thumbnails are 200px plus 20px padding plus 10px margin right
$length = $pics * (200 + 30);
?>

<link type="text/css" href="css/carousel.css" rel="stylesheet" />

<script type="text/javascript">
	// server tells us how many pics there are
	var numberpics = <?= $pics ?>;
</script>

<script type="text/javascript" src="js/carousel.js"></script>


<div id="carouselc">
	<div id="carousel" style="width:<?= $length ?>px;">
		<? // populate carousel with user's photos
		while ($row = mysql_fetch_array($result)): ?>
		<div id="<?= $row["name"] ?>" class="carouselpic"><img src="<?= $row["thumb"] ?>"></div>
		<? endwhile ?>
	</div>

</div>