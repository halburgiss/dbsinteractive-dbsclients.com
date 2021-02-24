<?php
/**
* dbs_config.php
*
* Parses the bash config file and makes php vars from it.
*
* @author Hal Burgiss  2018-06-14
*/

$conf = array();
// try from site root
if ( is_file( ".dbs/config" ) ) {
	$fh = fopen( ".dbs/config", "r" );
} else {
	// if that fails, try from this folder
	$fh = fopen( "config", "r" );
}

while ( $line = fgets( $fh, 80 ) ) {
	if ( preg_match( '/^[\-a-z_]+=([\.a-z0-9]+)$/', $line ) ) {
		$line_a = explode("=", $line);
		$conf[$line_a[0]] = str_replace( PHP_EOL, null, $line_a[1] );
	}
}

// make vars
extract( $conf );

if ( !empty( $staging ) ) {
	define( 'DBS_STAGING_DOMAIN', "staging$staging.resultsbydesign.com" );
}
if ( !empty( $domain ) ) {
	define( 'DOMAIN', "$domain" );
}



