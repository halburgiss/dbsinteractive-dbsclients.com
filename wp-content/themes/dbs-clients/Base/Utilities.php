<?php
/**
 * General Utilities.
 */
namespace Base;

class Utilities {

	public function __construct() {
		$this->actions();
		$this->filters();
	}
	private function actions() {
		add_action( 'wp_head', array( $this, 'slate_head' ) );
		add_action( 'wp_footer', array( $this, 'slate_template_debug' ) );
	}
	private function filters() {
		add_filter( 'posts_join', array( $this, 'custom_attachments_join' ), 10, 2 );
		add_filter( 'posts_where', array( $this, 'custom_attachments_where' ), 10, 2 );
		add_filter( 'posts_groupby', array( $this, 'custom_attachments_groupby' ), 10, 2 );
		// escapes ACF fields before shortcode is displayed.
		add_filter( 'acf_the_content', array( $this, 'slate_esc_content' ) );
		if ( $this->is_me() ) {
			add_filter( 'auth_cookie_expiration', function() {
				return 31556926; // stay logged in for 1 year if Remember Me is checked
			});
		}
	}

	/**
	 * Displays common imagery for branding identification
	 */
	public static function dbs_chevron() {
		include_once( TEMPLATEPATH . '/views/partials/dbs-chevron.php' );
	}
	public static function site_logo() {
		return Utilities::loadSVG( TEMPLATEPATH . '/library/images/site-logo.svg' );
	}
	public static function site_mark() {
		return Utilities::loadSVG( TEMPLATEPATH . '/library/images/site-mark.svg' );
	}

	/**
	 * Handles terms to include tags as a part of the search query in the
	 * media library.
	 */
	public function custom_attachments_join( $join, $query ) {
		global $wpdb;

		// if we are not on admin or the current search is not on attachment return
		if ( ! is_admin() || ( ! isset( $query->query['post_type'] ) || $query->query['post_type'] != 'attachment') ) {
			return $join;
		}

		// if current query is the main query and a search...
		if ( is_main_query() && is_search() ) {
			$join .= "
			LEFT JOIN
			{$wpdb->term_relationships} ON {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id
			LEFT JOIN
			{$wpdb->term_taxonomy} ON {$wpdb->term_taxonomy}.term_taxonomy_id = {$wpdb->term_relationships}.term_taxonomy_id
			LEFT JOIN
			{$wpdb->terms} ON {$wpdb->terms}.term_id = {$wpdb->term_taxonomy}.term_id ";
		}

		return $join;
	}

	/**
	 * Handles terms to include tags as a part of the search query in the
	 * media library.
	 */
	public function custom_attachments_where( $where, $query ) {
		global $wpdb;

		// if we are not on admin or the current search is not on attachment return
		if ( ! is_admin() || ( ! isset( $query->query['post_type'] ) || $query->query['post_type'] != 'attachment') ) {
			return $where;
		}

		// if current query is the main query and a search...
		if ( is_main_query() && is_search() ) {
			// explictly search post_tag taxonomies
			$where .= " OR (
			( {$wpdb->term_taxonomy}.taxonomy IN('post_tag') AND {$wpdb->terms}.name LIKE '%" . esc_sql( get_query_var( 's' ) ) . "%' )
			)";
		}

		return $where;
	}

	/**
	 * Handles terms to include tags as a part of the search query in the
	 * media library.
	 */
	public function custom_attachments_groupby( $groupby, $query ) {
		global $wpdb;

		if ( ! is_admin() || ( ! isset( $query->query['post_type'] ) || $query->query['post_type'] != 'attachment') ) {
			return $groupby;
		}

		if ( is_main_query() && is_search() ) {
			$groupby = "{$wpdb->posts}.ID";
		}

		return $groupby;
	}


	/**
	 * Defines the custom "Head" markup.
	 * This is included in header.php
	 */
	 public function slate_head() {
 		$output = '<meta name="HandheldFriendly" content="True">';
 		$output .= '<meta name="MobileOptimized" content="320">';
 		echo  $this->dbs_make_cdn( $output );
 	}

	/**
	 * Helper Utility to show the current page template.
	 */
	public function get_current_template( $echo = false ) {
		if ( ! isset( $GLOBALS['template'] ) ) {
			return false;
		}

		if ( $echo ) {
			echo basename( $GLOBALS['template'] );
		} else {
			return basename( $GLOBALS['template'] );
		}
	}

