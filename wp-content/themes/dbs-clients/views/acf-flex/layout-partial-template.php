<?php
/**
 * Partial template for custom partials.
 * Assumes a file exists in /views/partials/cms-{partial_name}.php.
 * DBS>Interactive
 */

$layout = $this->layout;
?>

<div class="layout partial-<?php $layout->the_partial_name; ?> <?php $layout->the_layout_classes; ?>">
	<div class="contain" <?php $layout->the_bg_image_srcset; ?>>
		<?php $layout->get_the_partial(); ?>
	</div>
</div>
