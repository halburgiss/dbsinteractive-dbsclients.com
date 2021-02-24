<?php
/**
 * Partial theme File for Header Image
 *
 * @package slate
 */

global $utils;

$header_image = get_field( 'header_image' );
?>

	<section id="header-block" class="header block">
		<div class="blocks__full linkclicker"
			<?php echo $utils->get_background_image_srcset( $header_image['ID'], 'huge' ); ?>
		> <!-- end div -->
			<div class="overlay-text">
				<?php the_field( 'header_image_text' ); ?>
			</div>
		</div>
	</section>