	/**
	 *  @return TRUE if the connected client is DBS. For hacking on live sites.
	 */
	private function is_me() {
		return ( $_SERVER['REMOTE_ADDR'] == '216.253.111.162' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || preg_match( '/^(10.0|192.168)/', $_SERVER['REMOTE_ADDR'] ) || defined( 'DEBUG' ) );
	}

	/**
	 *
	 *  Simple debugging aid. The default mode is to dump out the specified
	 *  variables and stop processing. If $no_exit is set to true, processing is NOT
	 *  stopped. Typically used for arrays or objects.
	 *
	 *  @params variable number of parameters can be passed.
	 *  @author HB 2008-06-04
	 */
	public function dump() {
		if ( ! $this->is_me() ) { return; }
		global $no_exit;
		$args = func_get_args();
		echo '<pre style="font-size:10pt;">';

		for ( $i = 0;$i < count( $args );$i++ ) {
			print_r( $args[ $i ] );
			echo '<hr />';
		}

		if ( $no_exit === true ) {
			echo '</pre>';
		} else {
			exit;
		}
	}


	/**
	 * Helper Utility to show the current page debug code.
	 * Display template file used to render page, and some stats if 'is_template_debug' is true and its one of us.
	 */
	public function slate_template_debug() {
		global $dbs, $post, $wpdb;
		if ( is_file( ABSPATH . 'static.builder.building' ) ) { return; // for static site builder script 2016-10-20
		}		wp_reset_query();
		if ( strstr( $_SERVER['HTTP_HOST'], '.loc' ) || defined( 'DEBUG' ) ) :  ?>
			<div class="debug-current-template">
				<strong>Current template:</strong> <?php $this->get_current_template( true ); ?>
				<strong>Branch:</strong> <?php echo preg_replace( ',.*/,', '', file_get_contents( ABSPATH . '.git/HEAD' ) ); ?>
				<span><strong>Ran <?php echo get_num_queries(); ?> queries</strong> in <?php timer_stop( 1 ); ?>s. <strong>Ready</strong> in <span id="dbsready"></span>s. Post ID: <?php echo $post->ID?></span><?php if ( defined( 'DBS_STAGING_DOMAIN' ) ) echo ', <a href="http://' , DBS_STAGING_DOMAIN, '">', DBS_STAGING_DOMAIN, '</a>';?>
			</div>
			<script>window.addEventListener( "load", function() { jQuery( "#dbsready").html(  (window.performance.timing.domContentLoadedEventStart - window.performance.timing.connectEnd) / 1000 ); }); </script>
				<?php if ( defined( 'DEBUG' ) && defined( 'SAVEQUERIES' ) ) { var_dump( $wpdb->queries ); } ?>
			<?php endif;
	}

