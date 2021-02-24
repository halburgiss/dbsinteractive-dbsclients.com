/**
 * ADA StyleSheet Selector
 *
 * @usage:
 *
 * 	var style_selector = new StyleSelector(
 * 			'accessibility', {
 * 				'wcag-aaa-light' : jQuery( '#wcag-aaa-light' ),
 * 				'wcag-aaa-dark' : jQuery( '#wcag-aaa-dark' )
 * 			}, jQuery( '#reset' )
 * 		);
 *
 */

function StyleSelector( name, elements, reset ) {

	var style_selector = this; // Create closure to refer to 'this' in event handlers

	this.name = name;
	this.elements = elements;
	this.options = Object.keys(elements);

	this.options.forEach( function( option ) {
		style_selector.elements[option].addEventListener( 'click', function() {
			style_selector.choose( option );
		});
	});

	// Reset button event
	reset.addEventListener( 'click', function() {
		style_selector.reset();
	});

	this.value = '';
	if ( readCookie( this.name ) ) {
		this.value = readCookie( this.name );
	}

	// Set the value to ensure current value is being used.
	this.choose( this.value );
}


/**
 * Load CSS Files Dynamically
 *
 * Helpful for on demand loading of files not integral to the immediate
 * function of the site, saving initial load time.
 * For the inspiration of this function:
 * https://stackoverflow.com/questions/11833759/add-stylesheet-to-head-using-javascript-in-body
*/
function loadCSS(url) {
	if ( document.createStyleSheet ) { // ie fix
		document.createStyleSheet(url);
	} else {
		var head = document.head;
		var link = document.createElement("link");

		link.type = "text/css";
		link.rel = "stylesheet";
		link.href = url;

		head.appendChild(link);
	}
}


/**
 * Choose a style
 */
StyleSelector.prototype.choose = function( value ) {
	// Remove the old class
	if ( document.querySelector( 'body' ).classList.contains( this.value ) ) {
		document.querySelector( 'body' ).classList.remove( this.value );
	}

	// Set the new class
	this.value = value;

	if ( value !== '' ) {
		document.querySelector( 'body' ).classList.add( this.value );

		if ( value == 'wcag-aaa-dark' ) {
			loadCSS( dbs.theme_directory + '/library/css/accessibility/wcag-aaa-dark' + dbs.css_file_extension );
		}

		if ( value == 'wcag-aaa-light' ) {
			loadCSS( dbs.theme_directory + '/library/css/accessibility/wcag-aaa-light' + dbs.css_file_extension );
		}

	}

	// Save the value as a cookie
	setCookie( this.name, value );
};


/**
 * Choose a style
 */
StyleSelector.prototype.reset = function() {
	// Remove the old class
	if ( document.querySelector( 'body' ).classList.contains( this.value ) ) {
		document.querySelector( 'body' ).classList.remove( this.value );
	}

	// Save the value as a cookie
	eraseCookie( this.name );
};


var style_selector = new StyleSelector(
	'contrast', {
		'wcag-aaa-light' : document.getElementById( 'wcag-aaa-light' ),
		'wcag-aaa-dark' : document.getElementById( 'wcag-aaa-dark' )
	}, document.getElementById( 'reset-accessibility' )
);