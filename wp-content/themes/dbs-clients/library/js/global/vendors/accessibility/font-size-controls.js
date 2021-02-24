/**
 * Accessibility Font Size Controls
 *
 * NOTE: This assumes you are using pixels for your font sizes on body and html.
 *
 * @usage:
 *
 * 	var font_size_controls = new FontSizeControls(
 * 			jQuery( '#increase-font' ),
 * 			jQuery( '#decrease-font' ),
 * 			jQuery( '#reset-font' ),
 * 		);
 *
 * 	font_size_controls.onChange(
 * 			function( x ) {
 * 			}
 * 		);
 *
 */

// Commonly re-used variables
var fontIncrease = document.getElementById( 'font-increase' );
var fontDecrease = document.getElementById( 'font-decrease' );
var reset = document.getElementById( 'reset-accessibility' );

function FontSizeControls( increase_button, decrease_button, reset_button ) {
	var font_size = this; // Create closure to refer to 'this' in event handlers

	this.increase_button = increase_button;
	this.decrease_button = decrease_button;
	this.reset_button = reset_button;

	this.handlers = []; // An array of change event handlers

	this.increase_button.addEventListener( 'click', function() {
		font_size.increase();
	});

	this.decrease_button.addEventListener( 'click', function() {
		font_size.decrease();
	});

	this.reset_button.addEventListener( 'click', function() {
		font_size.reset();
	});

	// Use the size saved in a cookie or revert to the default.
	this.default_size = 18;
	this.current_size = this.default_size;
	if ( readCookie( 'font-size' ) ) {
		this.current_size = readCookie( 'font-size' );
	}

	this.apply();  // Apply the current styles once after initializing.
}


/**
 * Applies the current font size.
 */
FontSizeControls.prototype.apply = function() {
	// Apply the current font size
	document.querySelector('html').style.fontSize = this.current_size + 'px';
	document.querySelector('body').style.fontSize = this.current_size + 'px';

	// Trigger a resize to allow anything related to window sizes
	// to re-calculate.
	var resizeEvent = window.document.createEvent( 'UIEvents' ); 
	resizeEvent.initUIEvent( 'resize', true, false, window, 0 ); 
	window.dispatchEvent( resizeEvent );

	// Save the state in a cookie for future visits:
	setCookie("font-size", this.current_size);

	this.redraw();

	// Trigger a change to run any event handlers
	this.triggerChange();
};


/**
 * (Re)Draws the controls based on current size
 */
FontSizeControls.prototype.redraw = function() {
	// Hide the increase button when we get to the max size
	if ( this.current_size > 28 ) {
		this.increase_button.style.display = 'none';
	} else {
		this.increase_button.style.display = 'inline-block';
	}

	// Hide the decrease button when we get to the min size
	if ( this.current_size < 14 ) {
		this.decrease_button.style.display = 'none';
	} else {
		this.decrease_button.style.display = 'inline-block';
	}
}


/**
 * Increases the font size.
 */
FontSizeControls.prototype.increase = function() {
	this.current_size = this.current_size * 1.1;
	this.apply();
};


/**
 * Decreases the font size.
 */
FontSizeControls.prototype.decrease = function() {
	this.current_size = this.current_size / 1.1;
	this.apply();
};


/**
 * Resets the font size to the default.
 */
FontSizeControls.prototype.reset = function() {
	this.current_size = this.default_size;
	this.apply();
};


/**
 * Creates an onChange event hander
 */
FontSizeControls.prototype.onChange = function( fn ) {
	this.handlers.push( fn );
	return this; // Be fluent
};


/**
 * Triggers a change event by calling any existing handlers
 */
FontSizeControls.prototype.triggerChange = function() {
	var font_size_controls = this; // Create for closure
	this.handlers.forEach( function( fn ) {
		if (fn instanceof Function) {
			fn( font_size_controls.current_size );
		}
	});
};


var font_size_controls = new FontSizeControls(
	fontIncrease,
	fontDecrease,
	reset
);