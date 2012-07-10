<?php
/*
Plugin Name: EXIF Everywhere
Plugin URI: http://aramzs.me/exifeverywhere
Description: This plugin allows you to place a responsive and exif aware slideshow in your WordPress site. 
Version: 0.1
Author: Aram Zucker-Scharff
Author URI: http://aramzs.me
License: GPL2
*/

/*  Copyright 2012  Aram Zucker-Scharff  (email : azuckers@gmu.edu)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
	Based on plugin Display Exif - http://wordpress.org/extend/plugins/display-exif/
	Built for the CUNY Graduate Journalism School
*/


//Adding photo credit option to the upload image interface.
//We will call this later using something like:
/*

					if (has_post_thumbnail() ) {
					
						echo '<div class="single-post-thumb">';
					
							$post_thumbnail_id = get_post_thumbnail_id( $post_id );
						
							the_post_thumbnail( 'main-thumb' );
							
							echo '<div class="thumb-caption">';
								
								echo get_the_excerpt_here($post_thumbnail_id);
							
							echo '</div>';
							
							echo '<div class="single-post-thumb-credit">';
							
								echo 'Photo by: ';
								echo get_post_meta($post_thumbnail_id, '_photocredit', true);
								
							echo '</div>';
					
						echo '</div>';
					}

*/
function add_photo_credit_option( $form_fields, $post ) {  
	
	$form_fields['photocredit'] = array(  
		'label' => '<span style="color:#ff0000; margin:0; padding:0;">'.__('Photo Credit', 'display_exif').'</span> <br />'.__('Add Photographer Name / Photo Type to image', 'display_exif'),
		'input' => 'text',
		'value' => get_post_meta($post->ID, '_photocredit', true)  
	);
	  
	return $form_fields;
}  

add_filter('attachment_fields_to_edit', 'add_photo_credit_option', null, 2);

// save custom option for images in media library

function save_photo_credit_option($post, $attachment) {
	if( isset($attachment['photocredit']) ){
		update_post_meta($post['ID'], '_photocredit', $attachment['photocredit']);  
	} 
		
	return $post;  
}

add_filter('attachment_fields_to_save', 'save_photo_credit_option', 10, 2);

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
}
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'slider-thumb', 1024, 600, true ); //(hard cropped)}
}

function style_exif_slider_plugin () {

	//Want to replace the default styling applied to tweets? Just add this CSS file to your stylesheet's directory.
	$theme_style_file = get_stylesheet_directory() . '/user-exif-slider-style.css';

	//If you've added a user override CSS, then it will be used instead of the styling I have in the plugin.
	if ( file_exists($theme_style_file) ) {
		wp_register_style( 'user-exif-slider-style', $theme_style_file );
		wp_enqueue_style( 'user-exif-slider-style' );
	}
	//If not, you get the default styling. 
	else {
		wp_register_style( 'exif-slider-style', plugins_url('css/exif-everywhere-style.css', __FILE__) );
		wp_enqueue_style( 'exif-slider-style' );
	}
	
	wp_register_style( 'lightbox', plugins_url('css/lightbox.css', __FILE__) );
	wp_enqueue_style( 'lightbox' );

}

