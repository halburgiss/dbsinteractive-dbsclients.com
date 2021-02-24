<?php
/** 
 * This can be used to override the db stuff in wp-config.php, for instance to 
 * use a custom, local database.
 *
 * Must be renamed to local.php and the contents edited and placed in the root 
 * of the site.
 */

/** The name of the database for WordPress */
define('DB_NAME', 'dbs_wp_bones_theme');

/** MySQL database username */
define('DB_USER', 'dbs_programming');

/** MySQL database password */
define('DB_PASSWORD', 'yourpasswordhereDUDE');

define('DB_HOST', 'dbs9.dx30.net');

// DBS: Show errors, on staging and home directories, not live.
ini_set( 'log_errors','On' );
ini_set( 'display_errors','true' );
ini_set( 'display_startup_errors','true' );
ini_set( 'session.gc_maxlifetime', 86400 * 90 );
ini_set( 'error_reporting', E_ALL & ~E_DEPRECATED );

$app_mode = LOCAL;

if ( strstr( $_SERVER['HTTP_HOST'],'staging' ) && ! '127.0.0.1' === $_SERVER['SERVER_ADDR'] ) { $app_mode = STAGING; }

if ( defined( 'DEBUG' ) ) {
	define( 'SCRIPT_DEBUG', true );
	define( 'SAVEQUERIES', true );
	define( 'WP_DEBUG', true );  // very noisy!
}

define( 'DBS_STATIC_URL', WP_SITEURL );
define( 'WP_CACHE', false );
define( 'IS_LIVE', false );
define( 'DBS_STAGING_DOMAIN', 'staging4.resultsbydesign.com' );

