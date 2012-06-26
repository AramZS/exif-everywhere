<html>
<head>
	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
	<script src="js/jquery.cycle.all.js" type="text/javascript"></script>
	<script src="js/cycle-imp.js" type="text/javascript"></script>
	<script src="js/lightbox.js" type="text/javascript"></script>
	<link rel="stylesheet" href="css/exif-everywhere-style.css" type="text/css">
	<link href="css/lightbox.css" rel="stylesheet" />
</head>
<body>
	<div class="cycle-control">
		<div class="cycle-title">
			<h2>Title</h2>
		</div>
		<div class="cycle-controls">
			EXIF Play Stop Full Screen
		</div>
	</div>
       <div class="cycleContainer">
			
			<?php
			
				//Using code from http://net.tutsplus.com/tutorials/php/quick-tip-loop-through-folders-with-phps-glob/ to build a quick demo
			
				$dir = "testimgs/*";
				
					foreach(glob($dir) as $file) {
					
						echo '<div class="slide">';
							echo '<a href="'. $file . '" rel="lightbox"><img src="' . $file . '" /></a>';
							echo '<div class="caption">
									Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean nec sapien sit amet diam pretium adipiscing. Phasellus euismod mi tincidunt elit mattis sit amet tincidunt est varius. Nunc pretium augue at nibh lacinia sit amet feugiat massa interdum. 
								</div>
								<div class="photog">Photog Name</div>
							</div><!-- end slide -->';
					
					}
				
			?>
        
        </div><!-- end of #cycleContainer -->

</body>
</html>