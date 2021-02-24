/**
* This file is loaded asyncronously in the footer.
* All custom global scripts should be included here.
*/

	/*
	A little info for this js file:

	As much as possible, this file should be kept organized. Currently, two major divisions are seen below, delinitated by their respective functions of "on load" and "ready".
		As mentioned below, most code added to this file should be included within the "on load" function.

	For ease of future use, include any new code at the bottom of the relevant section of the page, adding clear comments to explain the purpose of the code being written,
		including any relavant details regarding what the code overrides, etc.
	*/


(function(){
	'use strict';

	// rerun lazyload on screen size updates
	window.addEventListener( 'resize', function() {
		dbs.lazyload();
	});

	// Load event function ... most custom stuff goes here. Safer for async.
	window.addEventListener( 'load', function() {
		/* Search Form functionality
		 * The click functionality involves three different actions,
		 * depending on conditions the various clicks provide.
		 * 
		 * NOTE: This script assumes there is one search form on the page.
		 */
		var searchOpen = document.querySelector( '.search-form__button--open' );
		var searchClose = document.querySelector( '.search-form__button--close' );
		var searchForm = document.getElementById( 'search-form' );
		var formContainer = document.querySelector( '.search-form__container' );

		if ( searchForm ) {
			searchOpen.addEventListener( 'click', function() {
				searchForm.classList.add( 'search-form--active' );
	
				// Hide the search bar after click out of it
				document.getElementById( 'content' ).addEventListener( 'click', function() {
					if ( searchForm.classList.contains( 'search-form--active' ) ) {
						searchForm.classList.remove( 'search-form--active' );
						formContainer.removeChild( document.querySelector( '.search_close' ) );
					}
				});
			});
	
			searchClose.addEventListener( 'click', function() {
				searchForm.classList.remove( 'search-form--active' );
			});
		}


		/**
		 * Animated Form Labels
		 * 
		 * CSS class must be added in GForms admin on each field that will need to animate.
		 */
		var labelFields = document.querySelectorAll( '.animate-label' );

		if ( labelFields ) {
			for ( var i = 0; labelFields.length > i; i++ ) {
				var field = labelFields[i];
				var input = field.querySelector( 'input' );
				
				input.addEventListener( 'focus', function() {
					this.parentElement.parentElement.classList.add( 'active' );
				});

				input.addEventListener( 'blur', function() {
					var $this = this;
					
					setTimeout(function() {
						if ( $this.value == '' ) {
							$this.parentElement.parentElement.classList.remove( 'active' );
						}
					}, 100);
				});
			}
		}


		/**
		 * Linkclicker
		 * 
		 * A class placed on a wrapping element with a link inside to make the wrapper clickable.
		 */
		var linkclickers = document.querySelectorAll('.linkclicker');

		for ( var i = 0; linkclickers.length > i; i++ ) {
			var linkclicker = linkclickers[i];

			linkclicker.addEventListener('click', function(e) {
				e.preventDefault();
				var link = this.querySelector('a');
				var linkPath = link.getAttribute('href');
				
				if ( link.getAttribute('target', "_blank") ) {
					window.open(linkPath);
				} else {
					window.location.href = linkPath;
				}
			});
		}


		/**
		 * Force external links/PDFs to open in a new tab.
		 * From https://stackoverflow.com/questions/2910946/test-if-links-are-external-with-jquery-javascript
		 * 2019-07-11 - TA
		 */
		function link_is_external(link_element) {
			return ( link_element.host !== window.location.host || link_element.href.indexOf("pdf") > -1 );
		}

		var anchorElements = document.getElementsByTagName('a');
		for ( let anchorElement of anchorElements ) {

			if ( link_is_external( anchorElement ) ) {
				anchorElement.setAttribute( 'target','_blank' );
				anchorElement.setAttribute( 'rel','noopener' );

				// Add assistive text to alert of external links
				var assistiveText = '<span class="assistive">. External Link. Opens in new window.</span>';
				var assistiveTextContainer = document.createElement( 'span' );
				assistiveTextContainer.innerHTML = assistiveText;
				anchorElement.appendChild( assistiveTextContainer.firstChild );
			}
		}
	}); // End of load function
	

	// Include ready events here
	window.addEventListener( 'DOMContentLoaded', function() {
		
	});


	/**
	 * Js for detecting a modals id and opening that modal
	 */
	var modalToggles = document.querySelectorAll( '.modal__open' );
	for ( var i = 0; modalToggles.length > i; i++ ) {
		modalToggles[i].addEventListener( 'click', function(e) {
			var relativeModal = this.getAttribute( 'href' );
			e.preventDefault();
			document.getElementById( '#' + relativeModal ).classList.remove('hide');
		});
	}


	/**
	 * Cookie Popup scripts
	 */
	var popUp		= document.getElementById('cookie-popup');
	var popUpMain	= document.getElementById('popup-main');

	// Hide popup and background filter and set cookie
	function accept() {
		popUp.style.display = "none";
		document.body.classList.add('cookies');
		setCookie( "accepts-cookie", "accepts-cookie-set", 365 );
	}

	document.getElementById('accept-button').addEventListener( 'click', function() {
		accept();
	});

	var cookie = readCookie( 'accepts-cookie' );
	// If user already has accepted cookies, don't show popup
	if ( cookie == 'accepts-cookie-set' ) {
		popUp.style.display = "none";
	} else {
		// Force screen reader to read popup
		popUpMain.setAttribute("aria-live", "assertive");
	}
})();
