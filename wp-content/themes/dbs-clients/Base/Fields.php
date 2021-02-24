<?php
/**
 * @file Fields.php
 * @author DBS>Interactive
 *
 * Helper class to register ACF Fields and access them.
 */

namespace Base;

class Fields {
	function __construct() {
		$this->make_fields();
	}
	/*
		Copied Data From Base Exports
	 */
	private function make_fields(){

	if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array (
		'key' => 'group_5827a8e2db49a',
		'title' => 'Flexible Content',
		'fields' => array (
			array (
				'key' => 'field_5827a95d6e706',
				'label' => 'Flex Content',
				'name' => 'flex_content',
				'type' => 'flexible_content',
				'instructions' => 'The Flexible Content field allows you to create custom layouts on the page based on your branding requirements. Pick a layout that best suits the page and add your content to the fields that layout provides.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'button_label' => 'Add Layout',
				'min' => '',
				'max' => '',
				'layouts' => array (
					array (
						'key' => '5827a968ed464',
						'name' => 'default',
						'label' => 'Default',
						'display' => 'block',
						'sub_fields' => array (
							array (
								'key' => 'field_5827a98e6e707',
								'label' => 'Content',
								'name' => 'content',
								'type' => 'wysiwyg',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'tabs' => 'all',
								'toolbar' => 'full',
								'media_upload' => 1,
							),
							array (
								'key' => 'field_5827bc4492c35',
								'label' => 'Advanced Properties',
								'name' => 'special_class',
								'type' => 'checkbox',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'choices' => array (
									'yes' => 'Yes',
								),
								'default_value' => array (
								),
								'layout' => 'horizontal',
								'toggle' => 0,
								'return_format' => 'value',
							),
							array (
								'key' => 'field_5827b709d7fff',
								'label' => 'Select Properties',
								'name' => 'special_class_list',
								'type' => 'select',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array (
									array (
										array (
											'field' => 'field_5827bc4492c35',
											'operator' => '==',
											'value' => 'yes',
										),
									),
								),
								'wrapper' => array (
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'choices' => array (
									'bg-color__light' => 'Background - Light',
									'bg-color__dark' => 'Background - dark',
									'contain' => 'Constrain Width',
								),
								'default_value' => array (
								),
								'allow_null' => 0,
								'multiple' => 1,
								'ui' => 1,
								'ajax' => 0,
								'return_format' => 'value',
								'placeholder' => '',
							),
						),
						'min' => '',
						'max' => '',
					),
					array (
						'key' => '5828c06786fd8',
						'name' => 'full_width',
						'label' => 'Full Width Layout',
						'display' => 'block',
						'sub_fields' => array (
							array (
								'key' => 'field_58409ac7ef6bb',
								'label' => 'Full Width Content',
								'name' => 'full_width_content',
								'type' => 'wysiwyg',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'tabs' => 'all',
								'toolbar' => 'full',
								'media_upload' => 1,
							),
							array (
								'key' => 'field_5846e270ec1b0',
								'label' => 'Background Image',
								'name' => 'background_image',
								'type' => 'image',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'return_format' => 'array',
								'preview_size' => 'thumbnail',
								'library' => 'all',
								'min_width' => '',
								'min_height' => '',
								'min_size' => '',
								'max_width' => '',
								'max_height' => '',
								'max_size' => '',
								'mime_types' => '',
							),
							array (
								'key' => 'field_585d53bd25d9e',
								'label' => 'Advanced Properties',
								'name' => 'special_class',
								'type' => 'checkbox',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'choices' => array (
									'yes' => 'Yes',
								),
								'default_value' => array (
								),
								'layout' => 'horizontal',
								'toggle' => 0,
								'return_format' => 'value',
							),
							array (
								'key' => 'field_585d53c725d9f',
								'label' => 'Select Properties',
								'name' => 'full-width-special_class_list',
								'type' => 'select',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array (
									array (
										array (
											'field' => 'field_585d53bd25d9e',
											'operator' => '==',
											'value' => 'yes',
										),
									),
								),
								'wrapper' => array (
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'choices' => array (
									'bg__light' => 'Background - Light',
									'bg__dark' => 'Background - dark',
									'contain' => 'Constrain Width',
									'align-text-left' => 'Text Align Left',
									'align-text-center' => 'Text Align Center',
									'align-text-center-small' => 'Text Align Center (Reduced Width)',
								),
								'default_value' => array (
								),
								'allow_null' => 0,
								'multiple' => 1,
								'ui' => 1,
								'ajax' => 0,
								'return_format' => 'value',
								'placeholder' => '',
							),
						),
						'min' => '',
						'max' => '',
					),
					array (
						'key' => '5827aab8bf533',
						'name' => 'half_and_half',
						'label' => 'Half and Half',
						'display' => 'block',
						'sub_fields' => array (
							array (
								'key' => 'field_5827aae4bf534',
								'label' => 'Half and Half Format',
								'name' => 'half_and_half_format',
								'type' => 'select',
								'instructions' => 'This is a multi-select field',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '100',
									'class' => '',
									'id' => '',
								),
								'choices' => array (
									'Select Type' => 'Select Type',
									'image-and-text__right' => 'Image Left and Text Right',
									'image-and-text__left' => 'Image Right and Text Left',
									'text-and-background' => 'Text With Background',
								),
								'default_value' => array (
								),
								'allow_null' => 0,
								'multiple' => 0,
								'ui' => 0,
								'ajax' => 0,
								'return_format' => 'value',
								'placeholder' => '',
							),
							array (
								'key' => 'field_5827af7bbf537',
								'label' => 'Left Side Content',
								'name' => 'left_side_content',
								'type' => 'wysiwyg',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array (
									array (
										array (
											'field' => 'field_5827aae4bf534',
											'operator' => '==',
											'value' => 'text-and-background',
										),
									),
									array (
										array (
											'field' => 'field_5827aae4bf534',
											'operator' => '==',
											'value' => 'image-and-text__left',
										),
									),
								),
								'wrapper' => array (
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'tabs' => 'all',
								'toolbar' => 'full',
								'media_upload' => 1,
							),
							array (
								'key' => 'field_5827ba734b780',
								'label' => 'Right Side Image',
								'name' => 'right_side_image',
								'type' => 'image',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array (
									array (
										array (
											'field' => 'field_5827aae4bf534',
											'operator' => '==',
											'value' => 'image-and-text__left',
										),
									),
									array (
										array (
											'field' => 'field_5827aae4bf534',
											'operator' => '==',
											'value' => 'text-and-background',
										),
									),
								),
								'wrapper' => array (
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'return_format' => 'array',
								'preview_size' => 'thumbnail',
								'library' => 'all',
								'min_width' => '',
								'min_height' => '',
								'min_size' => '',
								'max_width' => '',
								'max_height' => '',
								'max_size' => '',
								'mime_types' => '',
							),
							array (
								'key' => 'field_5850576319591',
								'label' => 'Left Side Image',
								'name' => 'left_side_image',
								'type' => 'image',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array (
									array (
										array (
											'field' => 'field_5827aae4bf534',
											'operator' => '==',
											'value' => 'image-and-text__right',
										),
									),
									array (
										array (
											'field' => 'field_5827aae4bf534',
											'operator' => '==',
											'value' => 'text-and-background',
										),
									),
								),
								'wrapper' => array (
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'return_format' => 'array',
								'preview_size' => 'thumbnail',
								'library' => 'all',
								'min_width' => '',
								'min_height' => '',
								'min_size' => '',
								'max_width' => '',
								'max_height' => '',
								'max_size' => '',
								'mime_types' => '',
							),
							array (
								'key' => 'field_5827afd8bf539',
								'label' => 'Right Side Content',
								'name' => 'right_side_content',
								'type' => 'wysiwyg',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array (
									array (
										array (
											'field' => 'field_5827aae4bf534',
											'operator' => '==',
											'value' => 'text-and-background',
										),
									),
									array (
										array (
											'field' => 'field_5827aae4bf534',
											'operator' => '==',
											'value' => 'image-and-text__right',
										),
									),
								),
								'wrapper' => array (
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'tabs' => 'all',
								'toolbar' => 'full',
								'media_upload' => 1,
							),
							array (
								'key' => 'field_585bfdc68d902',
								'label' => 'Advanced Properties',
								'name' => 'special_class',
								'type' => 'checkbox',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'choices' => array (
									'yes' => 'Yes',
								),
								'default_value' => array (
								),
								'layout' => 'horizontal',
								'toggle' => 0,
								'return_format' => 'value',
							),
							array (
								'key' => 'field_585047d4ea45a',
								'label' => 'Select Properties',
								'name' => 'special_class_list',
								'type' => 'select',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array (
									array (
										array (
											'field' => 'field_585bfdc68d902',
											'operator' => '==',
											'value' => 'yes',
										),
									),
								),
								'wrapper' => array (
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'choices' => array (
									'right-bg__light' => 'Right Background - Light',
									'left-bg__light' => 'Left Background - Light',
									'right-bg__dark' => 'Right Background - Dark',
									'left-bg__dark' => 'Left Background - Dark',
									'contain-half-and-half' => 'Constrain Width',
									'shift-text-right' => 'Right Text Position',
								),
								'default_value' => array (
								),
								'allow_null' => 0,
								'multiple' => 1,
								'ui' => 1,
								'ajax' => 0,
								'return_format' => 'value',
								'placeholder' => '',
							),
						),
						'min' => '',
						'max' => '',
					),
					array (
						'key' => '582920fbb774e',
						'name' => 'flex_blocks',
						'label' => 'Flex Blocks',
						'display' => 'row',
						'sub_fields' => array (
							array (
								'key' => 'field_58753f6725f4d',
								'label' => 'Title',
								'name' => 'title',
								'type' => 'text',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'default_value' => '',
								'placeholder' => '',
								'prepend' => '',
								'append' => '',
								'maxlength' => '',
							),
							array (
								'key' => 'field_5874f8416b446',
								'label' => 'Left-Aligned Block',
								'name' => 'flex_blocks_content_left-aligned',
								'type' => 'repeater',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'collapsed' => '',
								'min' => '',
								'max' => '',
								'layout' => 'table',
								'button_label' => 'Add Row',
								'sub_fields' => array (
									array (
										'key' => 'field_5874f87c6b447',
										'label' => 'Content',
										'name' => 'flex_block_content_left-aligned',
										'type' => 'wysiwyg',
										'instructions' => '',
										'required' => 0,
										'conditional_logic' => 0,
										'wrapper' => array (
											'width' => '',
											'class' => '',
											'id' => '',
										),
										'default_value' => '',
										'tabs' => 'all',
										'toolbar' => 'full',
										'media_upload' => 1,
									),
								),
							),
							array (
								'key' => 'field_5829210cb7751',
								'label' => 'Block',
								'name' => 'flex_blocks_content',
								'type' => 'repeater',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '',
									'class' => '',
									'id' => '',
								),
								'collapsed' => '',
								'min' => '',
								'max' => '',
								'layout' => 'table',
								'button_label' => 'Add Row',
								'sub_fields' => array (
									array (
										'key' => 'field_584047715ff52',
										'label' => 'Content',
										'name' => 'flex_block_content',
										'type' => 'wysiwyg',
										'instructions' => '',
										'required' => 0,
										'conditional_logic' => 0,
										'wrapper' => array (
											'width' => '',
											'class' => '',
											'id' => '',
										),
										'default_value' => '',
										'tabs' => 'all',
										'toolbar' => 'full',
										'media_upload' => 1,
									),
								),
							),
							array (
								'key' => 'field_585d53ea25da0',
								'label' => 'Advanced Properties',
								'name' => 'special_class',
								'type' => 'checkbox',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => 0,
								'wrapper' => array (
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'choices' => array (
									'yes' => 'Yes',
								),
								'default_value' => array (
								),
								'layout' => 'horizontal',
								'toggle' => 0,
								'return_format' => 'value',
							),
							array (
								'key' => 'field_585d53f425da1',
								'label' => 'Select Properties',
								'name' => 'special_class_list',
								'type' => 'select',
								'instructions' => '',
								'required' => 0,
								'conditional_logic' => array (
									array (
										array (
											'field' => 'field_585d53ea25da0',
											'operator' => '==',
											'value' => 'yes',
										),
									),
								),
								'wrapper' => array (
									'width' => '50',
									'class' => '',
									'id' => '',
								),
								'choices' => array (
									'contain-half-and-half' => 'Constrain Width',
									'three-times-two' => 'Three Rows with Two in Each Column',
								),
								'default_value' => array (
								),
								'allow_null' => 0,
								'multiple' => 1,
								'ui' => 1,
								'ajax' => 0,
								'return_format' => 'value',
								'placeholder' => '',
							),
						),
						'min' => '',
						'max' => '',
					),
				),
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => array (
			0 => 'the_content',
		),
		'active' => 1,
		'description' => '',
	));

	endif;
}
}
