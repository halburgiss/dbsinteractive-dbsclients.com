<?php
/**
 * Image Gallery (with optional text callout)
 *
 * @author Tyler Akin 2018-10-16 - DBS > Interactive
 *
 * Stylesheet: scss/layout/_layout-image-grid.scss
 *
 *		Use:
 *			Out of the box, this layout provides an image gallery for up to
 *			six images (the recommended number, since the gallery will not
 *			resize the image widths based on less images.).
 *
 * 			Possible variants in use:
 *				Light Box (.light-box)
 *					Add the ACF Special Class "Light Box"
 *					A class of (.light-box) is added to the parent div of the
 *					Image gallery, which enables the lightbox script for
 *					images (See lightbox/lightbox.js for the functionality)
 *
 *				Image Zoom
 *					Add the ACF Special Class "Image Zoom"
 *					A class of (.image-zoom) is added to the parent div of the
 *					Image gallery, which enables the simple css transform(scale)
 *					declaration (See the stylesheet listed above).
 */
global $utils;
$layout = $this->layout;
$image_gallery_callout = get_sub_field( 'image_gallery_callout' );
// Enque the script here, so that the js is only loaded when being used
wp_enqueue_script( 'lightbox', get_stylesheet_directory_uri() . '/library/js/vendors/lightbox/lightbox' . $this->js_unique_hash . '.min.js', array(), null, true );
?>

<div class="layout image-gallery light-box <?php $layout->the_layout_classes; ?>">
	<div class="contain">
		<div class="image-gallery__wrapper">
			<?php if ($image_gallery_callout) : ?>
				<div class="image-gallery__column image-gallery__content">
					<div class="inner">
						<?php echo $image_gallery_callout; ?>
					</div>
				</div>
			<?php endif; ?>
			
			<div class="image-gallery__column image-gallery__images">
				<?php $images = get_sub_field('gallery'); ?>
				<?php foreach ($images as $image) : ?>
					<button class="image-gallery__image">
						<?php echo $utils->get_image_with_srcset( $image['id'], false ); ?>
					</button>
					<div class="individual-lightbox">
						<div class="backDrop"></div>
						<div class="image-container">
							<button class="close-lightbox"><span class="assistive">Close</span></button>
							<img class="largeImage" src="" alt="<?php echo $image['alt']; ?>"/>
							<?php $caption = wp_get_attachment_caption($image['id']);
							if ($caption) { ?>
								<span class="lightbox-caption"><?php echo $caption; ?></span>
							<?php } ?>
						</div>

						<div class="image-gallery__nav">
							<button class="image-gallery__button prev"><span class="assistive">Previous Image</span></button>
							<button class="image-gallery__button next"><span class="assistive">Next Image</span></button>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
