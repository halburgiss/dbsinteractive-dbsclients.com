<?php

/**
 * Four Columns Flex Field Layout
 * DBS>Interactive
 */

$layout = $this->layout;
$col_classes = '';

if ( strpos( $layout->get_format, 'bg-img' ) !== false ) {
	$col_classes .= ' bg-img';
}
?>
<div class="layout columns four-columns contain <?php $layout->the_format; ?> <?php $layout->the_layout_classes; ?>">
	<div class="column <?php echo $col_classes . ' ' . $layout->the_col_1_classes; ?>">
		<?php if ($layout->has_col_1_image): ?>
			<div class="image-wrapper">
				<?php $layout->the_col_1_image; ?>
			</div>
		<?php endif; ?>
		<div class="cell">
			<?php $layout->the_col_1_content; ?>
		</div>
	</div>
	<div class="column <?php echo $col_classes . ' ' . $layout->the_col_2_classes; ?>">
		<?php if ($layout->has_col_2_image): ?>
			<div class="image-wrapper">
				<?php $layout->the_col_2_image; ?>
			</div>
		<?php endif; ?>
		<div class="cell">
			<?php $layout->the_col_2_content; ?>
		</div>
	</div>
	<div class="column <?php echo $col_classes . ' ' . $layout->the_col_3_classes; ?>">
		<?php if ($layout->has_col_3_image): ?>
			<div class="image-wrapper">
				<?php $layout->the_col_3_image; ?>
			</div>
		<?php endif; ?>
		<div class="cell">
			<?php $layout->the_col_3_content; ?>
		</div>
	</div>
	<div class="column <?php echo $col_classes . ' ' . $layout->the_col_4_classes; ?>">
		<?php if ($layout->has_col_4_image): ?>
			<div class="image-wrapper">
				<?php $layout->the_col_4_image; ?>
			</div>
		<?php endif; ?>
		<div class="cell">
			<?php $layout->the_col_4_content; ?>
		</div>
	</div>
</div>
