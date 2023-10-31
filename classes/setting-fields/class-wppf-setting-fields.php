<?php
namespace WPPF\Setting_Fields;

use WPPF\Helpers\HTML;

/**
 * Setting Fields
 *
 * @since 1.0.0
 */
abstract class Setting_Fields
{

	/**
	 * Option group
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $option_group;

	/**
	 * Option name
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $option_name;

	/**
	 * HTML section id
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $section_id;

	/**
	 * Section title
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $section_title;

	/**
	 * Page slug
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $slug;

	/**
	 * HTML field id
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $field_id;

	/**
	 * Field Title
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $field_title;


	/**
	 * Construct
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'register_setting' ) );
	}

	/**
	 * Register Setting
	 *
	 * @since 1.0.0
	 */
	function register_setting()
	{
		register_setting(
			$this->option_group,
			$this->option_name,
			array( 'sanitize_callback' => array( $this, 'sanitize_options' ) )
		);

		add_settings_section(
			$this->slug .'-'. $this->section_id,
			$this->section_title,
			'__return_false',
			$this->slug
		);

		add_settings_field(
			$this->slug .'-'. $this->field_id,
			$this->field_title,
			$this->callback(),
			$this->slug,
			$this->slug .'-'. $this->section_id
		);
	}

	/**
	 * Main configuration function
	 * @param string $meta 	Meta key stored in '$this->option_name' register in the data base option table.
	 *
	 * @since 1.0.0
	 */
	function get_option( $meta )
	{
		$option = get_option( $this->option_name );

		if ( ! is_array( $option ) || ! array_key_exists( $meta, $option ) )
			return;

		return $option[$meta];
	}

	/**
	 * Sanitize Callback
	 *
	 * @since 1.0.0
	 */
	function sanitize_options()
	{
		/**
		 * Filters the Options Input
		 * @param array 	Array of key => value
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'wppf_setting_fields_options_input', $this->validate() );
	}

	/**
	 * Get the callable that will the content of the section.
	 * @return callable
	 *
	 * @since 1.0.0
	 */
	function callback()
	{
		return array( $this, 'render' );
	}

	/**
	 * Render the content of the section
	 *
	 * @since 1.0.0
	 */
	function render()
	{
		$fields = $this->fields();

		foreach ( $fields as $key => $data ) :
			$value = $this->get_option( $data['meta'] );
			HTML::field( $data, $value );
		endforeach;
	}

	/**
	 * Process validation fields
	 *
	 * @since 1.0.0
	 */
	function validate()
	{
		$fields = $this->fields();

		foreach ( $fields as $key => $value ) :
			$input[$value['meta']] = ( isset( $_POST[$value['meta']] ) && null != $_POST[$value['meta']] )
										? stripslashes( trim( $_POST[$value['meta']] ) )
										: null;
		endforeach;

		add_settings_error( 'wppf-update', 'wppf', __( 'Setting Updated', 'wppf' ), 'success' );

		return $input;
	}

	/**
	 * Array of fields to render
	 * This method must return an associative array like the example
	 * 		$fields = array(
	 * 			'field_key_name' => array(
	 * 				'label'		=> 'Field Name',
	 * 				'option'	=> 'field_option_name',
	 * 				'type'		=> 'text',
	 * 				'multiple'	=> array()
	 * 			),
	 * 			...
	 * 		);
	 * @see class WPPF\Helpers\HTML::field() for full documentation about supported fields.
	 * @return array 	Array of fields
	 *
	 * @since 1.0.0
	 */
	abstract public function fields();
}
