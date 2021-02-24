/**
* @file lightbox.js
* Custom Lightbox JS
* @author Tyler Akin 2018-10-16
*
*	How To:
*		This following custom lightbox script provides a clean lightbox for
*			expanding images, including a resizing function to keep the lightbox
*			at the center of any screen size, as (I guess!) it should be.
*
*		Currently, the lightbox functionality is used in conjunction with
*			layout-image-gallery.php, which is a custom layout included in the
*			acf-flex subfolder located within the views folder.
*
*		Use:
*			This functionality is optional. In the Wordpress CMS, there is an
*				ACF special class entitled Light Box (.light-box is the
*				cooresponding class name).  If this class is added to an Image
*				Gallery layout, the following lightbox script will be enabled.
*
*			Note: When added, the .light-box class is added to the outermost
*				div of the Image Gallery.
*/
 // Lightbox Functionality
	$('.light-box').each(function(e) { // Checks to see if the class has been added in the CMS
		$(this).find('.image-gallery__image').on('click', function(){
			// Get the source of the img from the image clicked and set it in a variable
			var largeImage = $(this).find('img').attr("src");
 			// Adds a class to display the individual lightbox
			$(this).next('.individual-lightbox').addClass('opened-box');
 			// Set the next largeImage element within the lightbox to the src of the image clicked
			$('.opened-box .image-container').find(".largeImage").attr({src: largeImage});
 			// Strip any previously clicked lightboxes of their image src
			$('.individual-lightbox').not('.opened-box').find('.largeImage').attr({src:''});
		});
 		// A function to control the closing of the lightbox
		function closeBox(){
			$('.individual-lightbox').removeClass('opened-box');
		};
 		// Closes the lightbox on click
		$(".close-lightbox, .backDrop").on("click", function(){
			closeBox();
		});
	});
 	// Resizing Script
	if ($('.light-box').length > 0 ) {
		jQuery(window).on('resize',function() {
			var windowHeight = $(window).innerHeight();
			if (windowHeight < 500) {
				jQuery('.layout-image-gallery').find('.image-container').addClass('contain-size');
			} else if (windowHeight >= 500 && jQuery('.layout-image-gallery').find('.image-container').hasClass('contain-size')) {
				jQuery('.layout-image-gallery').find('.image-container').removeClass('contain-size');
			}
		});
	}
