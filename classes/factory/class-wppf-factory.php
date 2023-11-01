<?php
namespace WPPF\Factory;

use WPPF\Factory\Posts;
use WPPF\Setting_Fields\Dashboard_Setting_Fields as Dashboard;
use WPPF\API\Loripsum;

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
				'api_paragraphs' 		=> ( isset( $_POST['api_paragraphs'] ) ) ? $_POST['api_paragraphs'] : null,
				'api_paragraphs_length'	=> ( isset( $_POST['api_paragraphs_length'] ) ) ? $_POST['api_paragraphs_length'] : null,
				'api_decorate' 			=> ( isset( $_POST['api_decorate'] ) ) ? 'decorate' : null,
				'api_link' 				=> ( isset( $_POST['api_link'] ) ) ? 'link' : null,
				'api_ul' 				=> ( isset( $_POST['api_ul'] ) ) ? 'ul' : null,
				'api_ol' 				=> ( isset( $_POST['api_ol'] ) ) ? 'ol' : null,
				'api_dl' 				=> ( isset( $_POST['api_dl'] ) ) ? 'dl' : null,
				'api_bq' 				=> ( isset( $_POST['api_bq'] ) ) ? 'bq' : null,
				'api_headers' 			=> ( isset( $_POST['api_headers'] ) ) ? 'headers' : null,
				'api_allcaps' 			=> ( isset( $_POST['api_allcaps'] ) ) ? 'allcaps' : null,
			);

			$amount		= $_POST[ $key .'_amount' ];
			$post_type	= $value['type'];
			$request	= join( '/', $api_fields );
			$api 		= new Loripsum;

			for ( $i = 0; $i < $amount; $i++ ) :
				$result		= $api->api( $request );
				preg_match( '`<h1>(.*?)</h1>`im', $result, $title );
				$lipsum_post = get_default_post_to_edit( $post_type, true );
				$args = array(
					'ID'			=> $lipsum_post->ID,
					'post_title'	=> $title[1],
					'post_content'	=> str_replace( $title[0], '', $result ),
					'post_status'	=> 'publish',
					'post_type'		=> $post_type
				);

				wp_update_post( $args );
			endfor;
		endforeach;
	}
}
