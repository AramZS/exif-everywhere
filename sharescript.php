<?php


	Header("content-type: application/x-javascript");
	
	$filelocation = $_SERVER['SCRIPT_FILENAME'];
	$fulllocation = substr_replace($filelocation, '', -15);
	
	$baselocation = substr_replace($fulllocation, '', -25);
	$sitelocation = substr_replace($baselocation, '', -11);
	$wpneeded = $sitelocation . '/wp-blog-header.php';
//	print_r($wpneeded);
//	die();
	define('WP_USE_THEMES', false);
	require($wpneeded);
	
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
			'id' => $post->ID,
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
		<?php if ($theStyle == 0){
		?><link rel="stylesheet" type="text/css" href="<?php echo $fulllocation . 'css/exif-everywhere-style.css'; ?>">
		<?php } 
		if ($theStyle == 1){
		?><link rel="stylesheet" type="text/css" href="<?php echo $stylesheetlocation . 'user-exif-slider-style.css'; ?>">
		<?php } ?>
		
		
		<link rel="stylesheet" type="text/css" href="<?php echo $fulllocation . 'css/lightbox.css'; ?>">
		<script src="<?php echo $fulllocation . 'js/jquery.cycle.all.js'; ?>"></script>
		<script src="<?php echo $fulllocation . 'js/jquery.tools.min.js'; ?>"></script>
		<script src="<?php echo $fulllocation . 'js/lightbox.js'; ?>"></script>
		<script src="<?php echo $fulllocation . 'js/cycle-imp.js'; ?>"></script>
	<?php
	//echo socialjs();
	die();
	echo exif_gallery_shortcode($thePassedArgs);

?>