/**
 * Accessibility Menu
 *
 * NOTE: This assumes you are using pixels for your font sizes on body and html.
 *
 * @usage:
 *
 * 	var accessibility_menu = new AccessibilityMenu(
 * 			jQuery( '.wcag-wrapper' ),
 * 			jQuery( '#decrease-font' ),
 * 			jQuery( '#reset-accessibility' )
 * 		);
 *
 */

// Commonly re-used variables
var body = document.querySelector( 'body' );
var wcagClose = document.getElementById( 'wcag-button__close' );
var wcagOpen = document.getElementById( 'wcag-button__open' );
var wcagWrapper = document.getElementById( 'wcag-panel' );


function AccessibilityMenu( element, open_button, close_button ) {
	var accessibility_menu = this; // Create closure to refer to 'this' in event handlers

	this.element = element;
	this.is_open = false;
	this.open_button = open_button;
	this.close_button = close_button;


	// Event handlers
	this.open_button.addEventListener( 'click', function() {
		if ( accessibility_menu.is_open ) {
			accessibility_menu.close();
		} else {
			accessibility_menu.open();
		}
	});

	this.close_button.addEventListener( 'click', function() {
		accessibility_menu.close();
	});

	// Start out closed
	this.close();

    // Close wcag menu when esc is pressed
    document.addEventListener('keydown', function(event){
        if (event.keyCode == 27) { // escape key maps to keycode `27`
            // Close WCAG accessibility menu
            if (accessibility_menu.is_open) {
                accessibility_menu.close();
                $('.wcag-button__open').focus();
            }
        }
    });
}


/**
 * Opens the menu
 */
AccessibilityMenu.prototype.open = function() {
	this.is_open = true;
	body.classList.add( 'accessibility-menu-opened' );
	this.element.setAttribute( 'aria-expanded', 'true' );
	this.element.setAttribute( 'aria-hidden', 'false' );
};


/**
 * Closes the menu
 */
AccessibilityMenu.prototype.close = function() {
	this.is_open = false;
	body.classList.remove( 'accessibility-menu-opened' );
	this.element.setAttribute( 'aria-expanded', 'false' );
	this.element.setAttribute( 'aria-hidden', 'true' );
	this.open_button.focus();
};

/**
* Button to disable stylesheets
*
* This removes all stylesheets, hides images, sliders, and the
* accessiblilty menu. It adds a button to close and reload with the
* stylesheets.
*/
jQuery( '#disable-stylesheet' ).on( 'click', function(){
	jQuery( 'link[rel=stylesheet], #testid' ).remove();
	jQuery( 'img, svg, .blog-post__header, .flickity-enabled, .wcag-panel, .secondary_menu' ).css({ display : 'none' });
	jQuery( 'body' ).append( '<button id="close-me" style="font-size:2em; padding: 1em 2em; position: fixed; top: 1em; right:2em;">Close</button>' );
	jQuery( '#close-me' ).click( function(){ location.reload(); });
});

/**
 * Tab Functionality for the Accessibility Menu
 */
// Menu close button functionality
wcagClose.addEventListener( 'keydown', function(e) {
	var keyCode = e.keyCode || e.which;
	if ( keyCode == 9 ) {
		e.preventDefault();
		document.querySelector( '.wcag-panel .wcag-button:first-of-type' ).focus();
	}
	if ( keyCode == 9 && e.shiftKey ) {
		document.getElementById( 'reset-accessibility' ).focus();
	}
});


wcagClose.addEventListener( 'keyup', function(e) {
	if ( ! body.classList.contains( 'accessibility-menu-opened' ) ) {
		wcagOpen.focus();
	}
});


// Loop back to the close button if tabbing backwards on the first element
document.querySelector( '.wcag-panel .wcag-button:first-of-type' ).addEventListener( 'keydown', function(e) {
	var keyCode = e.keyCode || e.which;
	if ( keyCode == 9 && e.shiftKey ) {
		e.preventDefault();
		wcagClose.focus();
	}
});


// Prevent default tabbing and enter the accessibility menu if opened
wcagOpen.addEventListener( 'keydown', function(e) {
	var keyCode = e.keyCode || e.which;

	if ( body.classList.contains( 'accessibility-menu-opened' ) ) {
		if (keyCode == 9 && e.shiftKey) {
			e.preventDefault();
			wcagClose.focus();
		} else if (keyCode == 9) {
			e.preventDefault();
			document.querySelector( '.wcag-panel__main-settings .wcag-button:first-of-type' ).focus();
		}
	}
});


/**
 * Accessibility Menu and controls
 */
var accessibility_menu = new AccessibilityMenu(
	wcagWrapper,
	wcagOpen,
	wcagClose
);
