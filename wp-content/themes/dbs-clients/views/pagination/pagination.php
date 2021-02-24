<?php
/**
 * Slate Pagination
 *
 * This is a replacement for the WordPress function the_posts_pagination(). It
 * allows for more customization.
 *
 * @usage:
 * 	$theme->view( 'views/pagination/pagination.php', array( 'query' => $wp_query ) )
 *
 */

// $query must be set
$this->expected_args( array( 'query' => '' ) );
$query = $this->query;

// Don't bother if there is only one page
if ( $query->max_num_pages <= 1 ) { return; }

$links = paginate_links( array(
	'prev_text' => 'Previous<span class="assistive"> page</span>',
	'total' => $query->max_num_pages,
	'next_text' => 'Next<span class="assistive"> page</span>',
	'before_page_number' => '<span class="assistive">Page </span>',
) );
// Replace WordPress default classes with BEM classes and add appropriate attributes
// NOTE: Use of single quotes and double quotes is intentional for proper matches,
// because WordPress is inconsistent with single/double quotes!
$links = str_replace(
	'class="prev page-numbers"',
	'class="pagination__link pagination__link--prev" rel="prev" itemprop="url"',
	$links );

$links = str_replace(
	'class="next page-numbers"',
	'class="pagination__link pagination__link--next" rel="next" itemprop="url"',
	$links );

$links = str_replace(
	'class="page-numbers"',
	'class="pagination__link" itemprop="url"',
	$links );

$links = str_replace( 'class="page-numbers current"', 'class="pagination__current"', $links );

$links = str_replace( 'class="page-numbers dots"', 'class="pagination__more"', $links );
?>

<nav class="pagination" itemscope itemtype="http://schema.org/SiteNavigationElement" aria-labelledby="paginglabel">
	<h2 class="assistive" id="paginglabel">Posts Pagination</h2>
	<?php echo $links; ?>
</nav>
