<?php
/**
 * Slate.php
 *
 * Slate extends $theme, the base theme object from /Base.
 *
 * TODO: Per project customizations should go here only.
 * TODO: Document this better via Github wiki.
 * FIXME: This isn't the case currently.
 */
Namespace Slate;

use \Base\Theme;
use \Base\SimpleWalker;

class Slate extends Theme {

	public $slug = "slate";
	public function __construct() {
		parent::__construct();

		//Setup text domain
		load_theme_textdomain( $this->slug, get_stylesheet_directory() . '/languages' );

		$this->actions();
		$this->filters();
		$this->build_options_page();
		$this->shortcodes();
	}

	/**
	 * Actions
	 */
	private function actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'init', array( $this, 'register_menus' ) );
		add_action( 'acf/init', array( $this, 'slides_add_local_field_groups') );
		add_action( 'admin_menu', array( $this, 'remove_admin_menu_items') );
		add_action( 'init' , array( $this, 'add_custom_image_sizes') );
		add_action( 'widgets_init', array( $this, 'register_sidebars') );
	} // actions

	/**
	 * Filters
	 */
	private function filters(){}

	/**
	 * Shortcodes
	 */
	private function shortcodes() {
		add_shortcode( 'container', array( $this, 'do_shortcode_container' ) );
		add_shortcode( 'button', array( $this, 'do_shortcode_button' ) );
	}

	/**
	 * Register New Navigation Menus
	 */
	function register_menus() {
		register_nav_menus(
			array(
				'main_menu'      => 'Main Menu',
				'secondary_menu' => 'Secondary Menu',
				'legal_menu' => 'Legal Menu',
				'footer_menu'    => 'Footer Menu',
			)
		);
	}

	/**
	 * Enqueue scripts and styles
	 * Needs to be public So that wordpress can call it.
	 */
	public function enqueue_scripts(){}

	/**
	 * Removes menu items from the dashboard.
	 * The client isn't using Blog Posts so let's remove it.
	 */
	function remove_admin_menu_items(){
		// remove_menu_page( 'edit.php' );  // removes Blog
		return false;
	}

	/**
	 * Adds custom image sizes. -- Defined in Theme.php -- 12.19.16 - JD
	 */
	// public function add_custom_image_sizes(){
	// 	add_image_size( 'extra_large', 1300, 2000 );
	// 	add_image_size( 'huge', 2000, 3000 );
	// }

	/**
	 * Build the Slate Theme Settings Page
	 * AKA "Options" page.
	 */
	function build_options_page(){
		if( function_exists( 'acf_add_options_page' ) ) {

			acf_add_options_page( array(
				'page_title' 	=> 'Theme Settings',
				'menu_title'	=> 'Theme Settings',
				'menu_slug' 	=> 'theme-general-settings',
				'capability'	=> 'edit_plugins',
				'redirect'		=> false,
				'icon_url' 		=> 'dashicons-admin-settings',
			));

			acf_add_options_sub_page( array(
				'page_title' 	=> 'Administrator Settings',
				'menu_title'	=> 'Admin',
				'parent_slug'	=> 'theme-general-settings',
			));

		}
	}

	/**
	 * Register our sidebars and widgetized areas.
	 *
	 */
	function register_sidebars() {

		$args = array(
		'name'		  => __( 'Sidebar 1', 'theme_text_domain' ),
		'id'			=> 'sidebar1',
		'description'   => '',
		'class'		 => 'sidebar1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>'
		);

		register_sidebar( $args );

		// TODO: this sidebar should be conditional on $dbs->has_blog.
		$args2 = array(
		'name'		  => __( 'Sidebar Blog', 'theme_text_domain' ),
		'id'			=> 'sidebar-blog',
		'description'   => '',
		'class'		 => 'sidebar-blog',
		'before_widget' => '<div id="%1$s" class="widget %2$s blog">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>'
		);

		register_sidebar( $args2 );

	}


	/**
	 * Container shortcode - useful for wrapping items in a div with a special class
	 *
	 * Applies content filters to recursively parse shortcodes, remove empty <p> tags
	 * and other content filters.
	 *
	 * Having the seemingly superfluous open and close p tags is necessary due to the
	 * annoying way WordPress uses ptags. Without this, if the content has more than
	 * one line, WordPress would insert </p><p> between the lines. There are potentially
	 * other ways of fighting this battle, but this seemed to be the most simple.
	 *
	 * @usage
	 *
	 * 		Use this anywhere in the WordPress CMS:
	 *
	 * 		[container class="my-class"]
	 * 			content...
	 * 		[/container]
	 *
	 * 		This creates:
	 *
	 * 		<div class="my-class"
	 * 			content...
	 * 		</div>
	 */
	function do_shortcode_container( $atts, $content = '' ) {
		$atts = shortcode_atts( array( 'class' => '' ), $atts );
		return apply_filters( 'the_content', "\n<div class=\"" . $atts['class'] . "\"><p>" . $content . "\n</p></div>\n" );
	}

	/**
	 * Button shortcode - creates a styled button
	 *
	 * @usage
	 *
	 * 		Use this anywhere in the WordPress CMS:
	 *
	 * 		[button link="https://dbswebsite.com"]
	 * 			Caption text...
	 * 		[/button]
	 *
	 * 		This creates:
	 *
	 * 		<a class="button-ghost--orange" href="https://dbswebsite.com">
	 * 			Caption text...
	 * 		</a>
	 */
	function do_shortcode_button( $atts, $content = '' ) {
		$atts = shortcode_atts( array( 'url' => '#', 'type' => '' ), $atts );
		return "\n" . '<a class="button ' . $atts['type'] . '" href="' . $atts['url'] . '">' . $content . '</a>' . "\n";
	}
}
