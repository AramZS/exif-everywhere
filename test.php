<html>
<head>
	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script src="js/lightbox.js" type="text/javascript"></script>
	<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
		<script src="js/jquery.cycle.all.js" type="text/javascript"></script>
	<link rel="stylesheet" href="css/exif-everywhere-style.css" type="text/css">
	<link href="css/lightbox.css" rel="stylesheet" />
		<script src="js/cycle-imp.js" type="text/javascript"></script>
</head>
<body>
<div class="overall-cycle-container">
	<div class="overall-cycle-contained">		
		<div class="cycle-control">
			<div class="cycle-title">
				<h2>Title</h2>
			</div>
			<div class="cycle-controls">
				<a id="exifButton" href="#">EXIF</a> <a id="resumeButton" onClick="api.play()" href="#">Play</a> <a id="pauseButton" onClick="api.pause()" href="#">Stop</a> <a id="fullButton" href="#">Full Screen</a>
			</div>
		</div>
		
	<!--<div id="cycle-prev" class="sliderPrev"><a class="prev" href="#"><span class="arrow-w prev"></span></a></div><div id="cycle-next" class="sliderNext"><a class="next" href="#"><span class="arrow-e next"></span></a></div> -->	
		   <div class="cycleContainer">
				
				<?php
				
					
				
					//Using code from http://net.tutsplus.com/tutorials/php/quick-tip-loop-through-folders-with-phps-glob/ to build a quick demo
					
					//http://localhost/xampp/wp-test/wp-content/plugins/exif-everywhere/test.php
				
					$dir = "testimgs/*";
					
						foreach(glob($dir) as $file) {
						
							echo '<div class="slide">';
								echo '<a href="'. $file . '" rel="lightbox"><img src="' . $file . '" /></a>';
								echo '<div class="exif-data disappear">Some EXIF info</div>';
								echo '<div class="caption">
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean nec sapien sit amet diam pretium adipiscing. Phasellus euismod mi tincidunt elit mattis sit amet tincidunt est varius. Nunc pretium augue at nibh lacinia sit amet feugiat massa interdum. 
									</div>
									<div class="photog">Photog Name</div>
								</div><!-- end slide -->';
						
						}
					
				?>
			
			</div><!-- end of #cycleContainer -->

		</div><!-- end of #overall-cycle-contained -->
	</div><!-- end of #overall-cycle-container -->
				<?php echo '<div class="embederBox disappear> 
							<form action="">
							<textarea name="embedField" cols="20" rows="20" type="text"  disabled="disabled">
							&#60;iframe src&#61;&#34;'
							 . 'site' . '/wp-content/plugins/exif-everywhere/sharescript.php?' . '&#34; width&#61;&#34;100%&#34; scrolling&#61;&#34;no&#34; frameborder&#61;&#34;0&#34;&#62; </textarea>
							 </form>
						</div>'; ?>
</body>
</html>