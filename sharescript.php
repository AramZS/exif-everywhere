<?php


//	Header("content-type: application/x-javascript");
//print_r($_SERVER); die();	
	$filerelq = $_SERVER['REQUEST_URI'];
	$querystring = '?' . $_SERVER['QUERY_STRING'];
	$filerel = str_replace($querystring, '', $filerelq);
	$serverlocation = $_SERVER['HTTP_HOST'];
	$filelocation = 'http://' . $serverlocation . $filerel;
	$fulllocation = substr_replace($filelocation, '', -15);
	
	$baselocation = substr_replace($fulllocation, '', -25);
	$sitelocation = substr_replace($baselocation, '', -11);
	
	
	$relfilelocation = $_SERVER['SCRIPT_FILENAME'];
	$relfulllocation = substr_replace($relfilelocation, '', -15);
	
	$relbaselocation = substr_replace($relfulllocation, '', -25);
	$relsitelocation = substr_replace($relbaselocation, '', -11);

	
	$wpneeded = $relsitelocation . '/wp-blog-header.php';
//	print_r($wpneeded);
//	die();
	define('WP_USE_THEMES', false);
	require($wpneeded);
//	require('/home1/chronoto/public_html/tandv/wp-blog-header.php');
	
// Post vs Get? 	http://php.net/manual/en/reserved.variables.get.php http://www.w3schools.com/PHP/php_post.asp
	
	$theGet = htmlspecialchars($_GET["get"]);
	$theInclude = htmlspecialchars($_GET["include"]);
	$theExclude = htmlspecialchars($_GET["exclude"]);
	$theExif = htmlspecialchars($_GET["exif"]);
	$theID = htmlspecialchars($_GET["id"]);
	$theAutoplay = htmlspecialchars($_GET["autoplay"]);
	$theTitle = htmlspecialchars($_GET["title"]);
	$theFontsize = htmlspecialchars($_GET["fontsize"]);
	$theWidth = htmlspecialchars($_GET["width"]);
	$theHeight = htmlspecialchars($_GET["height"]);
	$theShare = htmlspecialchars($_GET["share"]);
	$theEmbed = htmlspecialchars($_GET["embed"]);
	$theOrder = htmlspecialchars($_GET["order"]);
	$theOrderby = htmlspecialchars($_GET["orderby"]);
	$theSize = htmlspecialchars($_GET["size"]);
	
	$theStyle = htmlspecialchars($_GET["css"]);
	$theTheme = htmlspecialchars($_GET["theme"]);


	$stylesheetlocation = $baselocation . '/themes/' . $theTheme . '/';
	
	$thePassedArgs = array(
	
			'get' => $theGet,
			'include' => $theInclude,
			'exclude' => $theExclude,
			'exif' => $theExif,
			'id' => $theID,
			'autoplay' => $theAutoplay,
			'title' => $theTitle,
			'fontsize' => $theFontsize,
//			'itemtag'    => 'dl',
//			'icontag'    => 'dt',
//			'captiontag' => 'dd',
			'width' => $theWidth,
			'height' => $theHeight,
			'share' => $theShare,
			'embed' => $theEmbed,
			'order' => $theOrder,
			'orderby' => $theOrderby,
			'size' => $theSize
		);
		
	$testArray = array(
			'get' => '',
			'include' => '',
			'exclude' => '',
			'exif' => 'yes',
			'id' => 139,
			'autoplay' => 'yes',
			'title' => 'Gallery',
			'fontsize' => '1em',
//			'itemtag'    => 'dl',
//			'icontag'    => 'dt',
//			'captiontag' => 'dd',
			'width' => 1024,
			'height' => 400,
			'share' => 'on',
			'embed' => 'on',
			'order' => 'ASC',
			'orderby' => 'menu_order ID',
			'size' => 'large'
		);
	
	?>
	<head>
		<?php if ($theStyle == 0){
		?><link rel="stylesheet" type="text/css" href="<?php echo $fulllocation . 'css/exif-everywhere-style.css'; ?>">
		<?php } 
		if ($theStyle == 1){
		?><link rel="stylesheet" type="text/css" href="<?php echo $stylesheetlocation . 'user-exif-slider-style.css'; ?>">
		<?php } ?>
		
		
		<link rel="stylesheet" type="text/css" href="<?php echo $fulllocation . 'css/lightbox.css'; ?>">
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript" src="<?php echo $fulllocation . 'js/jquery.cycle.all.js'; ?>"></script>
		<script type="text/javascript" src="<?php echo $fulllocation . 'js/jquery.tools.min.js'; ?>"></script>
		<script type="text/javascript" src="<?php echo $fulllocation . 'js/lightbox.js'; ?>"></script>
		<script type="text/javascript" src="<?php echo $fulllocation . 'js/cycle-imp.js'; ?>"></script>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				jQuery(function() {
					jQuery('.shareButton').remove();
					jQuery('.embederBox').remove();
				});
			});
		</script>
	</head>
	<body>
	<?php
	//echo socialjs();

	echo exif_gallery_shortcode($thePassedArgs);

	?>
	<div class="embedLink"><h4><?php echo '<a href="' . $sitelocation . '">' ?>Get this slideshow.</a></h4></div>
	</body>