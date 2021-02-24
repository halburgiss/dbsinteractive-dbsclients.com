<?php
/**
 * Repeater Field Template: Multiple Content Blocks with one BG image
 * This template is used to create a multi block CTA. Use acf
 * layout-flex-multiblocks if each block needs its own bg image.
 *
 * @author Michael Large 2016 - DBS>Interactive
 */

$layout = $this->layout;
?>
<div class="layout flex-blocks contain <?php $layout->the_layout_classes; ?>" <?php $layout->the_bg_image_srcset; ?>>
	<?php if ( $layout->has_title ) :  ?>
		<div class="flex-blocks__heading text-center">
			<?php $layout->the_title; ?>
		</div>
	<?php endif; ?>
	<?php if ( have_rows( 'flex_blocks_content' ) ) :  ?>
		<div class="flex-blocks__content">
			<?php while ( have_rows( 'flex_blocks_content' ) ) : the_row(); ?>
				<div class="cell">
					<?php the_sub_field( 'flex_block_content' );?>
				</div>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
</div>
