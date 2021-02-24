<?php
/**
 * page-sitemap.php, HTML sitemap template.
 */

namespace Slate;
global $dbs, $wpdb, $post;

get_header();

// 2014-11-12 HB ... lets handle the exclusions better.
// get password proteced pages first.
$protected = $wpdb->get_col( 'SELECT ID ' .
	"FROM $wpdb->posts " .
	"WHERE post_status = 'publish' " .
	"AND post_password != '' ORDER BY post_title"  );

// merge id's we know we don't need like Thank You pages, with the protected pages.
$excluded_pages = array_merge( $protected, array( $post->ID ) );

// now spit it out.
$top_level_pages = get_pages(
	array(
		'parent' => 0,
		'sort_column' => 'menu_order',
		'sort_order' => 'asc',
		'exclude' => $excluded_pages
	)
); // sort order and exclude pages

/**
 * Build a sitemap based on top level pages and loop through sub pages
 * Also allow for exclusion conditions
 */
function build_sitemap_sub_pages( $page_ID, $excluded_pages ) {
	/*
	 * Set a boolean to triggger an event specific to a condition
	 * This allows for stepping outside the loop temporarily
	 * Also is automatically reset by top level page hit.
	 */
	$exclude_condition = false; // Triggered by values below to manually exclude_condition

	$this_page = get_post( $page_ID );

	/*
	 * Exclude all pages with thank-you as part of the slug, to prevent further thank-you pages from being added
	 */
	if ( strpos( $this_page->post_name, 'thank-you' ) !== false ) {
		$exclude_condition = true;
	}

	if ( strpos( $this_page->post_name, 'sitemap' ) !== false || strpos( $this_page->post_name, 'site-map' ) !== false  ) {
		$exclude_condition = true;
	}

	/**
	 * Exclude WP_SEO noindex pages
	 *
	 * Assumes Yoast returns an array of one item.
	 */
	if ( get_post_meta( $page_ID, '_yoast_wpseo_meta-robots-noindex' ) &&
		get_post_meta( $page_ID, '_yoast_wpseo_meta-robots-noindex' )[0] === '1' ) {
		$exclude_condition = true;
	}

	if ( ! $exclude_condition ) {
		/*
		 * Are we a top level or sub level page?
		 */
		if ( $this_page->post_parent ) {
			echo '<li class="sub-page"><a href="'.get_page_link( $page_ID ).'">' . get_the_title( $page_ID ) . '</a>';
		} else {
			echo '<li class="top-page"><a href="'.get_page_link( $page_ID ).'">' . get_the_title( $page_ID ) . '</a>';
		}
			// Make sure we have more pages here
		if ( count( get_children( $page_ID ) ) > 0 ) {
			$get_sub_pages = get_pages( array( 'parent' => $page_ID, 'sort_column' => 'title', 'sort_order' => 'asc', 'exclude' => $excluded_pages ) );
			if ( count( $get_sub_pages ) > 0 ) {
				echo '<ul class="sub-menu">';
				foreach ( $get_sub_pages as $sub_page ) {
					build_sitemap_sub_pages( $sub_page->ID, $excluded_pages );
				}
				echo '</ul>';
			}
		}
		/*
		// 2020-12-17 CPT custom post type example
		if ( get_the_title( $page_ID ) === 'Portfolio' ) :
			 $args = array(
		        'post_type' => 'portfolio',
				'posts_per_page' => -1,
				'post_status' => 'publish'
	    	);
			$query = new \WP_Query($args);
			if ($query->have_posts() ) : 
			    echo '<ul>';
			    while ( $query->have_posts() ) : $query->the_post();
            		echo '<li><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></li>';
			    endwhile;
			    echo '</ul>';
			endif;
		    wp_reset_postdata();
		endif;
		*/
		echo '</li>';
	}
}

/** Generate sitemap here */
?>
<div class="layout layout-page-header bg-dark">
	<div class="contain contain-narrow">
		<h1>Site Map</h1>
	</div>
</div>
<div class="layout layout-default contain">
	<div class="contain contain-narrow">
		<ul id="sitemap" class="sitemap">
		<?php foreach ( $top_level_pages as $page ) :
			build_sitemap_sub_pages( $page->ID, $excluded_pages );
		endforeach; ?>
		</ul>
	</div>
</div>

<?php
get_footer();