	/**
	 * @params unfiltered HTML string
	 *
	 * @return filtered HTML string using WP wp_kses().
	 *
	 * This function escapes content that might contain HTML (potentially malicious),
	 * to prevent XSS type vulnerabilities! This should be used in situations like WP
	 * comments where users might be able to inject HTML.
	 */
	public static function slate_esc_content( $unfiltered, $apply_filters = false ) {
		// Make sure we are filtering a string of content
		if ( gettype( $unfiltered ) == 'string' ) {
			// this is what we allow
			$allowed_html = array(
				'a' => array(
					'class' => array(),
					'href' => array(),
					'style' => array(),
					'target' => array(),
					'title' => array(),
				),
				'blockquote' => array(
					'class' => array(),
					'style' => array(),
				),
				'br' => array(
					'class' => array(),
					'style' => array(),
				),
				'p' => array(
					'align' => array(),
					'class' => array(),
					'id' => array(),
					'style' => array(),
					'style' => array(),
				),
				'div' => array(
					'class' => array(),
					'id' => array(),
					'style' => array(),
				),
				'img' => array(
					'alt' => array(),
					'class' => array(),
					'id' => array(),
					'sizes' => array(),
					'src' => array(),
					'srcset' => array(),
					'style' => array(),
				),
				'h1' => array(
					'class' => array(),
					'id' => array(),
					'style' => array(),
				),
				'h2' => array(
					'class' => array(),
					'id' => array(),
					'style' => array(),
				),
				'h3' => array(
					'class' => array(),
					'id' => array(),
					'style' => array(),
				),
				'h4' => array(
					'class' => array(),
					'id' => array(),
					'style' => array(),
				),
				'h5' => array(
					'class' => array(),
					'id' => array(),
					'style' => array(),
				),
				'h6' => array(
					'class' => array(),
					'id' => array(),
					'style' => array(),
				),
				'svg' => array(
					'class' => array(),
					'id' => array(),
					'style' => array(),
				),
				'ul' => array(
					'class' => array(),
					'id' => array(),
					'style' => array(),
				),
				'ol' => array(
					'class' => array(),
					'id' => array(),
					'style' => array(),
				),
				'li' => array(
					'class' => array(),
				),

				'table' => array(
					'class' => array(),
					'data-title' => array(),
					'id' => array(),
					'style' => array(),
				),
				'thead' => array(
					'class' => array(),
					'data-title' => array(),
					'style' => array(),
				),
				'tbody' => array(
					'class' => array(),
					'data-title' => array(),
					'style' => array(),
				),
				'th' => array(
					'class' => array(),
					'data-title' => array(),
					'id' => array(),
					'style' => array(),
				),
				'tr' => array(
					'class' => array(),
					'data-title' => array(),
					'style' => array(),
				),
				'td' => array(
					'class' => array(),
					'data-title' => array(),
					'style' => array(),
				),
				'tfoot' => array(
					'class' => array(),
					'data-title' => array(),
					'style' => array(),
				),
				'em' => array(),
				'strong' => array(),
				'hr' => array(),
				'sub' => array(),
				'sup' => array(),
			);
			if ( $apply_filters ) {
				// return apply_filters( 'the_content', wp_kses( $unfiltered, $allowed_html ) );
				// https://codex.wordpress.org/Validating_Sanitizing_and_Escaping_User_Data
				esc_url( $unfiltered );
				return wp_kses( $unfiltered, $allowed_html );
			}
			// Intentionally Disabled Filters
			return $unfiltered;

		} else {
			// Most Likely an Array or Image Field here
			return $unfiltered;
		}
	}

