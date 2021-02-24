/**
 * Slate Mobile Navigation Menu
 *
 */
( function( ) {

    'use strict';

	/**
	 * 'is_mobile' variable to determine if this is a mobile size.
	 */
	var set_is_mobile, is_mobile = false;

	window.addEventListener( 'load', function() {
		var menuToggle = document.getElementById( 'menu-toggle' );
	
		(set_is_mobile = function() {
			if ( window.getComputedStyle( menuToggle ).display === "none" ) {
				is_mobile = false;
			} else {
				is_mobile = true;
			}
		})();
	
		/**
		 * Toggle menu open/closed when clicking menu toggle
		 */
		menuToggle.addEventListener( 'click', function() {
			this.classList.toggle( 'open' );
			document.getElementById( 'menu' ).classList.toggle( 'open' );
			document.querySelector( 'body' ).classList.toggle( 'opened-menu' );
	
			if ( this.classList.contains( 'open' ) ) {
				this.setAttribute( 'aria-expanded', true );
			} else {
				this.setAttribute( 'aria-expanded', false );
			}
		});
	});


	var subToggles = document.querySelectorAll( '.submenu-toggle' );

	for (var i = 0; subToggles.length > i; i++ ) {
		var focus_out_timer = null;
		var toggle_button = subToggles[i];

		toggle_button.addEventListener( 'click', function() {
			this.classList.toggle( 'open' );
			this.parentNode.classList.toggle( 'open' );

			if ( this.classList.contains( 'open' ) ) {
				this.setAttribute( 'aria-expanded', true );
			} else {
				this.setAttribute( 'aria-expanded', false );
			}
		});
	}
})();