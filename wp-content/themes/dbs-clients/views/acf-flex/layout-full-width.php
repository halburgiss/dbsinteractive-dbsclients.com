<?php
/**
 * Repeater Field Template: Full Width
 * This template is used to make a full width section.
 * This also includes the option for a colored image background.
 *
 * @author Mark Shelton 2016 - DBS>Interactive
 */

global $theme;
$layout = $this->layout;
$classes = $layout->get_layout_classes;

if ( $layout->get_image || $layout->get_bg_image ) {
	$classes .= 'bg-img';
}
?>
<div class="layout layout-full-width <?php echo $classes; ?>" <?php $layout->the_bg_image; ?>>
	<div class="image-wrapper">
		<?php $layout->the_image; ?>
	</div>
	<div class="contain">
		<?php $layout->the_content; ?>
	</div>
</div>
