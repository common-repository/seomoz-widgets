<?php

/**
 *
 * Utility Class to make a GET HTTP connection
 * to the given url and pass the output
 *
 */
class ConnectionUtil
{
	const CURL_CONNECTION_TIMEOUT = 120;

	/**
	 *
	 * Method to make a GET HTTP connecton to
	 * the given url and return the output
	 *
	 * @param urlToFetch url to be connected
	 * @return the decoded JSON http get response
	 */
	public static function makeRequest($urlToFetch)
	{
		$response = wp_remote_get( $urlToFetch );

		// Check for Wordpress Error
		if( is_wp_error( $response ) ) {
			return false;
		}

		// Check for errored response
		if (array_key_exists('response', $response) &&
				array_key_exists('code', $response["response"]) &&
				$response["response"]["code"] != "200") {
			return false;
		}

		$data = wp_remote_retrieve_body( $response );

		if (!function_exists('json_decode')) {
			$result = require_once 'JSON.php';
			$json = new Services_JSON;
			return $json->decode($data);
		} else {
			return json_decode($data);
		}

		return false;
	}

}

?>