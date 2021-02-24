/*
* Plugin Name: Resize Image to Parent Container
*
* Author: Christian Varga
* Author URI: http://christianvarga.com
* Plugin Source: https://github.com/levymetal/jquery-resize-image-to-parent/
*
*/

(function($) {
	$.fn.resizeToParent = function(opts) {
		var defaults = {
			parent: 'div',
			contain: false,
			marginCenter: false,
			delay: 100
		};
		// console.log(opts.contain);

		var opts = $.extend(defaults, opts);

		function positionImage(obj) {
			// reset image (in case we're calling this a second time, for example on resize)
			//obj.css({'width': '', 'height': '', 'margin-left': '', 'margin-top': ''});
			if(opts.marginCenter){
				obj.css({'width': '', 'height': '', 'margin-left': '', 'margin-top': 0});
			}else{
				obj.css({'width': '', 'height': '', 'max-width': 'none'});
			}

			// dimensions of the parent
			var parentWidth = obj.parents(opts.parent).outerWidth() + 5; // To handle antialiasing
			var parentHeight = obj.parents(opts.parent).outerHeight() + 5; // To handle antialiasing

			// dimensions of the image
			var imageWidth = obj.width();
			var imageHeight = obj.height();

			// step 1 - calculate the percentage difference between image width and container width
			var diff = imageWidth / parentWidth;

			// step 2 - if height divided by difference is smaller than container height, resize by height. otherwise resize by width
			// console.log(opts.contain +' | '+ (imageHeight / diff) +' | '+ parentHeight);
			if ((imageHeight / diff) < parentHeight) {
				obj.css({'width': 'auto', 'height': parentHeight});

				// set image variables to new dimensions
				imageWidth = imageWidth / (imageHeight / parentHeight);
				imageHeight = parentHeight;
				// console.log('1');
				if( opts.contain ){
					obj.css({'height': 'auto', 'width': parentWidth});
				}
			}
			else {
				obj.css({'height': 'auto', 'width': parentWidth});

				// set image variables to new dimensions
				imageWidth = parentWidth;
				imageHeight = imageHeight / diff;
				// console.log('2');
				if( opts.contain ){
					obj.css({'width': 'auto', 'height': parentHeight});
				}
			}

			// step 3 - center image in container
			var leftOffset = (imageWidth - parentWidth) / -2;
			var topOffset = (imageHeight - parentHeight) / -2;

			//obj.css({'margin-left': leftOffset, 'margin-top': topOffset});
			if(opts.marginCenter){
				obj.css({'margin-left': leftOffset, 'margin-top': 0});
			}
		}

		// run the position function on window resize (to make it responsive)
		var tid;
		var elems = this;

		$(window).on('resize', function() {
			clearTimeout(tid);
			tid = setTimeout(function() {
				elems.each(function() {
					positionImage($(this));
				});
			}, opts.delay);
		});

		return this.each(function() {
			var obj = $(this);

			// hack to force ie to run the load function... ridiculous bug
			// http://stackoverflow.com/questions/7137737/ie9-problems-with-jquery-load-event-not-firing
			obj.attr("src", obj.attr("src"));

			// bind to load of image
//			obj.load(function() {  // update for jq 3.3 HB 2019-01-09
			obj.on( 'load', function() {
				positionImage(obj);
			});

			// run the position function if the image is cached
			if (this.complete) {
				positionImage(obj);
			}
		});
	};
})( jQuery );
