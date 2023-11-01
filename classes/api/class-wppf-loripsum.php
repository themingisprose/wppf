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
		$curl = curl_init();

		curl_setopt_array( $curl, array(
						CURLOPT_URL 			=> $this->url . $request,
						CURLOPT_RETURNTRANSFER 	=> true,
						CURLOPT_ENCODING 		=> 'gzip',
						CURLOPT_MAXREDIRS 		=> 10,
						CURLOPT_TIMEOUT 		=> 0,
						CURLOPT_FOLLOWLOCATION 	=> true,
						CURLOPT_HTTP_VERSION 	=> CURL_HTTP_VERSION_2_0,
						CURLOPT_CUSTOMREQUEST 	=> 'GET',
						CURLOPT_HTTPHEADER => array(
										'Content-type: text/plain; charset=utf-8'
						),
		) );

		$response = curl_exec( $curl );

		curl_close( $curl );
		return $response;
	}
}
