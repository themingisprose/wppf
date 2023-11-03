<?php
namespace WPPF\Admin;

/**
 * Admin Page
 *
 * @since 1.0.0
 */
abstract class Admin_Page
{

	/**
	 * Page title
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $page_title;

	/**
	 * Menu title
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $menu_title;

	/**
	 * Capability
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $capability;

	/**
	 * Menu slug
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $menu_slug;

	/**
	 * Icon URL
	 * @access public
	 * @var string 	Optional
	 *
	 * @since 1.0.0
	 */
	public $icon_url = '';

	/**
	 * Position
	 * @access public
	 * @var string 	Optional
	 *
	 * @since 1.0.0
	 */
	public $position = null;

	/**
	 * Page heading
	 * @access public
	 * @var string 	Optional
	 *
	 * @since 1.0.0
	 */
	public $page_heading;

	/**
	 * Option Group
	 * @access public
	 * @var string 	Required
	 *
	 * @since 1.0.0
	 */
	public $option_group;

	/**
	 * Construct
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Add the admin menu page
	 *
	 * @since 1.0.0
	 */
	function admin_menu()
	{
		add_menu_page(
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
			array( $this, 'setting_options' ),
			$this->icon_url,
			$this->position
		);
	}

	/**
	 * Display the form
	 *
	 * @since 1.0.0
	 */
	function setting_options()
	{
		?>
			<div class="wrap">
				<h2><?php echo $this->page_heading ?></h2>
				<?php settings_errors( 'wppf-update' ) ?>
				<form id="<?php echo $this->menu_slug ?>" method="post" action="options.php">
					<?php
						settings_fields( $this->option_group );
						do_settings_sections( $this->menu_slug );
						submit_button( __( 'Start!', 'wppf' ) );
					?>
				</form>
			</div>
		<?php
	}
}