add_action( 'wp_enqueue_scripts', 'style_exif_slider_plugin', 1 );

		$wpver = get_bloginfo('version');
		$floatWPVer = floatval($wpver);

	//echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>';
	//Ref:WordPress Bible pg 90
	
	if ($floatWPVer >= 3.4){

		function jq_threefour_setup() {
				
				wp_enqueue_script('jquery');
				wp_enqueue_script('jquery-cycle', plugins_url('js/jquery.cycle.all.js', __FILE__), array('jquery'));
				wp_enqueue_script('jquery-tools', plugins_url('js/jquery.tools.min.js', __FILE__), array('jquery'));
				wp_enqueue_script('jquery-lightbox', plugins_url('js/lightbox.js', __FILE__), array('jquery'));
				wp_enqueue_script('jquery-cycle-imp', plugins_url('js/cycle-imp.js', __FILE__), array('jquery-cycle', 'jquery-tools'));

		}

		add_action('wp_enqueue_scripts', 'jq_threefour_setup');
	} else {
			
		function jq_enqueue() {
		
						wp_dequeue_script( 'jquery' );
						wp_deregister_script( 'jquery' );
						wp_register_script('jquery', 'http://code.jquery.com/jquery-latest.min.js', '', '1.7.2');
						wp_enqueue_script('jquery');
		
		}
		add_action('wp_enqueue_scripts', 'jq_enqueue');	
			
		function jq_setup() {	
			?>
				<!--Needs latest version of jQuery, some sites don't have it
					
				<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>-->
				<script src="<?php echo plugins_url('js/jquery.cycle.all.js', __FILE__); ?>"></script>
				<script src="<?php echo plugins_url('js/jquery.tools.min.js', __FILE__); ?>"></script>
				<script src="<?php echo plugins_url('js/lightbox.js', __FILE__); ?>"></script>
				<script src="<?php echo plugins_url('js/cycle-imp.js', __FILE__); ?>"></script>
				
					
			<?php
		}
			
		add_action('wp_head', 'jq_setup');
	}
	
