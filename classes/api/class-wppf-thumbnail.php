<?php
namespace WPPF\API;

/**
 * Lorem Picsum
 * @link https://picsum.photos/
 *
 * @since 1.0.0
 */
class Thumbnail
{

	/**
	 * API URL
	 * @access private
	 * @var string
	 *
	 * @since 1.0.0
	 */
	private $url = 'https://picsum.photos/';

	/**
	 * API Request
	 * @param string $request 	API Request
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function api( $request )
	{
		$curl = curl_init();

		curl_setopt_array( $curl, array(
						CURLOPT_URL 			=> $this->url( $request ),
						CURLOPT_RETURNTRANSFER 	=> true,
						CURLOPT_ENCODING 		=> 'gzip',
						CURLOPT_MAXREDIRS 		=> 10,
						CURLOPT_TIMEOUT 		=> 0,
						CURLOPT_FOLLOWLOCATION 	=> true,
						CURLOPT_HTTP_VERSION 	=> CURL_HTTP_VERSION_2_0,
						CURLOPT_CUSTOMREQUEST 	=> 'GET',
		) );

		$response = curl_exec( $curl );

		curl_close( $curl );
		return $response;
	}

	/**
	 * Build the request URL
	 *
	 * @since 1.0.0
	 */
	public function url( $request )
	{
		return $this->url . $request;
	}

}
