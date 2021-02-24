<?php
/**
 * The template for displaying the header
 *
 * @package WordPress
 */
namespace Slate;
use \Base\MenuWalker;
use \Base\Theme;

global $utils;
global $theme;
global $dbs;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" itemscope itemtype="http://schema.org/WebPage">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="preconnect" href="https://www.google-analytics.com" crossorigin>
	<link rel="preconnect" href="https://www.googletagmanager.com">
	<link rel="preconnect" href="https://ajax.googleapis.com">
	<link rel="preconnect" href="https://signup.e2ma.net">
	<link rel="preconnect" href="https://cdn.jsdelivr.net">
	<link rel="preconnect" href="https://stats.g.doubleclick.net">

	<?php /* title tag inserted by Yoast SEO now 2017-09-19 */ ?>
	<?php if ( property_exists( $dbs, 'has_full_blog_features' ) && $dbs->has_full_blog_features ) { ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" >
	<?php } ?>
	<?php include_once( TEMPLATEPATH . '/views/partials/favicons.php' ); ?>
	<?php wp_head(); ?>
	<!--[if lte IE 9]>
		<script src="<?php echo get_stylesheet_directory_uri(); ?>/library/js/vendors/html5shiv/html5shiv<?php echo $theme->js_unique_hash; ?>.min.js"></script>
	<![endif]-->
	<script>
		// IE compatibility
		if ( /(MSIE|Trident\/|Edge\/)/i.test( navigator.userAgent ) ) {
			var ie_fixes_script = document.createElement( "script" );
			ie_fixes_script.type = "text/javascript";
			ie_fixes_script.src = "<?php echo get_stylesheet_directory_uri();  ?>/library/js/vendors/ie-fixes/ie-fixes<?php echo $theme->js_unique_hash;?>.min.js";
			document.head.appendChild( ie_fixes_script );
		}
		<?php require TEMPLATEPATH . '/library/js/jquery-load-event.js'; /* jquery onload handler */ ?>
		<?php require TEMPLATEPATH . '/library/js/dbs-global-vars.js'; /* site wide js variables */ ?>
		dbs.css_unique_hash = "<?php echo $theme->css_unique_hash; ?>";
		dbs.css_file_extension = "<?php echo $theme->css_file_extension; ?>";
		dbs.js_unique_hash = "<?php echo $theme->js_unique_hash; ?>";
		dbs.theme_directory = "<?php echo get_template_directory_uri(); ?>";

		dbs.static_url = "<?php echo DBS_STATIC_URL;?>"; dbs.siteurl = "<?php echo WP_SITEURL;?>";dbs.http_host = "<?php echo $_SERVER['HTTP_HOST'] ?>";
	<?php if ( ! IS_LIVE ) { ?>
		dbs.staging_domain = "<?php echo DBS_STAGING_DOMAIN ?>";
		dbs.debug = true;
	<?php } ?>
		document.getElementsByTagName("html")[0].className =
			  document.getElementsByTagName("html")[0].className
				  .replace(new RegExp('(?:^|\\s)'+ 'no-js' + '(?:\\s|$)'), ' ');
	</script>
	<?php

	if ( IS_LIVE ) {
		/* do NOT move this */
		require TEMPLATEPATH . '/library/googleanalytics.php';
	}

	require TEMPLATEPATH . '/library/schemas.php';?>

</head>

<body <?php body_class(); ?> itemscope="" itemtype="<?php echo $utils->get_schema_type();?>">
	<meta itemprop="name" content="<?php the_title();?>">

	<div class="site">
		<a class="assistive show-on-focus" href="#content"><?php _e( 'Skip to content', $theme->theme_name ); ?></a>

		<header class="site-header" itemscope itemtype="http://schema.org/Organization">
			<div class="site-header__container contain">
				<div class="site-header__branding">
					<a title="Home" href="/">
						<?php echo $utils->site_logo(); ?>
						<span class="assistive"><?php echo $dbs->client_name;?></span>
					</a>
				</div>

				<!-- Hamburger Icon -->
				<button class='menu-toggle' id='menu-toggle' title='Menu'>
					<span class='menu-toggle__bar'></span>
					<span class='menu-toggle__bar'></span>
					<span class='menu-toggle__bar'></span>
				</button>

				<div class="site-header__navigation" id="menu">
					<?php if ( has_nav_menu( 'secondary_menu' ) ) { ?>
						<nav class="secondary-menu" aria-labelledby="secondary-menu-heading" >
							<span id="secondary-menu-heading" class="assistive">Secondary Menu</span>
							<?php wp_nav_menu( array(
									'container' => false,
									'items_wrap' => '<ul class="menu" role="menu">' . "\n" . '%3$s' . "\n\t\t\t\t</ul>",
									'theme_location' => 'secondary_menu',
									'walker' => new MenuWalker( 'menu' ),
							) ); ?>

							<?php if (!$utils->is_amp() ) : // Only include wcag menu button on non-amp pages ?>
								<button id="wcag-button__open" class="wcag-button wcag-button__open not-mobile" aria-haspopup="true"><span class="assistive">Accessibility Options Button</span></button>
							<?php endif; ?>
						</nav>
					<?php } ?>

					<?php if (!$utils->is_amp() ) : // Only include wcag menu on non-amp pages
						include TEMPLATEPATH . '/views/partials/wcag-menu.php';
					endif; ?>
					<?php if ( has_nav_menu( 'main_menu' ) ) { ?>
						<nav class="main-menu" aria-labelledby="main-menu-heading" >
							<span id="main-menu-heading" class="assistive">Main Menu</span>
							<?php wp_nav_menu( array(
									'container' => false,
									'items_wrap' => '<ul class="menu" role="menu">' . "\n" . '%3$s' . "\n\t\t\t\t</ul>",
									'theme_location' => 'main_menu',
									'walker' => new MenuWalker( 'menu' ),
							) ); ?>
							<?php if ( $dbs->has_search ) { $theme->view( 'views/search/form.php' ); } ?>
						</nav>
					<?php } ?>
				</div>
			</div>
		</header>

		<main id="content" class="site-content">
			<?php if ( have_rows( 'schema_json-ld' ) ): ?>
				<?php while ( have_rows( 'schema_json-ld' ) ): the_row(); ?>
					<script type="application/ld+json">
						<?php the_sub_field( 'json-ld' ); ?>
					</script>
				<?php endwhile; ?>
			<?php endif; ?>
			<?php
			if ( function_exists( 'yoast_breadcrumb' ) && ! is_front_page() ) {
				// adds json-ld markup, in place of schema attrs. Recommended by Yoast as such. DO NOT CHANGE.
				yoast_breadcrumb('<div class="breadcrumbs-wrapper"><p id="breadcrumbs" class="breadcrumbs">','</p></div>' );
			}
