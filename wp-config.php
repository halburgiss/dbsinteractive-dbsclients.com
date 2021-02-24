<?php
/**
 *  This file has various customizations done for DBS. 2009-03-25
 *
 *  See http://codex.wordpress.org/Editing_wp-config.php for tips.
 */

/**
 * The base configurations of the WordPress.
 *
 * @package WordPress
 */

/** The name of the database for WordPress */
// see below

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 #@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/ WordPress.org secret-key service}
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY', ',,,4-ff0a7afc5eba;78bad36e04cca7aaf40basdf33nD' );
define( 'SECURE_AUTH_KEY', 'bDS4ff0a7a9fc5eba78bad36e04c3ca7-$a4a0b' );
define( 'LOGGED_IN_KEY', 'D.,./4ff0a7ad9fc5eba78bad36e04cbdaca7aIY^40ba767+-?' );
define( 'NONCE_KEY', 'MM4ff0a7a9fcaaaa5eba78bad36e04cca7afd4a30b[][&Yad' );
define( 'AUTH_SALT',		  '--34qbadfgbere%^asda3ba__wdadsFDd' );
define( 'SECURE_AUTH_SALT', ',ladsfcvobn34KDag923maa,lLLdfae&D7322' );
define( 'LOGGED_IN_SALT',	'++9knLWSgnla903;lad1-l_-23aka8DSdDasfga' );
define( 'NONCE_SALT',		 '38jkadnLdnad=2@@2a0paa,.ldasdf23IJa4dawe23342,=O' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 */
// HB 2009-03-25 ///////////////////////////////
$table_prefix  = 'dbswp_';
/**
 * WordPress Localized Language, defaults to English.
 */
define( 'WPLANG', '' );

/* That's all, stop editing! Happy blogging. */

/** WordPress absolute path to the Wordpress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

// mostly for WP CLI 2015-02-16
if ( empty( $_SERVER['HTTP_HOST'] ) ) {
	$_SERVER['HTTP_HOST'] = 'localhost';
}
// mostly for WP CLI 2015-02-16
if ( empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) {
	$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'http';
}

// other useful settings 2012-10-19
define( 'WP_POST_REVISIONS', 4 );
// disables the theme editor
define( 'DISALLOW_FILE_EDIT',true );
// disables plugin updating via wp-admin
define( 'DISALLOW_FILE_MODS',true );
define( 'WP_AUTO_UPDATE_CORE',false );

// Handle SSL and default base URL's
if ( isset( $_SERVER['HTTPS'] ) || 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO'] ) {
	define( 'WP_CONTENT_URL', "https://$_SERVER[HTTP_HOST]/wp-content" );
	define( 'WP_HOME', "https://$_SERVER[HTTP_HOST]" );
	define( 'WP_SITEURL', "https://$_SERVER[HTTP_HOST]" );
	define( 'WP_PLUGIN_URL', 'https://'.$_SERVER['HTTP_HOST'].'/wp-content/plugins' );
	$_SERVER['HTTPS'] = 'on'; // required to make is_ssl() work under load balancing / proxies
} else {
	define( 'WP_CONTENT_URL', "http://$_SERVER[HTTP_HOST]/wp-content" );
	define( 'WP_HOME', "http://$_SERVER[HTTP_HOST]" );
	define( 'WP_SITEURL', "http://$_SERVER[HTTP_HOST]" );
}

// can be run from system cron
// 10 * * * * root /usr/bin/php /home/clients/test.com/wp-cron.php >/dev/null 2>&1
// define('DISABLE_WP_CRON', true);
define( 'PRODUCTION', 0 );
define( 'DEVEL', 1 );
define( 'STAGING', 2 );
define( 'LOCAL', 3 );

// cute debugging toggle
if ( isset( $_GET['debug'] ) ) { define( 'DEBUG', true ); }

$app_mode = PRODUCTION;

// NOTE: command line scripts may need to be checked to see which db they use. FYI.
if ( is_file( 'local.php' ) || is_file( '../local.php' ) ) {
	require 'local.php';		// let devs use a personal configuration 2017-02-02
} else if ( 'localhost' === $_SERVER['HTTP_HOST'] || strstr( $_SERVER['HTTP_HOST'],'staging' ) || strstr( $_SERVER['HTTP_HOST'],'.loc' ) || strstr( ABSPATH, '/staging' ) ) {
	define( 'DB_NAME', 'dbs_clients' );
	/** MySQL database username */
	define( 'DB_USER', '2774615058' );
	/** MySQL database password */
	define( 'DB_PASSWORD', '05eb32afc16ab5a79c1c00b15140dced' );
	/** MySQL hostname */
	define( 'DB_HOST', 'dbs16.dx30.net' );
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
	if ( isset( $_GET['force-static'] ) ) {
		//for static-site builder
		define( 'DBS_STATIC_URL', 'https://d2bgm5fr9jeu5e.cloudfront.net' );
	} else {
		define( 'DBS_STATIC_URL', WP_SITEURL );
	}
	define( 'WP_CACHE', false );
	define( 'IS_LIVE', false );
	// get DBS_STAGING_DOMAIN from env. 2018-06-14
	if ( is_file( '.dbs/dbs_config.php' ) ) include '.dbs/dbs_config.php';
} else {
	define( 'DB_NAME', 'dbs_clients' );
	/** MySQL database username */
	define( 'DB_USER', '2774615058' );
	/** MySQL database password */
	define( 'DB_PASSWORD', '05eb32afc16ab5a79c1c00b15140dced' );
	/** MySQL hostname */
	define( 'DB_HOST', 'dbs16.dx30.net' );
	// Where static content should live, eg images, pdf's, etc.
	ini_set( 'log_errors','On' );
	ini_set( 'display_errors','false' );
	// for cdn networks, and handle SSL.
	define( 'DBS_STATIC_URL', ( isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] );
	ini_set( 'error_reporting', E_ALL & ~E_DEPRECATED & ~E_NOTICE );
	  // caching should be on for all live sites.
	define( 'WP_CACHE', true );
	define( 'IS_LIVE', true );
	define( 'WPCACHEHOME', ABSPATH . 'wp-content/plugins/wp-super-cache/' );
}

define( 'APP_MODE', $app_mode );

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
