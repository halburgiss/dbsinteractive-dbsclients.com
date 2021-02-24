/**
 * Scripts that make things easier to debug on dev environment.
 *
 * @author HGB3 12/28/2016
 */
jQuery( window ).ready( function() {


	/**
	 * Pretty console logs messages.
	 *
	 * @param string msg - message to print
	 */
	function log( msg ) {
		console.log(
			"%c[SLATE]%c " + msg,
			"font-weight: bold;",
			"font-weight: normal;"
		)
	}



	/**
	 * Add a rule to test for extra long classnames.
	 */
	HTMLInspector.rules.add(
		"extra-long-classnames",
		{ length: 36, },
		function( listener, reporter, config ) {
			listener.on( 'class', function( className, element ) {
				// Classes to ignore:
				if ( className.startsWith( 'gform_' ) ) { return; }
				if ( className.startsWith( 'gfield_' ) ) { return; }

				if ( className.length > config.length ) {
					reporter.warn(
						"extra-long-classnames",
						"Classname '" + className + "' is longer than recommended. Consider using a shorter classname.",
						element
					)
				}
			});
		}
	);

	/**
	 * Custom rule for finding inline event handlers
	 */
	HTMLInspector.rules.add(
		"dbs-inline-event-handlers",
		null,
		function(listener, reporter, config) {
			listener.on('attribute', function(name, value) {

				// Ignore gravity forms
				if ( value.includes( 'gform_' ) ) { return; }
				if ( value.includes( 'gfield_' ) ) { return; }

				if ( name.indexOf( "on" ) === 0 ) {
					reporter.warn(
						"dbs-inline-event-handlers",
						"An '" + name + "' attribute was found in the HTML. Use external scripts for event binding instead.",
						this
					);
				}
			});
		}
	);

	/**
	 * Wrapping/Overriding some of the attribute rules.
	 */
	isAttributeValidForElement = HTMLInspector.modules.validation.isAttributeValidForElement;
	HTMLInspector.modules.validation.isAttributeValidForElement = function( attribute, element ) {
		if ( element === 'html' && attribute === 'prefix' ) { return true; }
		if ( element === 'img' && attribute === 'sizes' ) { return true; }
		if ( element === 'img' && attribute === 'srcset' ) { return true; }
		if ( element === 'meta' && attribute === 'property' ) { return true; }
		return isAttributeValidForElement( attribute, element );
	};

	/**
	 * Ignore the fact that jquery is placed in the header.
	 */
	HTMLInspector.rules.extend( "script-placement", {
		whitelist: jQuery( "script[src*='jquery.min.js']" ).toArray()
			.concat ( jQuery( "script[src*='jquery.js']" ).toArray() )
			.concat ( jQuery( "script:not([src])" ).toArray() ),  // Ignore inline scripts
	});

	/**
	 * Do the code inspection.
	 *
	 * @see https://philipwalton.com/articles/introducing-html-inspector/
	 */
	HTMLInspector.inspect({
		excludeSubTrees: [
			"svg",
			"iframe",
		].concat( jQuery( ".debug-current-template" ).toArray() ),
		excludeRules: [
			"unused-classes",
			"inline-event-handlers",
		],
		onComplete: function( errors ) {
			errors.forEach( function( error ) {
				console.warn( error.message, error.context );
			});
		}
	});



	/**
	 * Converts a url to use staging domain instead of localhost.
	 */
	function convertURL( i, url ) {
		if ( ! url ) { return; }
		//        (Group 1)  // (Group 2 domain.loc or localhost:port )
		var re = /(https?:)?\/\/([a-zA-Z0-9._-]*\.loc|localhost:[0-9]*)\/wp-content\/uploads\//;
		var matches = url.match( re );

		if ( matches && matches[2] !== undefined ) {  // The second match is the domain
			var new_url = url.replace( new RegExp( matches[2], 'g' ), dbs.staging_domain );
			log( "Converting local: <" + url.split(' ')[0] + "> to staging: <" + new_url.split(' ')[0] + ">." );
			return new_url;
		}
		return url;
	}

	/**
	 * Changes an image SRC to use staging domain instead of localhost.
	 */
	 /* deprecated use mod rewrite in dbs_repos.conf now 2017-06-29

	var repeat = 0;  // This is repeated to catch lazy-loaded images
	var useStagingImages = function() {
		if ( repeat < 10 ) {
			jQuery( '[srcset]' ).attr( 'srcset', convertURL );
			jQuery( '[src]' ).attr( 'src', convertURL );
			jQuery( '[data-src]' ).attr( 'data-src', convertURL );
			jQuery( '[data-bg-srcset]' ).attr( 'data-bg-srcset', convertURL );
			jQuery( '[data-thumb-src]' ).attr( 'data-thumb-src', convertURL );
			// An ugly hack for a race condition
			if ( typeof bgss !== 'undefined' ) {
				bgss.init('.bgimg'); // Re-initialize SrcSet
			}
			repeat++;
		} else {
			clearInterval( updateImageSrcInterval );
		}
	}
	useStagingImages();  // Run immediately, then repeat every second for 10 seconds
	var updateImageSrcInterval = setInterval( useStagingImages, 1000);
*/

});
