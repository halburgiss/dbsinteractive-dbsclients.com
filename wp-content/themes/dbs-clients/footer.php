<?php
/**
 * The template to display the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Slate
 * @since Slate 0.1.0
 */

namespace Slate;

use \Base\MenuWalker;

global $theme;
global $utils;
global $dbs;

?>

</main><!-- .site-content -->

<footer class="site-footer bg-dark" itemscope itemtype="http://schema.org/WPFooter">
	<div class="site-footer__container contain">
		<div class="site-footer__content">
			<ul class="site-footer__business" itemscope itemtype="http://schema.org/Organization">
				<li>
					<a class="site-footer__logo" href="<?php echo get_home_url(); ?>">
						<span class="assistive" itemprop="name"><?php bloginfo( 'name' ); ?></span>
						<?php echo $utils->site_logo(); ?>
						<span class="assistive"><?php bloginfo(); ?></span>
					</a>
				</li>
				<li>
					<a class="site-footer__address" target="_blank"
						href="https://www.google.com/maps/place/517+S+4th+St,+Louisville,+KY+40202"
						itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<div class="streetAddress" itemprop="streetAddress">517 South 4th Street</div>
						<span itemprop="addressLocality">Louisville</span>,
						<span itemprop="addressRegion">KY</span>
						<span itemprop="postalCode">40202</span>
						<span class="assistive">(opens in new window)</span>
					</a>
				</li>
				<li class="site-footer__phone">
					<a class="telephone" href="tel:+18665627895" itemprop="telephone">(866) 562-7895</a>
				</li>
			</ul>

			<?php $theme->view( 'views/partials/social-icons.php' ); ?>
		</div>

		<?php if ( has_nav_menu( 'footer_menu' ) ) { ?>
			<nav class="site-footer__navigation">
				<?php wp_nav_menu( array(
						'container' => false,
						'items_wrap' => '<ul role="menu">' . "\n" . '%3$s' . "\n\t\t\t\t</ul>",
						'theme_location' => 'footer_menu',
						'walker' => new MenuWalker( 'footer-menu' ),
				) ); ?>
			</nav>
		<?php } ?>

		<div class="site-footer__credits" itemscope itemtype="http://schema.org/WebSite">
			<div>
				<span class="assistive" itemprop="description"><?php bloginfo( 'name' ); ?></span>
				<span class="copyright">&copy;</span>
				<span class="site-footer__copyright" itemprop="copyrightYear"><?php echo date( 'Y' ); ?></span>
				<span itemprop="copyrightHolder"><?php echo bloginfo( 'name' ); ?></span>

				<?php if ( has_nav_menu( 'legal_menu' ) ) { ?>
					<nav class="legal-footer">
						<?php wp_nav_menu( array(
								'container' => false,
								'items_wrap' => '<ul role="menu">' . "\n" . '%3$s' . "\n\t\t\t\t</ul>",
								'theme_location' => 'legal_menu',
								'walker' => new MenuWalker( 'legal-menu' ),
						) ); ?>
					</nav>
				<?php } ?>
			</div>

			<?php echo $utils->dbs_chevron(); ?>
		</div>
	</div>
		<script>
			<?php // make sure jquery is loaded now for third party stuff ?>
			if ( ! window.jQuery ) {
				document.write(  '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"><\/script>' );
				console.log( 'jq loaded sync' );
			}
		</script>
</footer><!-- .site-footer -->

<?php if ( ! $utils->is_amp() ) : // Cookie Popup js will not work on amp.
	$theme->view( 'views/partials/cookie-popup.php' );
endif; ?>

</div><!-- .site -->
<?php wp_footer(); ?>
<?php // make sure jquery is loaded b4 unveil (important if asyncing jQuery) ?>
<script>if ( dbs.device.is_phone ) jQuery( ".not-mobile" ).remove(); <?php // must run before ready event?></script>
<?php require TEMPLATEPATH . '/library/js/service-worker.php'; ?>
</body>
</html>