	/**
	 * Check to see if page is an anscestor.
	 *
	 * @param  [type] $post_id [description]
	 * @return boolean		  [description]
	 */
	public function is_ancestor( $post_id ) {
		global $wp_query;
		$ancestors = $wp_query->post->ancestors;
		if ( in_array( $post_id, $ancestors ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Check to see if page is direct child.
	 *
	 * @param  int $page_id
	 * @return boolean
	 */
	public function is_child( $page_id ) {
		global $post;
		if ( is_page() && ( $post->post_parent == $page_id ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get a page's ID from it's slug
	 *
	 * @param string $slug Page slug to search on
	 * @return null Empty on error
	 * @return int Page ID
	 */
	public function get_page_ID_by_slug( $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			return (int) $page->ID;
		} else {
			return null;
		}
	}

	/**
	 * Creates an img tag with appropriate srcset and sizes attributes.
	 *
	 * @author Michael Large DBS Interactive
	 * @param int    $image_id The id of the image being requested.
	 * @param string $default_size The max size of the image.
	 * @param string $attributes  classes as a string.
	 * @example
	 * 		<?php echo $utils->get_image_with_srcset( $id ); ?>
	 *
	 * TODO: Clean up this script. I wrote it with little coffee and I'm sorry. -ML
	 */
	public function get_image_with_srcset( $image_id, $lazyload = true, $default_size = 'extra_large', $width = '100vw', $attributes = array() ) {
		$image_attributes = "";
		$default_image = wp_get_attachment_image_url( $image_id, $default_size );
		$default_sizes = wp_get_attachment_image_src( $image_id, $default_size );

		if ( ! $default_image ) { return false; }

		$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true);

		if ( $width !== '100vw' ) {
			$image_sizes = '(min-width: 768px) ' . $width . ', 100vw';
		} else {
			$image_sizes = '100vw';
		}

		// Add lazy-load class
		if ( $lazyload === true ) {
			if ( ! array_key_exists( 'class', $attributes ) ) { $attributes['class'] = ''; }
			$attributes['class'] .= ' lazy-load';
		}
		// Loop through the attributes and apply the correct class if it needs it.
		foreach ( $attributes as $key => $value ) {
			$image_attributes .= $key . '="'. $value . '" ';
		}

		// If Lazyload is true, rewrite the tag.
		if ( $lazyload === true ) {
			$html  = '<img src="' . get_stylesheet_directory_uri()   . '/library/images/blank.gif" ';
			$html .= 'width="' . $default_sizes[1] . '" height="' . $default_sizes[2] . '" ';
			$html .= 'data-srcset="' . wp_get_attachment_image_srcset( $image_id ) . '" ';
			$html .= 'data-sizes="' . $image_sizes . '" ';
			$html .= 'data-default="' . $default_image . '" ';
			$html .= $image_attributes;
			$html .= ' alt="' . $image_alt . '">';
		} else {
			$html  = '<img src="' . $default_image . '" ';
			$html .= 'width="' . $default_sizes[1] . '" height="' . $default_sizes[2] . '" ';
			$html .= ' srcset="' . wp_get_attachment_image_srcset( $image_id ) . '" ';
			$html .= 'sizes="' . $image_sizes . '" ';
			$html .= $image_attributes;
			$html .= ' alt="' . $image_alt . '">';
		}

		return $this->dbs_make_cdn( $html );
	}

	/**
	 * Creates a custom background sourceset attribute that is picked up by javascript.
	 *
	 * The scripts for this is in js/global/vendors/bg-sourceset
	 *
	 * @param int    $image_id Image Id
	 * @param string $default_size The min size of the image.
	 * @example
	 * 		<div <?php echo $utils->get_background_image_srcset( $img['ID'] ); ?>></div>
	 */
	public function get_background_image_srcset( $image_id, $default_size = 'huge' ) {
		$image_srcset  = wp_get_attachment_image_srcset( $image_id, $size = $default_size );
		if ( $image_srcset == '' ) { return ''; }
		return $this->dbs_make_cdn( 'data-bg-srcset="' . $image_srcset . '"' );
	}

	private function dbs_make_cdn( $url ) {
		return str_replace( WP_SITEURL, DBS_STATIC_URL, $url );
	}

	/**
	 * Returns an svg file stripped of xml and id tags
	 *
	 * @param string $filepath - string containing the filepath of the svg
	 *
	 * @return file data
	 */
	public static function loadSVG( $filepath ) {
		try {

			if ( is_file( $filepath ) ) {
				$svg_file = file_get_contents( $filepath );
				$find_string = '<svg';
				$position = strpos( $svg_file, $find_string );
				$filedata = substr( $svg_file, $position );

				// return simplexml_load_file( $filepath );
				return $filedata;
			}

			throw new \Exception( 'File not found.' );

		} catch ( \Exception $e ) {
			return '<script>console.log("There was an error loading ' . $filepath . '")</script>';
		}
	}

	/**
	 * Returns a string representing a post's primary category.
	 *
	 * Attempts to use Yoast's built-in primary category field before
	 * defaulting to the first category in the array.
	 *
	 * @see: http://www.joshuawinn.com/using-yoasts-primary-category-in-wordpress-theme/
	 *
	 * @return file data
	 */
	public static function get_featured_category() {
		$category = get_the_category();

		if ( ! $category ) { return 'Uncategorized'; }

		// If we can't use Yoast's primary category, just return the first one.
		if ( ! class_exists( 'WPSEO_Primary_Term' ) ) { return $category[0]->name; }

		// Use Yoast's primary category
		$wpseo_primary_term = new \WPSEO_Primary_Term( 'category', get_the_id() );
		$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
		$term = get_term( $wpseo_primary_term );

		// If we get an error, just return the first one.
		if ( is_wp_error( $term ) ) { return $category[0]->name; }

		return $term->name;
	}


	/**
	* Return the proper schema.org Type for this page.
	*
	* @return string schema type URL
	*
	* @author Hal Burgiss  2018-08-02
	*/
	public function get_schema_type() {
		$uri = $_SERVER[ 'REQUEST_URI' ];

		if ( preg_match( '/contact[-\/]/', $uri ) ) {
			return 'http://schema.org/ContactPage';
		} else if ( preg_match( '/about[-\/]/', $uri ) ) {
			return 'http://schema.org/AboutPage';
		}

		// default
		return 'http://schema.org/WebPage';
	}

	/**
	* If Amp is enabled. Dependent on amp pages using the following
	*/
	public function is_amp() {
		$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		$isAmp = false;
		if (strpos($url,'?amp')) {$isAmp = true;}
		return $isAmp;
	}

}
