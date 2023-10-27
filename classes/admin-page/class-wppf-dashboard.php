<?php
namespace WPPF\Admin;

use WPPF\Admin\Admin_Page;

/**
 * Dashboard
 *
 * @since 1.0.0
 */
class Dashboard extends Admin_Page
{

	function __construct()
	{
		parent::__construct();
		$this->page_title 	= __( 'WPPF Admin Page', 'wppf' );
		$this->menu_title 	= __( 'WPPF', 'wppf' );
		$this->page_heading	= __( 'The WordPress Post Factory', 'wppf' );
		$this->capability 	= 'manage_options';
		$this->menu_slug	= 'wppf-setting';
		$this->option_group	= 'wppf_setting';
		$this->option_name 	= 'wppf_options';
		$this->icon_url 	= 'dashicons-admin-post';
	}
}
