<html>
<head>
	<link rel="stylesheet" href="css/exif-everywhere-style.css" type="text/css">
<style type="text/css">

.scrollable {
  /* required settings */
  position:relative;
  overflow:hidden;
  width: 200px;
  height:58px;
}

.scrollable .items {
  /* this cannot be too large */
  position:absolute;
}
 
/*
a single item. must be floated in horizontal scrolling.  typically,
this element is the one that *you* will style the most.
*/
.item {
  float:left;
}

</style>
	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>

	
		<script type="text/javascript">
$(function() {
  // initialize scrollable
  $(".scrollable").scrollable({circular: true, mousewheel: true}).autoscroll({
	interval: 7000
});
});
	</script>

</head>
<body>
<ul id="cycle-nav" class="cyclenav scrollable">
<div class="items">
<li class="item"><a href="#"><img src="testimgs/379050721_458650774b_b.jpg" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/Aquaduct_by_Phifty.jpg" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/Capture.PNG" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/IMG_20120223_163731.jpg" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/IMG_20120417_092832.jpg" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/Radial_Crosshair.jpg" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/Screen shot 2011-02-17 at 3.54.55 PM.png" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/bbnotworking.JPG" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/c2merror.PNG" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/callingwordpress.jpg" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/desktopscreen.png" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/final super tuesday.jpg" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/infographiclarge_v2.png" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/joystiq-sg.PNG" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/masonvotes3-1--1.jpg" width="50" height="50"></a></li><li class="item"><a href="#"><img src="testimgs/reasonrally.jpg" width="50" height="50"></a></li>
</div>
</ul>
	

</html>