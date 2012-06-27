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


//Set up the shortcode
function exif_gallery_shortcode($atts){

//To make this as easy to use and as feature-rich as possible, we'll duplicate some of the same options as the default gallery shortcode.
//Find the original at wp-includes/media.php ln 777

		extract( shortcode_atts( array(
			'for' => 'Chronotope',
			'exif' => 'yes',
			'autoplay' => 'yes',
			'title' => 'Gallery',
			'fontsize' => '1em',
			'share' => 'on',
			'embed' => 'on',
			'order' => 'ASC',
			'orderby' => 'menu_order ID',
			'size' => 'large'
		), $atts ) );

}

add_shortcode( 'exifgallery', 'exif_gallery_shortcode' );

//Adding photo credit option to the upload image interface.
//We will call this later using:
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
		'label' => '<span style="color:#ff0000; margin:0; padding:0;">'.__('Photo Credit').'</span> <br />'.__('Add Photographer Name / Photo Type to image'),
		'input' => 'text',
		'value' => get_post_meta($post->ID, '_photocredit', true)  
	);
	  
	return $form_fields;
}  

add_filter('attachment_fields_to_edit', 'add_photo_credit_option', null, 2);

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



function display_exif_replace_cb( $matches ) {
	global $global_exif_datas;
	$output = $matches[ 0 ];	// something must return

	$attrs_org = $matches[ 1 ];
	$dp_str = 'DISPEXIF_' . rand( 10000, 99999 );

	$options = get_option( 'display_exif' );
	if( !$options ) $options[ 'display_exif_switches' ] = display_exif_get_default_option_value();

	$filename = '';
	if( preg_match( '/src="(.+?)"/s', $attrs_org, $f ) && !preg_match( '/^"/s', $f[ 1 ] ) ) {
		$filename = $f[ 1 ];

		$titletext = '';
		if( preg_match( '/title="(.+?)"/s', $attrs_org, $t ) && !preg_match( '/^"/s', $t[ 1 ] ) ) $titletext = $t[ 1 ];

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

			$global_exif_datas[ $dp_str ] = $exif_array;

			$dp_classname = $dp_str;
			$output = '<img ' . $attrs_org . ' displayexif=".' . $dp_classname . '" />';

		} /* if */
	} /* if */

	return( $output );
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
} /* display_exif_init_jquery() */
 
add_action( 'init', 'display_exif_init_jquery' );


add_action( 'wp_head', 'display_exif_js' );
add_filter( 'the_content', 'display_exif_filter', 50 );
add_action( 'admin_menu', 'display_exif_add_pages');


?>

?>