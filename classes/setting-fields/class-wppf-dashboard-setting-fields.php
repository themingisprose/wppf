<?php
namespace WPPF\Setting_Fields;

use WPPF\Setting_Fields\Setting_Fields;

/**
 * Dashboard Setting Fields
 *
 * @since 1.0.0
 */
class Dashboard_Setting_Fields extends Setting_Fields
{

	function __construct()
	{
		parent::__construct();

		$this->slug 			= 'wppf-setting';
		$this->option_group 	= 'wppf_setting';
		$this->option_name 		= 'wppf_options';
		$this->section_id 		= 'wppf-setting-fields-section';
		$this->field_id 		= 'wppf-setting-fields-field';
		$this->field_title 		= __( 'Factory Setting', 'text-domain' );
		$this->wpml_field		= 'my_class_of_fields_filters_fields';
	}

	function fields()
	{
		$post_types_args = array(
			'public'	=> true
		);
		$post_types = get_post_types( $post_types_args, 'objects' );
		foreach ( $post_types as $post => $value ) :
			// No attachment support
			if ( 'attachment' == $post )
				continue;
			$all_post_types[ $post ] = $value->label;
		endforeach;

		$fields['title_01'] = array(
			'label'	=> __( 'Post Type Setting', 'text-domain' ),
			'meta'	=> null,
			'type'	=> 'title'
		);

		$fields['paragraph_01'] = array(
			'label'	=> __( 'Select the post type and the amount of each one to generate', 'text-domain' ),
			'meta'	=> null,
			'type'	=> 'paragraph'
		);

		foreach ( $all_post_types as $post => $label ) :
			$fields['post_type_'. $post] = array(
				'label'	=> $label,
				'meta'	=> 'post_type_'. $post,
				'type'	=> 'checkbox'
			);
			$fields['post_type_'. $post .'_amount'] = array(
				'label'	=> sprintf( __( 'Amount of %s to generate', 'wppf' ), $label ),
				'meta'	=> 'post_type_'. $post .'_amount',
				'type'	=> 'number',
				'default'	=> get_option( 'posts_per_page' )
			);
		endforeach;

		$fields['title_02'] = array(
			'label'	=> __( 'API Setting', 'text-domain' ),
			'meta'	=> null,
			'type'	=> 'title'
		);

		// API fields
		$fields['paragraphs'] = array(
			'label'		=> __( 'The number of paragraphs to generate.', 'wppf' ),
			'meta'		=> 'paragraphs',
			'type'		=> 'number',
			'default'	=> '5'
		);

		$paragraphs_length = array(
			'short'		=> __( 'Short', 'wppf' ),
			'medium'	=> __( 'Medium', 'wppf' ),
			'long'		=> __( 'Long', 'wppf' ),
			'verylong'	=> __( 'Very long', 'wppf' )
		);

		$fields['paragraphs_length'] = array(
			'label'	=> __( 'The average length of a paragraph.', 'wppf' ),
			'meta'	=> 'paragraphs_length',
			'type'	=> 'select',
			'multiple'	=> $paragraphs_length
		);

		$api_args = array(
			'decorate'		=> __( 'Add bold, italic and marked text.', 'wppf' ),
			'link'			=> __( 'Add links.', 'wppf' ),
			'ul'			=> __( 'Add unordered list.', 'wppf' ),
			'ol'			=> __( 'Add ordered list.', 'wppf' ),
			'dl'			=> __( 'Add description list.', 'wppf' ),
			'bq'			=> __( 'Add blockquotes.', 'wppf' ),
			'headers'		=> __( 'Add headers.', 'wppf' ),
			'allcaps'		=> __( 'Use ALL CAPS.', 'wppf' ),
		);

		foreach ( $api_args as $key => $label ) :
			$fields['api_'. $key] = array(
				'label'	=> $label,
				'meta'	=> 'api_'. $key,
				'type'	=> 'checkbox'
			);
		endforeach;

		$fields['_wppfnonce'] = array(
			'label'	=> '_wppfnonce',
			'meta'	=> '_wppfnonce',
			'type'	=> 'nonce',
			'value'	=> wp_create_nonce( '_wppfnonce' )
		);

		/**
		 * Filters the Dashboard Setting fields values
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'wppf_dashboard_setting_fields', $fields );
	}
}
