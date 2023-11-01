<?php
namespace WPPF\Factory;

/**
 * Posts
 *
 * @since 1.0.0
 */
class Posts
{

	/**
	 * Get the public post types
	 * @return array 	Associative array for each public post type (but attachments)
	 * 					[post_type_{post-type-slug}] => [
	 * 						'type'	=> 'post-type-slug'
	 * 						'label'	=> 'Post Type Label'
	 * 					]
	 *
	 * @since 1.0.0
	 */
	public function posts()
	{
		$args = array(
			'public'	=> true
		);
		$get_post_types = get_post_types( $args, 'objects' );
		foreach ( $get_post_types as $post => $value ) :
			// No attachment support
			if ( 'attachment' == $post )
				continue;
			$post_types[ 'post_type_'. $post ] = array(
				'type'	=> $post,
				'label'	=> $value->label
			);
		endforeach;

		return $post_types;
	}
}
