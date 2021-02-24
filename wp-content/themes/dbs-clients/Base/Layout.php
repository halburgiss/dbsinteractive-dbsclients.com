<?php
/**
 * This is a model for DBS's ACF Flex Layouts.
 * DBS>Interactive
 *
 * ********************************************************
 * ACF Flex Layouts
 * ********************************************************
 *
 * Terms:
 *
 * 		layout - one single section of content
 * 		cell - is a component of a layout; for instance, the left side of a
 * 			half-n-half is one cell and the right side is another.
 *
 * 	Layouts have fields that are created by ACF. This layout object can
 * 	retrieve those fields useing `get_` + field name or `the_` + field name.
 * 	The `get_*` method will return the string and the `the_*` will echo the
 * 	string out juts like typical WordPress. The `has_*` method will return true
 * 	if the ACF field exists and is filled out and the value is 'truthy'.
 *
 * 	There are a couple of specially reserved fields that should be used for
 * 	special casses.
 * 		*_image - this is for an image field that is to be used as an image tag
 * 		*_bg_image - this is for an image field that is to be used as a
 * 			background image.
 * 		layout_classes - this is for classes that go on the wrapper div
 * 		*_classes - this is for other classes, particularly those that go on
 * 			the cell's wrapper div
 *
 * This class is setup so that if the field is visible in the gui on the back
 * end, it will be visible on the front end. Hence, you can use conditional
 * logic for ACF fields in one place and it will be used both on the front end
 * and the back end.
 *
 * A DBS ACF flex layout will usually have the following fields:
 * 		content (wysiwyg -> html)
 * 		image ( that can be either a background image or a regular image)
 * 		layout_classes - classes to be applied to the layout's containing
 * 			element
 * 		cell_classes - classes applied to a particular cell (column) of a
 * 			layout
 */

namespace Base;

class Layout {

	public static $count = 0;  // Reference using \Layout::$count;

	/**
	 * Layout constructor.
	 *
	 * @param string $layout - string name of the layout.
	 */
	public function __construct( $layout ) {
		self::$count++; // Increment immediately to start with 1 not 0.

		$this->layout = $layout;

		$this->template = 'views/acf-flex/layout-'. str_replace( '_', '-', $this->layout ) .'.php';

		// If we are on theme slate and the layout lives in the optional
		// directory, use it.
		$optional_layout = 'views/acf-flex/optional/layout-'. str_replace( '_', '-', $this->layout ) .'.php';
		if ( wp_get_theme()->__toString() === 'slate' && file_exists( get_template_directory() . '/' . $optional_layout ) ) {
			$this->template = $optional_layout;
		}

		$this->display();
	}


	/**
	 * Magic method to convert get_* and the_* properties into fieldnames.
	 *
	 * Using the Wordpress Paradigm, get_* properties will return a string and
	 * set_* properties will echo the string.
	 *
	 * @param string $name - the property name
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		if ( strpos( $name, 'get_' ) === 0 ) {
			return $this->get_field( substr( $name, 4 ) );
		} else if ( strpos( $name, 'the_' ) === 0 ) {
			echo $this->get_field( substr( $name, 4 ) );
		} else if ( strpos( $name, 'has_' ) === 0 ) {
			return !! $this->get_field( substr( $name, 4 ) );
		} else {
			throw new Exception( 'There is no field named "' . $name . '".' );
		}
	}


	/**
	 * Gets an ACF field for the layout.
	 *
	 * @param String $name - the name of the field
	 *
	 * @return mixed
	 */
	public function get_field( $name ) {
		global $utils;

		// If it's not visible, just return an empty string
		if ( ! $this->is_visible( $name ) ) {
			return '';

		// Layout and cell classes
		} else if ( strpos( $name, 'classes' ) !== false ) {
			return $this->get_class_list( $name );

		// Background image fields
		} else if ( strpos( $name, 'bg_image' ) !== false ) {
			$image = get_sub_field( $name );

			// Special case: SVGs
			if ( get_post_mime_type( $image['ID'] ) === 'image/svg+xml' ) {
				return ' data-bg-srcset="' . $background_image['url'] . '" ';
			}

			return $utils->get_background_image_srcset( $image['ID'] );

		// Regular image fields
		} else if ( strpos( $name, 'image' ) !== false ) {
			return $utils->get_image_with_srcset( get_sub_field( $name )['ID'] );

		// All other fields defer to ACF
		} else {
			return get_sub_field( $name ) ?? '';
		}
	}


