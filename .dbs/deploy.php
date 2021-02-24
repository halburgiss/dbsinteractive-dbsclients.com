<?php
/**
* deploy.php
*
* Process a github webhook for Push events (only).
*
* Setup for the first time use:
*
* - In github, create the webhook that points to a url for this script on the staging 
*   server (never a live site), which is in the .dbs folder.
* - You will need to include basic auth login info, ala:
*   http://DBSclient:interactive@staging15.resultsbydesign.com/.dbs/deploy.php
* - The initial attempt uses ef2798433be7e940e883f7 as the 'secret' (not sure 
*   what all this controls), but you probably can just wing it.
* - The user executing php must have an account with ssh keys. The login shell 
*   in /etc/passwd should be set to /usr/sbin/login (just in case).
* - The user must be known to git too. You can use email  = programming@dbswebsite.com.
* - The .ssh folder can be copied from dbs9. Make sure to chown, if needed. 
* - Staging sites are writable by anyone in the develop group. Make sure the 
*   above user is in that group. su to that user to test keys, sanity, etc., 
*   with a test 'git pull'.
* - By default most of our systems block access to dotfiles and folders. If 
*   not already done, an exception needs to be created for the above script,
*   for staging sites only.
*
* @author Hal Burgiss  2019-03-05
*/


if ( !isset( $_POST['payload'] ) ) die();

// extract data from POST, and then make sure we are posting to a 'staging' branch
$data = json_decode( $_POST['payload'] );

// check this is the 'staging' branch
if ( 'refs/heads/staging' === $data->ref ) {
	error_log( 'Processing github webhook' );
	$output = shell_exec( 'git pull origin staging 2>&1' );
	error_log( "Shell output git webhook: " . $output );

	// post to slack channel that staging site is updated.
	// parse config file
	include 'dbs_config.php';
	if ( isset( $domain ) && isset( $staging ) ) {
		$body = json_encode( array("text" => "$domain staging http://staging${staging}.resultsbydesign.com/ is updated" ) );
		// General Slack channel https://hooks.slack.com/services/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
		// #test channel https://hooks.slack.com/services/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
		// #project_management: https://hooks.slack.com/services/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
		$ch = curl_init( 'https://hooks.slack.com/services/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');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen( $body ) ) 
		); 
				 
		$result = curl_exec( $ch );
		mail( 'hal@dbswebsite.com', 'deploy', $body ); 
	}

} else {
	error_log( "Not a staging branch: " . $data->ref );
}

