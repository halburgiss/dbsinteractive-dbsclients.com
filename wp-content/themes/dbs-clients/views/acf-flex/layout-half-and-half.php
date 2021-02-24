<?php
/**
 * Repeater Field Template: Half and Half
 * This template is used to create a 50% 50% split template
 * with the option of having a background image and call to action button.
 *
 * NOTE: This template is 'contain'ed by default. To make it full width, add
 * the class 'contain-full-width' (in the cms).
 *
 * @author Michael Large 2016 - DBS>Interactive
 */

$layout = $this->layout;
$left_side_classes = $layout->get_left_classes;

if ( $layout->get_left_image || $layout->get_left_bg_image ) {
	$left_side_classes .= 'bg-img';
}

$right_side_classes = $layout->get_right_classes;

if ( $layout->get_right_image || $layout->get_right_bg_image ) {
	$right_side_classes .= 'bg-img';
}
?>
<div class="layout columns half-and-half contain <?php $layout->the_layout_classes; ?>">
	<div class="column half-and-half__left <?php echo $left_side_classes; ?>" <?php $layout->the_left_bg_image; ?>>
		<div class="image-wrapper">
			<?php $layout->the_left_image; ?>
		</div>
		<div class="cell">
			<?php $layout->the_left_content; ?>
		</div>
	</div>

	<div class="column half-and-half__right <?php echo $right_side_classes; ?>" <?php $layout->the_right_bg_image; ?>>
		<div class="image-wrapper">
			<?php $layout->the_right_image; ?>
		</div>
		<div class="cell">
			<?php $layout->the_right_content; ?>
		</div>
	</div>
</div>
