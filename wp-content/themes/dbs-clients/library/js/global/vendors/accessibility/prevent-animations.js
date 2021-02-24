/**
 * Simple script for stopping all css animations
 */

function StopAnimations( name, elements, reset ) {

	var stop_animations = this; // Create closure to refer to 'this' in event handlers

	this.name = name;
	this.elements = elements;
	this.options = Object.keys(elements);

	this.options.forEach( function( option ) {
		stop_animations.elements[option].addEventListener( 'click', function() {
			stop_animations.choose( option );
		});
	});

	// Reset button event
	reset.addEventListener( 'click', function() { stop_animations.reset(); });

	this.value = '';
	if ( readCookie( this.name ) ) {
		this.value = readCookie( this.name );
	}

	// Set the value to ensure current value is being used.
	this.choose( this.value );
}


/**
 * Choose a style
 */
StopAnimations.prototype.choose = function( value ) {
	// Remove the old class
	if ( document.querySelector( 'body' ).classList.contains( this.value) ) {
		document.querySelector( 'body' ).classList.remove( this.value );
	}
	// Set the new class
	this.value = value;

	if ( value !== '' ) {
		document.querySelector( 'body' ).classList.add( this.value );

		// Assistive text for accessibility

		// First remove old assistive alerts
		var oldSpans = document.querySelectorAll( '.wcag-assistive' );
		for ( var i = 0; oldSpans.length > i; i++ ) {
			document.querySelector( 'body' ).removeChild( oldSpans[i] );
		}
		
		// Then append new alert.
		var assistiveSpan = document.createElement( 'span' );
		assistiveSpan.classList.add( 'assistive' );
		assistiveSpan.classList.add( 'wcag-assistive' );
		assistiveSpan.setAttribute( 'role', "alert" );
		assistiveSpan.textContent = 'Prevent Animations mode enabled. All non-essential animations have been removed. Tab forward for other options or to reset settings to default';
		document.querySelector( 'body' ).appendChild( assistiveSpan );
	}

	// Save the value as a cookie
	setCookie( this.name, value );
};


/**
 * Choose a style
 */
StopAnimations.prototype.reset = function() {
	// Remove the old class
	if ( document.querySelector( 'body' ).classList.contains( this.value) ) {
		document.querySelector( 'body' ).classList.remove( this.value );
	}

	// Remove assistive alerts
	var oldSpans = document.querySelectorAll( '.wcag-assistive' );
	for ( var i = 0; oldSpans.length > i; i++ ) {
		document.querySelector( 'body' ).removeChild( oldSpans[i] );
	}

	// Save the value as a cookie
	eraseCookie( this.name );
};


var stop_animations = new StopAnimations(
	'animations', {
		'prevent-animations' : document.getElementById( 'prevent-animations' )
	}, document.getElementById( 'reset-accessibility' )
);