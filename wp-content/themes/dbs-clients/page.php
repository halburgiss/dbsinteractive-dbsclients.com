<?php
/**
 * @file page.php, the default page template
 */

namespace Slate;
global $utils;
get_header();

// Get page-specific microdata
$microdata = '';
$uri = get_page_uri();
if ( 'contact' === $uri || 'contact-us' === $uri ) {
	$microdata = 'itemscope itemtype="https://schema.org/ContactPage"';
}

if ( have_posts() ) :  while ( have_posts() ) :  the_post();
	if ( post_password_required() ) :  // we need this for any/all custom field content for pw protected pages.
		?>
		<div class="layout layout-default">
			<div class="contain contain-narrow">
				<p><?php echo get_the_password_form(); ?></p>
			</div>
		</div>
	<?php else : ?>
		<div class="layout layout-page-header bg-dark" style="margin-top: 0;">
			<div class="contain contain-narrow">
				<h1><?php the_title(); ?></h1>
			</div>
		</div>
		<div class="layout layout-default" <?php echo $microdata; ?>>
			<div class="contain contain-narrow">
				<?php the_content(); ?>
			</div>
		</div>
	<?php endif;

// End of loop
endwhile; endif;

get_footer();
