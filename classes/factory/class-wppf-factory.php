<?php
namespace WPPF\Factory;

use WPPF\Factory\Posts;
use WPPF\Setting_Fields\Dashboard_Setting_Fields as Dashboard;
use WPPF\API\{
	Content,
	Thumbnail
};

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
	 * Thumbnail extension
	 * @access private
	 * @var string
	 *
	 * @since 1.0.0
	 */
	private $thumbnail_extension;

	/**
	 * Construct
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
		$dashboard 					= new Dashboard;
		$this->nonce 				= $dashboard->nonce;
		$this->thumbnail_extension 	= 'jpg';

		add_action( 'admin_init', array( $this, 'create' ) );
	}

	/**
	 * Create posts
	 *
	 * @since 1.0.0
	 */
	public function create()
	{
		if ( ! isset( $_POST[ $this->nonce ] )
			|| ! wp_verify_nonce( $_POST[ $this->nonce ], $this->nonce ) )
			return;

		$posts = new Posts;

		foreach ( $posts->posts() as $key => $value ) :
			if ( ! array_key_exists( $key, $_POST ) )
				continue;

			$content_placeholders = array(
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

			for ( $i = 0; $i < $amount; $i++ ) :
				$content = $this->content( $content_placeholders );
				$new_post = $this->set_post( $content, $post_type );
				$this->set_thumbnail( $new_post );
			endfor;
		endforeach;
	}

	/**
	 * Set post
	 * @param string $content	The post content
	 * @param string $post_type Post Type
	 * @return int 				Post ID
	 *
	 * @since 1.0.1
	 */
	private function set_post( $content, $post_type )
	{
		preg_match( '`<h1>(.*?)</h1>`im', $content, $title );
		$new_post = get_default_post_to_edit( $post_type, true );
		$args = array(
			'ID'			=> $new_post->ID,
			'post_title'	=> $title[1],
			'post_content'	=> str_replace( $title[0], '', $content ),
			'post_status'	=> 'publish',
			'post_type'		=> $post_type
		);

		return wp_update_post( $args );
	}

	/**
	 * Set the post thumbnail
	 * @param int $post_id 		ID of parent post
	 *
	 * @since 1.0.0
	 */
	private function set_thumbnail( $post_id )
	{
		$post_type = get_post_type( $post_id );
		if ( ! post_type_supports( $post_type, 'thumbnail' ) )
			return;

		$thumbnail_placeholders = array(
			get_option( 'large_size_w' ),
			get_option( 'large_size_h' )
		);
		$image = 'thumbnail-'. time() .'-'. $post_id .'.'. $this->thumbnail_extension;
		$upload 	= wp_upload_dir();
		$filename	= $upload['path'] . '/'. $image;
		$this->thumbnail( $thumbnail_placeholders, $filename );

		// Attach the new generated image to the current post
		$args = array(
			'post_title'		=> $image,
			'post_status'		=> 'inherit',
			'post_content'		=> '',
			'guid'				=> $upload['url'] .'/'. $image,
			'post_mime_type'	=> 'image/'. $this->thumbnail_extension
		);
		$attachment_id = wp_insert_attachment( $args, $filename, $post_id );

		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
		wp_update_attachment_metadata( $attachment_id, $attachment_data );

		// Set the post thumbnail
		update_post_meta( $post_id, '_thumbnail_id', $attachment_id );
	}

	/**
	 * Get the content
	 * @param array $placeholders 	Array of Placeholder
	 * @link https://loripsum.net/
	 *
	 * @since 1.0.0
	 */
	private function content( $placeholders )
	{
			$request = join( '/', $placeholders );
			$content = new Content;

			return $content->api( $request );
	}

	/**
	 * Get the thumbnail
	 * @param array $placeholders 	Array of Placeholder
	 * @link https://picsum.photos/
	 * @param string $image 		path/to/file.extension
	 *
	 * @since 1.0.0
	 */
	private function thumbnail( $placeholders, $filename )
	{
			$request 	= join( '/', $placeholders );
			$request 	= $request .'.'. $this->thumbnail_extension;
			$thumbnail	= new Thumbnail;

			return $thumbnail->api( $request, $filename );
	}
}
