<?php
/**
 * Flex Field Slider
 * DBS>Interactive
 */

$layout = $this->layout;
wp_enqueue_script( 'flickity' );
?>

<div class="layout layout-slider contain <?php $layout->the_layout_classes; ?>">
	<?php if ( have_rows( 'slides' ) ) :  ?>
		<div class="slider">
			<?php while ( have_rows( 'slides' ) ) : the_row(); ?>
				<div class="slider-cell <?php if ($layout->has_image) {echo 'image-slide';} ?><?php $layout->the_slide_classes; ?>">
					<div class="slider-cell__image">
						<?php $layout->the_image; ?>
					</div>
					<div class="slider-cell__content">
						<?php $layout->the_content; ?>
					</div>
				</div>
			<?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<script>
window.addEventListener('load', function() {
	var elem = document.querySelector('.slider');
	var $flktySlider = new Flickity( elem, {
        cellAlign: "left",
		wrapAround: true,
    });
});
</script>
