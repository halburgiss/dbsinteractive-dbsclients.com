<?php
/*
Plugin Name: DBS>Interactive.
Plugin URI: https://www.dbswebsite.com/
Description: Clean up of default WP doodles.
Author: DBS>Interactive
Version: 1.7
Author URI: https://www.dbswebsite.com/

NOTES

This is a simple plugin to debrand, and cleanup unwanted default Wordpress
features, and/or add simplisms, etc. FYI, it removes some stuff that might be
worthwhile for a traditional blog.

@author Hal Burgiss
*/

// HB 2008-07-13. Some of this should go back in for blogs.
// The first 3 in particular, have merit for any purely blog type site.
/*
// moved to Theme.php
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'noindex', 1);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
*/

add_action( 'admin_enqueue_scripts', 'dbs_admin_styles');
function dbs_admin_styles() {
	if (is_admin()) {
		// custom css for branding/debranding.
		wp_enqueue_style('dbs_css', '/' . PLUGINDIR . '/dbs/dbs.css');
		// in case we need custom js for the admin.
		wp_enqueue_script('dbs_js', '/' . PLUGINDIR . '/dbs/dbs.js', array('jquery'), null, true );

		// so don't get overwritten on update
		if ( is_file( '/' . PLUGINDIR . '/dbs/dbs.local.js') ){
			wp_enqueue_script('dbs_js_local', '/' . PLUGINDIR . '/dbs/dbs.local.js', array('jquery'), null, true );
		}
	}
}

// rebrand the login page 2012-02-22
add_action( 'login_enqueue_scripts', 'w4_login_enqueue_scripts' );
function w4_login_enqueue_scripts(){
     echo '<style type="text/css">';
//     echo '#login h1 {display:none;}';
	echo '#login h1 a { background-image: /images/logo.png!important; padding-bottom: 30px;}';
     echo '</style>';
}


add_action( 'admin_notices', 'no_wpupdate', 2 );
function no_wpupdate() {
     remove_action('admin_notices', 'update_nag', 3);
}

add_filter('admin_footer_text', 'remove_footer_admin');
//change admin footer text
function remove_footer_admin () {
	echo '<p class="dbs_admin_footer" align="center"><a href="http://www.dbswebsite.com">DBS &gt; Interactive CMS</a></p>';
	echo '<script type="text/javascript">jQuery( function() {jQuery("span.turbo-nag").remove(); } );</script>';
     if ( is_admin() ) {
		// remove WP branding
          echo '<script type="text/javascript">jQuery( function() {
                    var t = jQuery("title").html();
                    jQuery("title").html( t.replace(/. Wordpress/i,"") );
               } );
               </script>';
     }
}

//2012-05-18
add_action('wp_dashboard_setup', 'wpc_dashboard_widgets');
function wpc_dashboard_widgets() {
     global $wp_meta_boxes;
     // Today widget
     unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
     // Last comments
     unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
     // Incoming links
     unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
     // Plugins
     unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
     unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
}


/**
*
*  All weather utility functions
*
*/

/**
*  @return TRUE if the connected client is DBS. For hacking on live sites.
*
*/
function is_me() {
     return ( $_SERVER['REMOTE_ADDR'] == '216.253.111.162' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || preg_match( '/^(10.0|192.168)/', $_SERVER['REMOTE_ADDR'] ) || defined( 'DEBUG' ) );
}


/**
*
*  Simple debugging aid. The default mode is to dump out the specified
*  variables and stop processing. If $no_exit is set to true, processing is NOT
*  stopped. Typically used for arrays or objects.
*
*  @params variable number of parameters can be passed.
*  @author HB 2008-06-04
*
*/
function dump() {
	if ( !is_me() ) return;
     global $no_exit;
     $args = func_get_args();
     echo '<pre style="font-size:10pt;">';
     for ($i=0;$i<count($args);$i++) {
          print_r( $args[$i] ); echo '<hr />';
     }
     if ( $no_exit === true ) {
          echo '</pre>';
     } else {
          exit;
     }
}
