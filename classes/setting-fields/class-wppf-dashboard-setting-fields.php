<?php
namespace WPPF\Setting_Fields;

use WPPF\Setting_Fields\Setting_Fields;
use WPPF\Factory\Posts;

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
		$this->field_title 		= __( 'Factory Setting', 'wppf' );
		$this->wpml_field		= 'my_class_of_fields_filters_fields';

		$this->nonce 			= '_wppfnonce';
	}

	function fields()
	{

		$fields['title_01'] = array(
			'label'	=> __( 'Post Type Setting', 'wppf' ),
			'meta'	=> null,
			'type'	=> 'title'
		);

		$fields['paragraph_01'] = array(
			'label'	=> __( 'Select the post type and the amount of each one to generate', 'wppf' ),
			'meta'	=> null,
			'type'	=> 'paragraph'
		);

		$all_post_types = new Posts;
		foreach ( $all_post_types->posts() as $post => $value ) :
			$fields[$post] = array(
				'label'	=> $value['label'],
				'meta'	=> $post,
				'type'	=> 'checkbox'
			);
			$fields[$post .'_amount'] = array(
				'label'	=> sprintf( __( 'Amount of <strong>%s</strong> to generate', 'wppf' ), $value['label'] ),
				'meta'	=> $post .'_amount',
				'type'	=> 'number',
				'default'	=> get_option( 'posts_per_page' ),
				'attr'	=> array(
					'min'	=> 1,
					'max'	=> get_option( 'posts_per_page' )
				)
			);
		endforeach;

		$fields['title_02'] = array(
			'label'	=> __( 'API Setting', 'wppf' ),
			'meta'	=> null,
			'type'	=> 'title'
		);

		// API fields
		$fields['api_paragraphs'] = array(
			'label'		=> __( 'The number of paragraphs to generate.', 'wppf' ),
			'meta'		=> 'api_paragraphs',
			'type'		=> 'number',
			'default'	=> '5',
			'attr'	=> array(
				'min'	=> 1
			)
		);

		$paragraphs_length = array(
			'short'		=> __( 'Short', 'wppf' ),
			'medium'	=> __( 'Medium', 'wppf' ),
			'long'		=> __( 'Long', 'wppf' ),
			'verylong'	=> __( 'Very long', 'wppf' )
		);

		$fields['api_paragraphs_length'] = array(
			'label'	=> __( 'The average length of a paragraph.', 'wppf' ),
			'meta'	=> 'api_paragraphs_length',
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

		$fields[ $this->nonce ] = array(
			'label'	=> $this->nonce,
			'meta'	=> $this->nonce,
			'type'	=> 'nonce',
			'default'	=> wp_create_nonce( $this->nonce )
		);

		$fields['title_03'] = array(
			'label'	=> __( 'Note:', 'wppf' ),
			'meta'	=> null,
			'type'	=> 'title'
		);

		$fields['paragraph_02'] = array(
			'label'	=> sprintf(
						__( '<em>For performance reasons the maximums of post per request is the value of <a href="%s"><code>posts_per_page</code></a> option, in your case is <code>%s</code>.</em>', 'wppf' ),
						admin_url( 'options-reading.php#posts_per_page' ),
						get_option( 'posts_per_page' )
					),
			'meta'	=> null,
			'type'	=> 'paragraph'
		);

		/**
		 * Filters the Dashboard Setting fields values
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'wppf_dashboard_setting_fields', $fields );
	}
}
