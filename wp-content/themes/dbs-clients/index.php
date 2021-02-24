<?php
/**
 * index.php, Blog / Posts default home
 */
namespace Slate;

if ( ! defined( 'ABSPATH' ) ) die( 'forbidden' );

get_header();

/**
 * Include the Post-Loop view for the content.
 * If you want to override the default posts pass it WP_query array.
 */

 // Header for searches and archives
if ( is_search() ) {
	$header = 'Search Results for ...' . esc_html( $_GET['s'] );
} elseif ( is_archive() ) {
	$header = get_the_archive_title();
} elseif ( is_home() ) {
	$header = 'Blog';
} else {
	$header = 'Latest ' . $dbs->posts_name;
}

?>

<div class="layout layout-page-header bg-dark">
	<div class="contain">
		<h1><?php echo $header; ?></h1>
	</div>
</div>

<div class="layout" style="margin-top: 1.5rem;">
	<div class="blog__container contain">
		<?php $theme->view( 'views/posts/loop.php', array( 'query' => $wp_query ) ); ?>
	</div>
</div>

<?php
$theme->view( 'views/pagination/pagination.php', array( 'query' => $wp_query ) );
get_footer();
