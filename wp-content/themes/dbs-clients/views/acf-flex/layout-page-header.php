<?php
/**
 * Page Header layout
 * DBS>Interactive
 */

$layout = $this->layout;
$classes = $layout->get_layout_classes;
if ( $layout->get_image || $layout->get_bg_image ) {
	$classes .= 'bg-img';
}
?>
<div class="layout layout-page-header <?php echo $classes; ?>" <?php $layout->the_bg_image; ?>>
	<div class="image-wrapper">
		<?php $layout->the_image; ?>
	</div>
	<div class="contain">
		<?php $layout->the_content; ?>
	</div>
</div>
