/**
 * Debounce function for rate limiting a function.
 *
 * It prevents a function from being fired too frequently. This is useful
 * for resource intensive processes like resize event handlers and tap/click
 * handlers.
 *
 *
 * @see: https://davidwalsh.name/javascript-debounce-function
 *
 * @param function func - the callback function
 * @param int wait - the time to wait in milliseconds
 * @param bool immediate - call the function immediately
 *
 * @return function - the modified function that will only run once within a wait period.
 */
function debounce( func, wait, immediate ) {
	var timeout;
	if ( ! wait ) { console.warn( "Debounce was called without a 'wait' argument. This renders debounce almost completely ineffective." ); }
	return function() {
		var context = this, args = arguments;
		var later = function() {
			timeout = null;
		};

		var callNow = immediate && !timeout;
		clearTimeout( timeout );
		timeout = setTimeout( later, wait );
		if ( callNow ) { func.apply( context, args ); }
	};
};


/**
 * Debounce scroll event for rate limiting scroll events.
 *
 * Putting events on the scroll handler should usually be discouraged
 * as they can be fired very frequently and this can cause the browser
 * to lag.
 *
 * Instead, use this event: debounceScroll
 *
 * This allows us to have only one scoll event handler that triggers
 * the debounceScoll event (on the window) 50ms (or whatever the
 * delay_time is set) after a scroll event starts and then stops.
 *
 *
 * @see: http://ejohn.org/blog/learning-from-twitter/#postcomment
 * (particularly read the comments for this variant)
 *
 * @usage:
 *
 *  	window.addEventListener( 'debounceScroll', function() {
 *  		console.log( 'scrolled....');
 *  	}, false );
 */
var debounceScroll = create_event( 'debounceScroll' );

window.addEventListener( 'load', function() {
	var delay_time = 50;
    var delay_start = 0;
    var last_scroll_pos = 0;

    function scroll() {
        // If we are already scrolling, reset the delay
        if ( delay_start ) { clearTimeout( delay_start ); }

        // Are we scrolling down?
        var scrollDown = $( window ).scrollTop() > last_scroll_pos;
        last_scroll_pos = $( window ).scrollTop(); // Save for next time

        delay_start = setTimeout( function( event ) {
            debounceScroll.scrollDown = scrollDown;
            window.dispatchEvent( debounceScroll );
        }, delay_time );
    }

    // For momentum-scrolling touch devices that only trigger a scroll event on scroll-end
    if ('touchmove' in document.documentElement) {
        window.addEventListener('touchmove', function() {
            scroll();
        });
    } else {
        // Non-touch devices
        window.addEventListener('scroll', function() {
            scroll();
        });
    }
});
