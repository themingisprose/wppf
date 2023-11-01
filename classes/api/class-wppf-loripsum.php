<?php
namespace WPPF\API;

/**
 * Loripsum API
 * @link https://loripsum.net/
 *
 * @since 1.0.0
 */
class Loripsum
{

	/**
	 * API URL
	 * @access private
	 * @var string
	 *
	 * @since 1.0.0
	 */
	private $url = 'https://loripsum.net/api/';

	/**
	 * API Request
	 * @param string $request 	API Request
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function api( $request )
	{
		return file_get_contents( $this->url . $request );
	}
}