function socialjs() {

	?>
		<script type="text/javascript">
		  (function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script>
	<?php

}
add_action('wp_footer', 'socialjs');

/* This can be used later to create a taxonomy for the exif properties.
function photo_taxonomy_init() {
	// create a new taxonomy
	register_taxonomy(
		'make',
		'post',
		array(
			'label' => __( 'Make' ),
			'rewrite' => array( 'slug' => 'exif-make' )
		)
	);
}
add_action( 'init', 'photo_taxonomy_init' );
*/

function display_exif_get_default_option_value() {
	$display_exif_switches_default_value = array(
				'Make' => array( 1, 'Manufacturer' ),
				'Model'  => array( 1, 'Model Name' ),
				'DateTimeOriginal' => array( 0, 'Date' ),
				'ExposureProgram' => array( 1, 'Exposure Program' ),
				'ExposureTime' => array( 1, 'Exposure Time' ),
				'FNumber' => array( 1, 'F Number' ),
				'ISOSpeedRatings' => array( 1, 'ISO' ),
				'FocalLength' => array( 1, 'Focal Length' ),
				'MeteringMode' => array( 1, 'Metering Mode' ),
				'LightSource' => array( 0, 'Light Source' ),
				'SensingMethod' => array( 0, 'Sensing Method' ),
				'ExposureMode' => array( 0, 'Exposure Mode' ),

				'FileName' => array( 0, 'File Name' ),
				'FileSize' => array( 0, 'File Size' ),
				'Software' => array( 0, 'Software' ),
				'XResolution' => array( 0, 'X Resolution' ),
				'YResolution' => array( 0, 'Y Resolution' ),

				'ExifVersion' => array( 0, 'Exif Version' ),

				'title' => array( 1, 'Title' )
	);
	return( $display_exif_switches_default_value );
} /* display_exif_get_default_option_value() */

$global_exif_datas;

function display_exif_js( $arg ) {
	$js_source =  '
	<!-- JavaScript for Display Exif -->
	<script type="text/javascript">
//<![CDATA[
	function _ie8_anti_NaN( number_to_check ) {
		var r = 0;
		if( isFinite( number_to_check ) ) r = number_to_check;
		return( r );
	} /* _ie8_anti_NaN */

	jQuery( function(){

		var $each_imgs = jQuery( \'img\' );

		$each_imgs.bind( \'mouseenter\', function() {
			$this_img = jQuery( this );
			if( $this_img.attr( \'displayexif\' ) && $this_img.attr( \'displayexif\' ).length > 0 ) {
				var $dispexif_id = $this_img.attr( \'displayexif\' );
				$this_dispexif = jQuery( $dispexif_id );

				var $img_pos = $this_img.position();

				var $oft_w = ( _ie8_anti_NaN( parseInt( $this_img.css( "marginRight" ) ) ) - _ie8_anti_NaN( parseInt( $this_img.css( "marginLeft" ) ) ) ) / 2;
				var $oft_h = ( _ie8_anti_NaN( parseInt( $this_img.css( "marginBottom" ) ) ) - _ie8_anti_NaN( parseInt( $this_img.css( "marginTop" ) ) ) ) / 2;

				$t_pos = $img_pos.top + ( $this_img.outerHeight( true ) - $this_img.height() ) / 2 - $oft_h;
				$l_pos = $img_pos.left + ( $this_img.outerWidth( true ) - $this_img.width() ) / 2 - $oft_w;
				$e_width = $this_img.width();

				$this_dispexif.css({
					position: "absolute",
					top: $t_pos,
					left: $l_pos,
					width: $e_width - 10
				});

				$most_top = $this_img.parent();
				if( !$most_top.css( "position" ) ) { $most_top.css( { position: "relative" } ); }

				$this_dispexif.show();

				/* suppress flicker when mouse on the box */
				$this_dispexif.bind( \'mouseover\', function() {
					$this_dispexif.show();
				}).bind( \'mouseleave\', function() {
					$this_dispexif.hide();
				});
			}
		}).bind( \'mouseleave\', function() {
				$this_dispexif.hide();
				jQuery( \'.dispexif_box\' ).remove();
		});

	});
//]]>
	</script><!-- end Javascript for Display Exif -->
	<style type="text/css" >
.dispexif_hidden {
	background-color: black;
	filter: alpha(opacity=60); /* for IE */
	-moz-opacity: .60; /* for non CSS3 support firefox*/
	opacity: .60; /* safari and others */
	padding: 5px;
	}
.dispexif_raw {
	margin: 0px;
	padding: 0px;
	position: relative;
	font-family: helvetica;
	font-size: 10px;
	line-height: 1.2em;
	color: white;
	clear: both;
	}
.dispexif_title {
	float: left;
	width: 100px;
	}
.dispexif_desc {
	}
	</style>
	';
	echo $js_source;
} /* display_exif_js() */

function display_exif_single_replace_cb( $filename ) {
	global $global_exif_datas;

	$options = get_option( 'display_exif' );
	if( !$options ) $options[ 'display_exif_switches' ] = display_exif_get_default_option_value();

	
/*	if( preg_match( '/src="(.+?)"/s', $attrs_org, $f ) && !preg_match( '/^"/s', $f[ 1 ] ) ) {
		$filename = $f[ 1 ];

		$titletext = '';
		if( preg_match( '/title="(.+?)"/s', $attrs_org, $t ) && !preg_match( '/^"/s', $t[ 1 ] ) ) $titletext = $t[ 1 ];
*/
		if( $options[ 'display_exif_thumbnail_support' ] ) $ex_filename = preg_replace( '/-[0-9]{1,3}x[0-9]{1,3}./s', '.', $filename );
		else $ex_filename = $filename;
		$exif = @exif_read_data( $ex_filename, 'EXIF' );		// IFD0

		if( !empty( $exif ) ) {
			$exif_tag = $options[ 'display_exif_switches' ];

			$exif_array = array();

//			$exif_array[ 'selfkey' ] = $dp_str;

			foreach( $exif_tag as $key => $value ) {
				if( $exif[ $key ] && $value[ 0 ] ) {
					switch( $key ) {
						case 'FocalLength':
							$tmparray = explode( '/', $exif[ $key ] );
							if( count( $tmparray ) > 1 )	$exif_array[ $value[ 1 ] ] = ( $tmparray[0] / $tmparray[1] ) . 'mm';
							else							$exif_array[ $value[ 1 ] ] = ( $tmparray[0] ) . 'mm';
							break;
						case 'FNumber':
							$tmparray = explode( '/', $exif[ $key ] );
							if( $tmparray[ 0 ] < 3000 && $tmparray[ 0 ] > 0  )	$exif_array[ $value[ 1 ] ] = ( $tmparray[0] / $tmparray[1] );
							break;
						case 'ExposureProgram':
							$exposure_program_data = array(
								1 => 'Manual',
								2 => 'Normal Program',
								3 => 'Aperture Priority',
								4 => 'Shutter Priority',
								5 => 'Creative Program',
								6 => 'Action Program',
								7 => 'Portrait Mode',
								8 => 'Landscape Mode' );
							$exif_array[ $value[ 1 ] ] = $exposure_program_data[ intval( $exif[ $key ] ) ];
							break;
						case 'MeteringMode':
							$metering_mode_data = array(
								0 => 'Unknown',
								1 => 'Average',
								2 => 'Center weighted average',
								3 => 'Spot',
								4 => 'Multi Spot',
								5 => 'Pattern',
								6 => 'Partial',
								255 => 'Other' );
							$exif_array[ $value[ 1 ] ] = $metering_mode_data[ intval( $exif[ $key ] ) ];
							break;
						case 'LightSource':
							$light_source_data = array(
								0 => 'Unknown',
								1 => 'Daylight',
								2 => 'Fluorescent',
								3 => 'Tungsten',
								4 => 'Flash',
								9 => 'Fine weather',
								10 => 'Cloudy weather',
								11 => 'Shade',
								12 => 'Daylight fluorescent', 13 => 'Day white fluorescent', 14 => 'Cool white fluorescent', 15 => 'White fluorescent',
								17 => 'Standard light A', 18 => 'Standard light B', 19 => 'Standard light C',
								20 => 'D55', 21 => 'D65', 22 => 'D75', 23 =>'D50', 
								24 => 'ISO studio tungsten',
								255 => 'Other light source' );
							$exif_array[ $value[ 1 ] ] = $light_source_data[ intval( $exif[ $key ] ) ];
							break;
						case 'SensingMethod':
							$sensing_method_data = array(
								2 => 'One-chip color area sensor',
								3 => 'Two-chip color area sensor',
								4 => 'Three-chip color area sensor',
								5 => 'Color sequential area sensor',
								7 => 'Trilinear sensor',
								8 => 'Color sequential linear sensor' );
							$exif_array[ $value[ 1 ] ] = $sensing_method_data[ intval( $exif[ $key ] ) ];
							break;
						case 'ExposureMode':
							$exposure_mode_data = array(
								0 => 'Auto',
								1 => 'Manual',
								2 => 'Auto bracket' );
							$exif_array[ $value[ 1 ] ] = $exposure_mode_data[ intval( $exif[ $key ] ) ];
							break;
						case 'ExifVersion':
							$exif_array[ $value[ 1 ] ] = floatval( $exif[ $key ] ) / 100 . '';
							break;
						default:
							$exif_array[ $value[ 1 ] ] = $exif[ $key ];
							break;
					} /* switch */

				} /* if */
			} /* foreach */

			if( $titletext && $exif_tag[ 'title' ][ 0 ] ) $exif_array[ $exif_tag[ 'title' ][ 1 ] ] = $titletext;

//			$global_exif_datas[ $dp_str ] = $exif_array;

//		foreach( $global_exif_datas as $ged_key => $global_exif_data ) {
			$data_cells = '';

			foreach( $exif_array as $k => $v ) {
				if( empty( $v ) ) continue;
				$data_cells .= '<div class="dispexif_raw" >';
				$data_cells .= '<div class="dispexif_title" >' . $k . '</div>';
				$data_cells .= '<div class="dispexif_desc" >' . $v . '</div>';
				$data_cells .= '</div>';
				
			} /* foreach */
//		}

//		} /* if */
	} /* if */

	return( $data_cells );
} /* display_exif_replace_cb() */


function display_exif_filter( $arg ) {
	$output = $arg;

	global $global_exif_datas;

	if( !empty( $global_exif_datas ) ) $global_exif_datas = '';	// init

	$output = preg_replace_callback( '/<img(.+?)\/>/s', 'display_exif_replace_cb', $output );

	if( !empty( $global_exif_datas ) && count( $global_exif_datas ) > 0 ) {
		foreach( $global_exif_datas as $ged_key => $global_exif_data ) {
			$data_cells = '';

			foreach( $global_exif_data as $k => $v ) {
				if( empty( $v ) ) continue;
				$data_cells .= '<div class="dispexif_raw" >';
				$data_cells .= '<div class="dispexif_title" >' . $k . '</div>';
				$data_cells .= '<div class="dispexif_desc" >' . $v . '</div>';
				$data_cells .= '</div>';
			} /* foreach */

			if( !empty( $data_cells ) ) $output .= '<div class="dispexif_hidden ' . $ged_key . '" style="display: none;" >' . $data_cells . '</div>';
		} /* foreach */

	} /* if */

	return( $output );
} /* display_exif_filter() */


function display_exif_add_pages() {
	add_options_page('Display Exif', 'Display Exif', 8, 'display_exif_options', 'display_exif_options_page');
} /* display_exif_add_pages() */

function display_exif_options_page() {
	$output = '';

	$display_exif_switches_default_value = display_exif_get_default_option_value();

	$options = $newoptions = get_option('display_exif');
	if ( $_POST["display_exif_submit"] ) {
		$dummyoptions = "";
		update_option('display_exif', $dummyoptions );		// reset once

		$display_exif_switches_values = array();
		foreach( $display_exif_switches_default_value as $key => $value ) {
			$display_exif_switches_values[ $key ] =  array( $_POST[ $key ] , $display_exif_switches_default_value[ $key ][ 1 ] );
		} /* foreach */


		$newoptions['display_exif_use_mouseover'] = (boolean)$_POST["display_exif_use_mouseover"];;
		$newoptions['display_exif_valid_check'] = (boolean)$_POST["display_exif_valid_check"];;
		$newoptions['display_exif_switches'] = $display_exif_switches_values;
		$newoptions['display_exif_master_switch'] = (boolean)$_POST["display_exif_master_switch"];

		$newoptions['display_exif_thumbnail_support'] = (boolean)$_POST["display_exif_thumbnail_support"];
	} /* if */
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('display_exif', $options);
	} /* if */

	$display_exif_master_switch = $options['display_exif_master_switch'];
	$display_exif_switches = $options['display_exif_switches'];
	$display_exif_valid_check = $options[ 'display_exif_valid_check' ];
	$display_exif_use_mouseover = $options[ 'display_exif_use_mouseover' ];
	$display_exif_thumbnail_support = $options[ 'display_exif_thumbnail_support' ];

	if( !$display_exif_switches ) {		// set default value
		$display_exif_switches = $display_exif_switches_default_value;
	} /* if */


	$output .= '<h2>Display Exif Settings</h2>';
	$output .= '<form action="" method="post" id="display_exif_form" style="margin: auto; width: 600px; ">';

	$output .= '<hr size="1" />';

	$output .= '<span style="font-weight: bold;" >&bull; Items to display:</span><br />';

	foreach( $display_exif_switches as $key => $value ) {
		$op_value = $value[ 0 ];
		$op_title = $value[ 1 ];

		$output .= '<input id="' . $key . '" name="' . $key . '" type="checkbox" value="1" ';
		if( $op_value ) $output .= 'checked';
		$output .= ' style="margin-left: 10px;" /> ' . $op_title . '<br />';
	} /* foreach */
	$output .= '*Only valid data will be displayed.<br />';

	$output .= '<hr size="1" />';

	$output .= '<input id="display_exif_thumbnail_support" name="display_exif_thumbnail_support" type="checkbox" value="1"' . ( $display_exif_thumbnail_support ? 'checked' : '' ) . ' >&nbsp;display exif window even the image is thumbnail<br />';

	$output .= '<br />For more detail, visit <a href="http://www.vjcatkick.com/?page_id=11751" target="_blank" >document page</a>.<br />';

	$output .= '<p class="submit"><input type="submit" name="display_exif_submit" value="'. 'Update options &raquo;' .'" /></p>';
	$output .= '</form>';

	echo $output;
} /* display_exif_options_page() */

