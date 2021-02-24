<?php
/**
 * DBS>Interactive WordPress Custom Menu Walker
 *
 * @file MenuWalker.php
 */

namespace Base;

use \Walker_Nav_Menu;


/**
 * MenuWalker
 */
class MenuWalker extends Walker_Nav_Menu
{

	/**
	 * Class constructor
	 */
	function __construct( $css_class_prefix = 'menu' ) {
		$this->css_class_prefix = $css_class_prefix;
	}


	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. It is possible to set the
	 * max depth to include all depths, see walk() method.
	 *
	 * This method should not be called directly, use the walk() method instead.
	 *
	 * @since 2.5.0
	 *
	 * @param object $element           Data object.
	 * @param array  $children_elements List of elements to continue traversing.
	 * @param int    $max_depth         Max depth to traverse.
	 * @param int    $depth             Depth of current element.
	 * @param array  $args              An array of arguments.
	 * @param string $output            Passed by reference. Used to append additional content.
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

		$id_field = $this->db_fields['id'];

		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}

		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );

	}


	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth + 1 );


		// Add submenu toggle button if this is as submenu
		$output .= '<button class="submenu-toggle" title="Submenu" aria-expanded="false">' .
		file_get_contents(TEMPLATEPATH . "/library/icons/src/arrow-down.svg") .
		'<span class="assistive">Toggle Submenu</span></button>';

		$output .= "\n" . $indent . '<ul class="' . $this->css_class_prefix . '__submenu ' . $this->css_class_prefix . '__submenu--l' . ( $depth + 1 ) . '" role="menu" aria-label="submenu"
		>' . "\n";
	}


	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth + 1 );
		$output .= $indent . "</ul>\n";
	}


	/**
	 * Injects mega dropdown menu
	 */
	function inject_mega_dropdown( $item ) {
		global $shortcode_tags;
		$content = $item->post_content;

		// If the nav item doesn't contain a shortcode.
		if ( false === strpos( $content, '[' ) ) {
			return '';
		}

		// Get Partial Name from the shortcode.
		// This shortcode is defined in Theme.php
		preg_match( '#(\[.*\])#', $content, $match );
		$shortcode = $match[1];
		$partial_name = do_shortcode( $shortcode );

		// Get the template part and save it as a variable.
		ob_start();
		get_template_part( 'views/menus/megamenu', $partial_name );
		$partial_template = ob_get_contents();
		ob_end_clean();

		// Build the HTML
		$partial_html = '';
		$partial_html .= $partial_template;

		// Return that bad boy
		return $partial_html;
	}


	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth + 1 ) : '';
		$class_names = $value = '';
		$unfiltered_classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$prefix = isset( $this->css_class_prefix ) ? $this->css_class_prefix : '';
		$suffix = isset( $this->item_css_class_suffixes ) ? $this->item_css_class_suffixes : '';

		if ( empty( $item ) || empty( $item->classes ) ) {
			return;
		};

		$item_classes = array(
			'item_class' => $depth == 0 ? $prefix . '__item toplevel' : $prefix . '__item',

			'parent_class' => $args->has_children ? $prefix . '__item--parent' : '',
			'active_page_class' => in_array( 'current-menu-item', $item->classes ) ? $prefix . '__item--active' : '',

			'active_parent_class' => in_array( 'current-menu-parent', $item->classes ) ? 'active-parent' : '',
			'active_ancestor_class' => in_array( 'current-menu-ancestor', $item->classes ) ? 'active-ancestor' : '',

			'depth_class' => $depth >= 1 ? $prefix . '__item--l' . $depth . ' subitem' : '',
			'user_class' => $item->classes[0] !== '' ? join( ' ', $item->classes ) : '',
		   );

		$classes = array_filter( $item_classes );  // Remove empty items

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$popup = $args->has_children ? 'aria-haspopup="true"' : '';

		$output .= $indent . '<li' . $value . $class_names . $popup . ' role="menuitem" itemscope itemtype="http://schema.org/SiteNavigationElement">'; // Tab index must be set for tabbing in Safari

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )	 ? $item->target	 : '';
		$atts['rel']	= ! empty( $item->xfn )		? $item->xfn		: '';
		$atts['href']   = ! empty( $item->url )		? $item->url		: '';
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;

		$mega_dropdown = $this->inject_mega_dropdown( $item );
		if ( $mega_dropdown != '' ) {
			 $item_output .= $mega_dropdown;
		} else {
			 $item_output .= '<a'. $attributes .'><span class="nav-text" itemprop="name">';
			 $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			 $item_output .= '</span></a>';
			 $item_output .= '<link href="' . $atts['href'] . '" itemprop="url">';
		}

		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