	/**
	 * Uses the ACF conditional logic to determine whether or not this
	 * particular field is visible in the back end. If it is return true to
	 * show it. If not return false. This helps to keep the conditional logic
	 * of fields in one place: the CMS, and prevents code duplication.
	 *
	 * @param string $fieldname
	 *
	 * @return bool
	 */
	public function is_visible( $fieldname ) {
		$conditional_logic = get_sub_field_object( $fieldname )['conditional_logic'];

		if (! $conditional_logic ) { return true; }

		/**
		 * Logic to determine if this field is conditionally hidden based on
		 * ACF conditional logic.
		 */
		$all_groups = false; // Will be ORed with the result of the first logic_group.
		foreach ( $conditional_logic as $logic_group ) {
			$group = true;

			// All of the conditions in a logic group must be true for the
			// group to be true
			foreach ( $logic_group as $condition ) {

				$current_condition = true;

				$field = get_sub_field( $condition['field'] );
				$value = $condition['value'];

				// NOTE: Not all conditions have been completed here!!
				switch ($condition['operator']) {
					case '!=':
						if ( is_array($field) ) {
							$current_condition = ! in_array($value, $field);
						} else {
							$current_condition = $field != $condition['value'];
						}
						break;

					case '==':
						if ( is_array($field) ) {
							$current_condition = in_array($value, $field);
						} else {
							$current_condition = $field == $condition['value'];
						}
						break;

					case '!=empty':
						$current_condition = ( !! $field );
						break;

					case '==empty':
						$current_condition = ( ! $field );
						break;
				}

				$group = $group && $current_condition;
			}

			// Only one of the logic groups has to be true for the etire
			// conditional logic to be true.
			$all_groups = $all_groups || $group;
		}
		return $all_groups;
	}


	/**
	 * Display the layout using its template
	 */
	public function display() {
		global $theme;
		$theme->view( $this->template, array( 'layout' => $this ) );
	}


	/**
	 * Test if layout has a partial.
	 *
	 * @return bool - returns true/false if layout has a partial.
	 */
	public function has_partial() {
		return ( $this->get_partial_name != '' );
	}


	/**
	 * Retrieve and display the actual partial code.
	 */
	public function get_the_partial() {
		if ( $this->has_partial() ) {
			get_template_part( 'views/partials/cms', $this->get_partial_name );
		}
	}


	/**
	 * Test if layout has a particular format.
	 *
	 * @param string $format - the format to test.
	 *
	 * @return bool - returns true/false if format of layout is $format.
	 */
	public function has_format( $format = '' ) {
		return $this->get_format === $format;
	}


	/**
	 * Prints the special class list for a field.
	 *
	 * @param string $fieldname
	 */
	public function the_class_list( $fieldname ) {
		echo $this->get_class_list( $fieldname );
	}


	/**
	 * Get the special class list for a field.
	 *
	 * @param string $fieldname
	 *
	 * @return string - a space-separated string of classes
	 */
	public function get_class_list( $fieldname ) {
		if ( ! $this->has_special_class_list( $fieldname ) ) {
			return '';
		}
		return ' ' . implode( ' ', get_sub_field( $fieldname )['special_class_list'] ?? array('') ) . ' ';
	}


	/**
	 * Test if field has a special class list.
	 *
	 * @param string $fieldname
	 *
	 * @return bool - returns true/false if field has special classes.
	 */
	public function has_special_class_list( $fieldname ) {
		return (
			( get_sub_field( $fieldname ) ) &&
			( isset( get_sub_field( $fieldname )['special_class'] ) ) &&
			( isset( get_sub_field( $fieldname )['special_class'][0] ) ) &&
			( get_sub_field( $fieldname )['special_class'][0] == 'yes' ) &&
			( ! empty( get_sub_field( $fieldname )['special_class_list'] ) )
		);
	}
}
