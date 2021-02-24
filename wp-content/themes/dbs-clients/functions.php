<?php
/**
 * @file functions.php
 * @author DBS>Interactive
 * @subpackage Slate
 * @since Slate 0.1.0
 */
namespace Slate;

// Remove or comment out these 2 lines if used in a child theme.
require_once 'Base/autoloader.php';
\Autoloader::register();

use \Base\Utilities as Utils;
use \Base\Config;
// use \Base\Fields;
use Slate\Slate;

$dbs = new Config();

define( 'CDN_TEMPLATE_URL', str_replace( WP_SITEURL, DBS_STATIC_URL, get_template_directory_uri() ) );
define( 'BLANK_GIF',  get_stylesheet_directory_uri()   . '/library/images/blank.gif' );

add_action('after_setup_theme', function() {
	global $theme, $utils, $dbs;

	$theme = new Slate();
	$utils = new Utils();
	$dbs = new Config();

	// helps searchability where pages are mostly custom fields. 2017-09-18
	if ( $dbs->has_search ) { add_post_type_support( 'page', 'excerpt' );
	} else {
        add_filter( 'disable_wpseo_json_ld_search', '__return_true' );
    }

	if ( $dbs->editor_menus ) :
		// allow our "managers" to edit menus. manually add 'editor' role if needed.
		$role_object = get_role( 'wpseo_manager' );
		$role_object->add_cap( 'edit_theme_options' );
	endif;

});

// TODO: move to Theme.php
// added 2016-11-30 forcing SSL within most / all the content area.
if ( isset( $_SERVER['HTTPS'] ) ) {

	function callback( $buffer ) {
		return str_replace( 'http://' . $_SERVER['HTTP_HOST'] , 'https://' . $_SERVER['HTTP_HOST'] , $buffer );
	}

	function buffer_start() {
		ob_start( __NAMESPACE__ . '\\' .  'callback' );
	}

	function buffer_end() {
		ob_end_flush();
	}

	add_action( 'after_setup_theme', __NAMESPACE__ . '\\' . 'buffer_start' );
	add_action( 'shutdown',  __NAMESPACE__ . '\\' . 'buffer_end' );
}

/**
* If an image is uploaded to library, make a webp copy.
*
* WARNING: Only use if we are hosting!!! Disable otherwise here and in dbs-lazyload.js
*
* @author Hal Burgiss  2019-01-10
*/
add_filter( 'wp_generate_attachment_metadata', function( $meta ) {
	// sanity checks.
	if ( empty( $meta ) ) return $meta;
	// only images
	$ext = pathinfo( $meta['file'], PATHINFO_EXTENSION );
	if ( ! in_array( $ext, array( 'jpg', 'jpeg', 'JPG', 'JPEG', 'PNG', 'png' ) ) ) return $meta;

	$path = dirname( $meta['file'] );
	// process
	$images = [ $meta['file'] ];
	foreach ( $meta['sizes'] as $f => $v ) :
		$images[] = $path . '/' . $v['file'];
	endforeach;
	for ( $i=0; $i<count( $images ); $i++ ) {
		// suppress any output due to ajax from WP, custom dbs script and server config.
		exec( "cd ../wp-content/uploads; /usr/local/bin/convert_to_webp.sh  $images[$i] >/dev/null" );
		error_log( "Generating webp img for: $images[$i]" );
	}
	return $meta;
}, 10, 2);

// 2020-09-02, limit size of image uploads
add_filter( 'wp_handle_upload_prefilter', function ( $file ) {
		$size = $file['size'];
		$size = $size / 1024;
		$type = $file['type'];
		$is_image = strpos( $type, 'image' ) !== false;
		$limit = 1000;
		$limit_output = '1000kb';
		if ( $is_image && $size > $limit ) {
			$file['error'] = 'Sorry, but image files must be smaller than ' . $limit_output . '. Please resize.';
		}
		return $file;
	}
 );

 /**
* Remove the register link from the login screen 2020-10-28 HB
*/
add_filter( 'option_users_can_register', function($value) {
	return false;
});



if ( false ===  $dbs->has_search ) {
	// disable search feature 2015-03-30
	function fb_filter_query( $query, $error = true ) {
	        if ( is_search() ) {
	                $query->is_search = false;
	                $query->query_vars[s] = false;
	                $query->query[s] = false;
	
	                // to error
	                if ( $error == true ) {
	                        $query->is_404 = true;
	                }
	        }
	}
	add_action( "parse_query", __NAMESPACE__ .  "\\fb_filter_query" );
	add_filter( "get_search_form", function( $a ) { 
		 return null; 
	});


}

// login screen show password 2020-07-16
add_action( 'login_enqueue_scripts', function() {
     wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', false );
     wp_enqueue_script( 'jquery' );
 }, 1 ); 

// Allow SVG uploads to media library 2021-02-10
add_filter( 'upload_mimes', function ( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}, 1 );


