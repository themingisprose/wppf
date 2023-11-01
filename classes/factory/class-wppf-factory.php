<?php
namespace WPPF\Factory;

use WPPF\Factory\Posts;
use WPPF\Setting_Fields\Dashboard_Setting_Fields as Dashboard;

/**
 * The Factory
 *
 * @since 1.0.0
 */
class Factory
{

	/**
	 * Nonce
	 * @access private
	 * @var string
	 *
	 * @since 1.0.0
	 */
	private $nonce;

	/**
	 * Construct
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
		$dashboard = new Dashboard;
		$this->nonce = $dashboard->nonce;

		add_action( 'admin_init', array( $this, 'create' ) );
	}

	/**
	 * Create posts
	 *
	 * @since 1.0.0
	 */
	function create()
	{
		if ( ! isset( $_POST[ $this->nonce ] )
			|| ! wp_verify_nonce( $_POST[ $this->nonce ], $this->nonce ) )
			return;

		$posts = new Posts;

		foreach ( $posts->posts() as $key => $value ) :
			if ( ! array_key_exists( $key, $_POST ) )
				continue;

			$api_fields = array(
				'api_paragraphs' 		=> ( $_POST['api_paragraphs'] ) ? $_POST['api_paragraphs'] : null,
				'api_paragraphs_length'	=> ( $_POST['api_paragraphs_length'] ) ? $_POST['api_paragraphs_length'] : null,
				'api_decorate' 			=> ( $_POST['api_decorate'] ) ? 'decorate' : null,
				'api_link' 				=> ( $_POST['api_link'] ) ? 'link' : null,
				'api_ul' 				=> ( $_POST['api_ul'] ) ? 'ul' : null,
				'api_ol' 				=> ( $_POST['api_ol'] ) ? 'ol' : null,
				'api_dl' 				=> ( $_POST['api_dl'] ) ? 'dl' : null,
				'api_bq' 				=> ( $_POST['api_bq'] ) ? 'bq' : null,
				'api_headers' 			=> ( $_POST['api_headers'] ) ? 'headers' : null,
				'api_allcaps' 			=> ( $_POST['api_allcaps'] ) ? 'allcaps' : null,
			);

			$amount 				= $_POST[ $key .'_amount' ];
			$post_type 				= $value['type'];
			$request 				= join( '/', $api_fields );
		endforeach;
	}
}
