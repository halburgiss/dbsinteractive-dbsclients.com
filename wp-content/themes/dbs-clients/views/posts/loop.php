<?php
/**
 * Generic (blog) posts loop
 */

namespace Slate;
global $dbs, $theme;

// Checks to see if the required arguments were passed.
$this->expected_args( array(
	'query' => '',
	'wrapper' => 'div',
	)
);

$query = $this->query;

?>

<!-- Schema.org microdata :: NOTE: use png's or jpegs for image references -->
<div id="#Organization" class="assistive" itemscope itemtype="http://schema.org/Organization">
	<a tabindex="-1" itemprop="url" href="<?php echo $dbs->canonical_url?>"></a>
		<span itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">
			<meta itemprop="url" content="<?php echo get_template_directory_uri() . '/library/images/site-logo.png'; ?>" ><?php // DO NOT use svg here ?>
		</span>
</div>

<?php if ( $query->have_posts() ) { ?>
	<div class="blog-main" itemscope itemtype="http://schema.org/Blog">
		<meta itemscope itemprop="mainEntityOfPage"  itemType="https://schema.org/WebPage" itemid="<?php echo $dbs->canonical_url?>blog/">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<article class="post" itemprop="description">
				<div class="post__content linkclicker">
					<div class="post__image">
						<?php echo get_the_post_thumbnail($post = null, $size = 'large', array( 'class' => 'lazy-load' ) ); ?>
					</div>

					<h2 class="post__title h5">
						<a itemprop="url" href="<?php the_permalink();?>" class="circle-link filled"><?php the_title(); ?></a>
					</h2>
				</div>

				<div class="post__meta" style="width: 100%; margin-top: 1rem;">
					<span><?php echo get_the_date( 'm/d/Y' ); ?></span> |
					<?php $category = get_the_category(); ?>
					<a href="<?php echo get_category_link($category[0]->cat_ID); ?>"><?php echo $category[0]->cat_name; ?></a>
				</div>

				<span class="post__author">
					<span itemprop="author" itemscope itemtype="http://schema.org/Organization"><meta itemprop="name" content="<?php echo $dbs->client_name;?>"></span>
					<meta itemprop="datePublished" content="<?php the_date();?>">
					<span itemprop="image" itemscope itemtype="http://schema.org/ImageObject"><meta itemprop="url" content="<?php echo $dbs->canonical_url?>"/><meta itemprop="height" content=""/><meta itemprop="width" content=""/><meta itemprop="image" content="<?php echo $dbs->canonical_url?>"></span>
					<span itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
						<meta itemprop="name" content="<?php echo $dbs->client_name;?>">
						<span itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">
							<meta itemprop="url" content="<?php echo get_template_directory_uri() . '/library/images/site-logo.png'; ?>">
							<meta itemprop="height" content=""/><meta itemprop="width" content=""/>
						</span>
					</span>
				</span>
			</article>
		<?php
		endwhile; ?>
	</div>
	<div class="blog-sidebar layout-sidebar"><?php /**Starting Sidebar Div**/ ?>
		<?php get_sidebar(); ?>
	</div><?php /**Starting Sidebar Div**/ ?>
<?php wp_reset_postdata();

} else {

	if ( is_search() ) : ?>
		<p>Sorry, no results for ...' <?php echo esc_html( $_GET['s'] )?>'</p>
	<?php else : ?>
		<p>NO CONTENT</p>
	<?php endif;
}
