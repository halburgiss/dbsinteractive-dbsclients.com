<?php
/**
 * ACF Field Template: Default
 * This template is used to imitate the default page behavior.
 *
 * @author Michael Large 2016 - DBS>Interactive
 */

$layout = $this->layout;
?>
<div class="layout layout-default <?php $layout->the_layout_classes; ?>" <?php $layout->the_bg_image; ?>>
	<div class="contain">
		<?php $layout->the_content; ?>
	</div>
</div>
