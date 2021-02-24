<?php
/**
 * File sidebar.php
 *
 * By default, we can have up to 2 sidebars: primary sidebar for site, or a blog
 * only sidebar. Add any other sidebars via functions.php
 *
 * @package WordPres
 * @subpackage dbsbone
 * @author @dbsdevelopers
 */

?>

<div id="sidebar" class="sidebar" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">
	<?php if ( 'post' === get_post_type() && ( is_single() || is_home() || is_archive() ) ) : ?>
	<?php
	dynamic_sidebar( 'sidebar-blog' ); ?>
	<nav>
		<div class="archive_list">
			<h4 class="widgettitle">Categories <span class="assistive">Categories</span></h4>
			<?php wp_list_categories( 'orderby=name&exclude=1&style=none' ); ?>
		</div>
	</nav>
<?php elseif ( is_active_sidebar( 'sidebar1' ) ) : ?>
	<?php dynamic_sidebar( 'sidebar1' );  // Main site sidebar. ?>
<?php else : ?>
	<?php
	// This content shows up if there are no widgets defined in the backend.
	// ?>
<?php endif; ?>
</div>
