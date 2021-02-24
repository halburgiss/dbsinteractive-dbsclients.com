<?php
/**
 * Displays Social Media link and icon if it is set in the options page.
 *
 * @author Michael Large - DBS Interactive
 */
global $utils;

// If there are no social media links, there's nothing to do
if ( ! have_rows( 'social_media_links', 'option' ) ) { return; }
?>
<ul class="social-media" itemscope itemtype="http://schema.org/Organization">
	<li class="social-media__origin">
		<h2 class="assistive">
			<span itemprop="name" class="social-media__origin" ><?php echo get_bloginfo( 'name' ); ?></span>
			<span class="assistive" >Social Media Links</span>
		</h2>
		<link itemprop="url" href="/">
	</li>

<?php
/**
 * Loop through the social media links
 */
while ( have_rows( 'social_media_links', 'option' ) ) : the_row();
	$type = get_sub_field( 'type' ); ?>
		<li class="social-media__link">
			<a itemprop="sameAs" href="<?php the_sub_field( 'url' ); ?>" target="_BLANK">
				<span class="twitter">
				<?php echo $utils->loadSVG( TEMPLATEPATH . '/library/icons/src/' . $type . '.svg' ); ?>
				</span>
				<span class="assistive" style="color: #6a2f99;"><?php echo $type . "(opens in new window)"; ?></span>
			</a>
		</li>
<?php
endwhile; ?>

</ul>
