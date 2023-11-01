<?php
namespace WPPF\API;

/**
 * Thumbnail. Lorem Picsum API
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
	 * @param string $filename 	path/to/file.extension
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function api( $request, $filename )
	{
		$fp		= fopen( $filename, 'w+' );
		$curl 	= curl_init();

		curl_setopt_array( $curl, array(
						CURLOPT_URL 			=> $this->url . $request,
						CURLOPT_FILE			=> $fp,
						CURLOPT_USERAGENT		=> $_SERVER['HTTP_USER_AGENT'],
						CURLOPT_RETURNTRANSFER 	=> true,
						CURLOPT_ENCODING 		=> '',
						CURLOPT_MAXREDIRS 		=> 10,
						CURLOPT_TIMEOUT 		=> 1000,
						CURLOPT_FOLLOWLOCATION 	=> true,
						CURLOPT_HTTP_VERSION 	=> CURL_HTTP_VERSION_2_0,
						CURLOPT_CUSTOMREQUEST 	=> 'GET'
		) );

		$raw = curl_exec( $curl );
		curl_close( $curl );

		fwrite( $fp, $raw );
		fclose( $fp );
	}

}
