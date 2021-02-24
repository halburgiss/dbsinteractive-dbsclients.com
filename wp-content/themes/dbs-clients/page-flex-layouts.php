<?php
/**
 * Template Name: Flex Layout
 *
 * @file page-flex-layouts.php, the base flex template
 */

namespace Slate;
global $utils;
get_header();

if ( post_password_required() ) :  // we need this for any/all custom field content for pw protected pages.
	?>
	<div class="layout layout-page-header bg-dark">
		<div class="contain contain-narrow">
			<h1>Please Login</h1>
		</div>
	</div>
	<div class="layout layout-default">
		<div class="contain contain-narrow">
			<p><?php echo get_the_password_form(); ?></p>
		</div>
	</div>
<?php else :
	if ( have_rows( 'flex_content' ) ):
		$theme->view( 'views/acf-flex/loop.php', array( 'query' => $wp_query ) );
	endif;
endif;

get_sidebar();
get_footer();
