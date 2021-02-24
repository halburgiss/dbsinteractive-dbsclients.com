<?php
/**
 * ACF Field Template: Content With Sidebar
 * This template is used to imitate the default page behavior.
 *
 * @author Tyler Akin 2017 - DBS>Interactive
 */

global $theme;

$layout = $this->layout;

$layout_classes = $layout->get_layout_classes;

if ( is_active_sidebar( 'sidebar1' ) ) {
	$layout_classes = ' active-sidebar';
}

?>

<div class="layout layout-content-with-sidebar <?php echo $layout_classes; ?>">
	<div class="contain">
		<?php if ( is_active_sidebar( 'sidebar1' ) ) : ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>

		<div class="content">
			<?php $theme->view( 'views/acf-flex/loop.php', array( 'prefix' => 'content_with_sidebar_' ) ); ?>
		</div>
	</div>
</div>