function display_exif_init_jquery() {
	if( !is_admin() ) {
		wp_enqueue_script( 'jquery' );
	}
}  /* display_exif_init_jquery() */
 
add_action( 'init', 'display_exif_init_jquery' );


//add_action( 'wp_head', 'display_exif_js' );
//add_filter( 'the_content', 'display_exif_filter', 50 );
add_action( 'admin_menu', 'display_exif_add_pages');

function pinlink($link) {

	//Removing the http:// part. 
	$pinReadyLink = substr( $link, 7 );

	return $pinReadyLink;

}

//Set up the shortcode
function exif_gallery_shortcode($attr){

//To make this as easy to use and as feature-rich as possible, we'll duplicate some of the same options as the default gallery shortcode.
//Find the original at wp-includes/media.php ln 777

	global $post;

	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	
	
		extract( shortcode_atts( array(
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
			'share' => 'on',
			'embed' => 'on',
			'order' => 'ASC',
			'orderby' => 'menu_order ID',
			'size' => 'large'
		), $attr ) );
		
					
	$columns = "1";
	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';

	$size_class = sanitize_html_class( $size );
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
		
	?>
	
	<?php $output .='
	
	<div class="overall-cycle-container">
	<div class="overall-cycle-contained">		
		<div class="cycle-control">
			<div class="cycle-title">
				<h2><?php echo $title; ?></h2>
			</div>
			<div class="cycle-controls">
				'; 
					if ($exif == 'yes') {
					$output .= '<a id="exifButton" href="#">EXIF</a>';
					}
				$output .='
				<a id="resumeButton" onClick="api.play()" href="#">Play</a> 
				<a id="pauseButton" onClick="api.pause()" href="#">Stop</a> 
				<a id="fullButton" href="#">Full Screen</a>
			</div>
		</div>
		
	<!--<div id="cycle-prev" class="sliderPrev"><a class="prev" href="#"><span class="arrow-w prev"></span></a></div><div id="cycle-next" class="sliderNext"><a class="next" href="#"><span class="arrow-e next"></span></a></div> -->	
		   <div class="cycleContainer">';
				
				
	$c = 0;
	$permaPin = pinlink(get_permalink());
	foreach ( $attachments as $id => $attachment ) {
		
		$output .= '<div class="slide">';
		
		$img = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_image_src($id, $size, false) : wp_get_attachment_image_src($id, $size, false);

//Took out width="' . $img[1] . '" height="' . $img[2] . '"		
//May want to add social js to footer hook instead. 
//http://pinterest.com/about/goodies/		
		$permaPinImg = pinlink($img[0]);
		$attachedID = $attachment->ID;
		$attachedPhotog = get_post_meta($attachedID, '_photocredit', true);
		$output .= '
				<a href="'. $img[0] . '" rel="lightbox"><img src="' . $img[0] . '" /></a>
				';
				
		$output .= '
						<div class="caption">
						
						' . $attachment->post_excerpt . '
						
						</div>
						<div class="photog">' . $attachedPhotog . '</div>
					';
		
		$output .= '<div class="slider-social-box disappear">
						<div class="facebook"><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="' . get_permalink() . '#' . $c . '" show_faces="false" width="380" action="recommend" font=""></fb:like></div>
					
						<div class="plus"><g:plusone annotation="inline" href="' . get_permalink() . '#' . $c . '"></g:plusone></div>
					
						<div class="tweet"><a href="http://twitter.com/share" class="twitter-share-button" data-url="' . get_permalink() . '#' . $c . '" data-text="' . get_the_title() . '" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
					
						<div class="stumble">
							<script src="http://www.stumbleupon.com/hostedbadge.php?s=2"></script>
						</div>
						
						<div class="pin">
							<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script><a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2F' . $permaPin . '#' . $c . '&media=http%3A%2F%2F' . $permaPinImg . '&description='. wptexturize($attachment->post_excerpt) .'" class="pin-it-button" count-layout="horizontal"><img border="0" class="pinbutton" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
						</div>
				</div>';
		
		$output .= '<div class="exif-data disappear">';
		$output .= display_exif_single_replace_cb($img[0]);
		$output .= '</div>';
				
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
			$output .= '</div>';
			$c++;
	}


					
				?>
	<?php		
	
			$output .= '</div><!-- end of #cycleContainer -->';
		$output .= '</div><!-- end of #overall-cycle-contained -->';
	$output .= '</div><!-- end of #overall-cycle-container -->';
	

	return $output;
		
}

add_shortcode( 'exifgallery', 'exif_gallery_shortcode' );


?>
