/**
 * A global cross-browser compatible event creator function.
 */
window.create_event = function(event_name) {
	if (typeof(Event) === "function") {
		var event = new Event(event_name);
	} else {
		var event = document.createEvent("Event");
		event.initEvent(event_name, true, true);
	}
	return event;
};

// An event that is triggered when jquery is loaded
window.jQueryLoaded = create_event("jQueryLoaded");
window.jQueryIsLoaded = false;

(function check_jquery() {
	if (typeof jQuery == "function") {
		window.dispatchEvent(jQueryLoaded);
		window.jQueryIsLoaded = true;
	} else {
		setTimeout(check_jquery, 150);
	}
})();

window.onJQueryLoad = function(fn) {
	if (window.jQueryIsLoaded) {
		fn();
	} else {
		window.addEventListener( "jQueryLoaded", fn );
	}
};
