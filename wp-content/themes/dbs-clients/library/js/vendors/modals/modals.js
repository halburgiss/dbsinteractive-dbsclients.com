/**
 * Modal object for creating modals.
 *
 * @usage:
 *
 * 	var myModal = new Modal( element, { 'open' : true, 'onOpen' : myFunction );
 *
 * 	Assumes html like:
 *
 *  	<div class="modal">
 *  		<!-- a 'modal' can have more than one 'modal__box'es. -->
 *  		<div class="modal__box" >
 *  			<a href="#close" title="Close" class="modal__close_btn">Close</a>
 *  			<h2>A header</h2>
 *  			<p>A bunch of random text here. A bunch of random text here. And some more!</p>
 *  			<a href="#close" title="Close" class="modal__button" data-action="close">Continue</a>
 *  		</div>
 *  	</div>
 *
 *
 * @param element - DOM element that is the container for the modal
 * @param object - object containing optional attributes using this format:
 * 		{
 *
 * 			// When set, opens modal at creation.
 * 			'open' : false,
 *
 * 			// When set, clicking outside a modal box closes the modal.
 * 			'close_on_click_away' : true,
 *
 * 			// When set, displays only once (based on a cookie).
 * 			// TODO: not yet implemented
 * 			'show_once' : false,
 *
 * 			// Function to run when opening modal
 * 			'onOpen'  : function() {},
 *
 * 			// Function to run when closing modal
 * 			'onClose' : function() {},
 *
 * 		}
 *
 * @author HGB3 12/12/2016
 */
function Modal( element, atts ) {

	// First try to use atts passed as an arg, if none were passed, try global modalAttributes.
	// Otherwise, just use an empty object
	if ( ! atts ) {
		if ( typeof modalAttributes !== 'undefined' ) {
			atts = modalAttributes;
		} else {
			atts = {};
		}
	}

	var attributes = {  // If available, use passed attributes, otherwise use defaults
		'open' : 				atts.open !== undefined ? atts.open : false,
		'close_on_click_away' : atts.close_on_click_away !== undefined ? atts.close_on_click_away : true,
		'show_once' : 			atts.show_once !== undefined ? atts.show_once : false,
		'onOpen' : 				atts.onOpen || function() {},
		'onClose' : 			atts.onClose || function() {},
	};

	if ( ! element ) {  // Sanity check
		console.warn( 'Cannot create Modal without modal element.' );
		return;
	}

	this.element = element;

	this.name = jQuery( this.element ).data( 'name' );

	// Store a reference to the modal object on the element.
	this.element.modal = this;


	// Event Handlers

	var modal = this;  // Closure on which to attach 'this'
	var close = function() { modal.close(); }

	jQuery( this.element )
		.find( '.modal__close_button' ).on( 'click', close );

	// For all modal buttons, set up event handler for action data attribute.
	jQuery( '.modal__button' ).each(
		function( i, button ) {
			var action = jQuery( button ).data( 'action' );
			if ( action && modal[action] ) {
				jQuery( button ).on( 'click', function( event ) {
					modal[action]();
					if ( event ) {
						event.preventDefault();
					}
			   	});
			}
		});

	if ( attributes.close_on_click_away ) {
		// Close when clicking the element (the area around the modal)...
		jQuery( this.element ).on( 'click', close );

		// ...but don't allow click events to bubble up past modal boxes.
		// They should handle their own events.
		jQuery( this.element )
			.find( '.modal__box').on( 'click', function( event ) {
				event.stopPropagation();
			});
	}

	// onOpen event handler
	this.onOpen = attributes.onOpen;

	// onClose event handler
	this.onClose = attributes.onClose;


	if ( attributes.open ) { this.open(); }
}

/**
 * Hides the modal
 *
 * Adds hide class and removes show class on modal container element.
 * Animation should be done in stylesheets.
 */
Modal.prototype.hide = function() {
	jQuery(this.element)
		.addClass('hide')
		.removeClass('show');
}

/**
 * Shows the modal
 *
 * Adds show class and removes hide class on modal container element.
 * Animation should be done in stylesheets.
 */
Modal.prototype.show = function() {
	jQuery(this.element)
		.addClass('show')
		.removeClass('hide');
}

/**
 * Opens the modal
 * Also runs onOpen event handler.
 */
Modal.prototype.open = function() {
	this.show();
	this.onOpen();
}

/**
 * Closes the modal
 * Also runs onClose event handler.
 */
Modal.prototype.close = function() {
	this.hide();
	this.onClose();
}

/**
 * Automatically creates a modal object for every element that has a class of
 * 'modal'.
 */
jQuery( window ).on( 'load', function() {

	// Store them in this array
	document.modals = [];

	jQuery( '.modal' ).each( function( i, modal_element ) {
		var name = jQuery( modal_element ).data( 'name' );
		var attributes = jQuery( modal_element ).data( 'attributes' );
		console.log(modal_element);
		if ( name ) {
			var atts = ( attributes ) ? dbs.modals[attributes] : none;
			document.modals[name] = new Modal( modal_element, atts );
		} else {
			document.modals.push( new Modal( modal_element ) );
		}
	});

});
