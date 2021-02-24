<?php
/**
 * EXAMPLE PARTIAL: Blog Feed
 * DBS>Interactive
 *
 * The content in the partial is surrounded by a div like this:
 *
 * 		<div class="layout partial-header-w-images {other_classes}">
 * 			..This code..
 * 		</div>
 */
 global $theme;
 ?>
 <section class="latest-news <?php // echo esc_attr( $news_container_class_list ); ?>">
	<h2 class="news__header">Latest News</h2>
	<div class="news__wrap">
		<?php
			$args = array(
				'showposts'	=> 4,
				'post_type'	=> 'post',
				'orderby'	=> 'publish_date',
				'order'		=> 'DESC',
				'post_status'   => 'publish'
			);
			$query = new \WP_Query( $args );
			$theme->view( 'views/posts/loop.php', array( 'query' => $query, 'wrap_link' => true ) );
		?>
	</div>
	<a class="button latest-news__button" href="/blog/">View All News</a>
</section>
