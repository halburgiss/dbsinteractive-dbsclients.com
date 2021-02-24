<?php
/**
 * Default single post template
 *
 */

namespace Slate;
use \Base\ShareCount;

// featured image might exist or might not.
$post_thumbnail_id = get_post_thumbnail_id();

get_header();

if ( have_posts() ) :  while ( have_posts() ) :  the_post(); ?>

	<!-- Schema.org microdata :: NOTE: use png's or jpegs for image references -->
	<div id="#Organization" class="assistive" itemscope itemtype="http://schema.org/Organization">
		<meta itemprop="name" content="<?php echo $dbs->client_name?>"/>
		<a itemprop="url" href="<?php echo $dbs->canonical_url?>"></a>
		<span itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">
			<meta itemprop="url" content="<?php echo get_template_directory_uri() . '/library/images/site-logo.png'; ?>" ><?php // DO NOT use svg here ?>
		</span>
	</div>

	<article class="blog-post single post" itemscope itemtype="http://schema.org/BlogPosting" <?php // use NewsArticle if we call it News ?> itemref="#Organization">
		<meta itemscope itemprop="mainEntityOfPage"  url="<?php echo get_permalink(); ?>"/>
		<div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
			<meta content="<?php echo get_template_directory_uri() . '/library/images/site-logo.png'; ?>" itemprop="url">
		</div>

		<header class="blog-post__header bg-dark" data-bg-srcset="<?php
			if ( $post_thumbnail_id ) {
				echo wp_get_attachment_image_srcset( $post_thumbnail_id );
			}
			?>">

			<h1 itemprop="name headline"><?php the_title(); ?></h1>
			<time class="publication-date" datetime="<?php the_modified_date( 'c' ); ?>">
				<span itemprop="datePublished" content="<?php the_time( 'c' ); ?>"><?php the_time( 'F j, Y' ); ?></span>
				<?php if ( get_the_time( 'c' ) < get_the_modified_date( 'c' ) ) { ?>
					<span class="assistive" itemprop="dateModified" content="<?php the_modified_date( 'c' ); ?>"><?php the_modified_date( 'F j, Y' ); ?></span>
				<?php } ?>
			</time>
			<span class="blog-post__author assistive">
				<span itemprop="author" itemscope itemtype="http://schema.org/Organization">
					<span itemprop="name"><?php echo $dbs->client_name;?></span>
				</span>
				<span itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
					<span itemprop="name"><?php echo $dbs->client_name;?></span>
					<span itemprop="logo" itemscope itemtype="http://schema.org/ImageObject">
						<meta itemprop="url" content="<?php echo get_template_directory_uri() . '/library/images/site-logo.png'; ?>">
						<meta itemprop="height" content=""/><meta itemprop="width" content=""/>
					</span>
				</span>
			</span>

			<?php $page_for_posts_link = get_permalink( get_option( 'page_for_posts' ) ); ?>
			<?php $page_for_posts_title = get_the_title( get_option( 'page_for_posts' ) ); ?>
			<a class="button--inline back" href="<?php
				if ( $page_for_posts_link ) {
					echo $page_for_posts_link;
				} ?>"
			>
				<?php if ( $page_for_posts_title ) {
						echo 'Back to Our ' . $page_for_posts_title . ' Page';
					} else { echo 'Back to Our Posts Page'; }
				?>
			</a>

			<?php if ( $post_thumbnail_id ) : ?>
				<span class="assistive" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
				<meta itemprop="url" content="<?php echo get_the_post_thumbnail_url( null, 'thumbnail' ); ?>"/>
					<meta itemprop="height" content="150"/>
					<meta itemprop="width" content="150"/>
					<img itemprop="image" src="<?php echo get_the_post_thumbnail_url( null, 'thumbnail' ); ?>" alt="thumbnail">
				</span>
			<?php endif; ?>
		</header>

		<div class="layout" style="margin-top: 2rem;">
			<div class="blog-post__container contain">
				<div class="blog-main" itemprop="ArticleBody">
					<?php the_content(); ?>

					<footer class="blog-post__footer">
						<div class="blog-post__navigation">
							<?php
								$prev = get_permalink( get_adjacent_post( false, '', false ) );
								$next = get_permalink( get_adjacent_post( false, '', true ) );
								$current = get_permalink( $post->ID );
								// check if we are at the very beginning or end
								$disabled_next = $disabled_prev = false;
								if ( $prev === $current ) :
									$prev = 'javascript:void(0)';
									$disabled_prev = true;
								endif;
								if ( $next === $current ) :
									$next = 'javascript:void(0)';
									$disabled_next = true;
								endif;
							?>
							<a class="button--inline back<?php if ( $disabled_prev ) echo ' nowhere'?>" href="<?php echo $prev; ?>">Previous Post</a>
							<a class="button--inline<?php if ( $disabled_next ) echo ' nowhere'?>" href="<?php echo $next; ?>">Next Post</a>
						</div>
					</footer>
				</div>

				<div class="blog-sidebar layout-sidebar">
					<?php get_sidebar(); ?>
				</div>
			</div>
		</div>

	</article>

<?php endwhile;
endif;

get_sidebar();
get_footer();
