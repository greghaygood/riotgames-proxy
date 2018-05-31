<?php

define( 'API_BASE', 'https://na1.api.riotgames.com' );

session_start();

$endpoint = @$_REQUEST['_ep'];
header('Content-Type: application/json');
header('Access-Control-Allow-Headers: Authorization');
header("Access-Control-Allow-Origin: *");

// respond to preflights
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // return only the headers and not the content
    // only allow CORS if we're doing a GET - i.e. no saving for now.
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']) && $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'GET') {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization');
    }
    exit;
}

if (!empty($endpoint)) {
  $apikey = $_REQUEST['api_key'];
    if (empty($apikey) || strlen($apikey) < 8) {
        error('No API key provided');
    }
    echo request(API_BASE . $endpoint, $_REQUEST);
} else {
    error('No endpoint requested');
}
function error($message)
{
    http_response_code(400);
    echo json_encode(array('error' => $message));
    die;
}

/**
 * Makes a request to the API and returns the response
 *
 * @param    $base_url    The full URL of the API endpoint
 * @param    $url_params    Array of query-string parameters.
 *
 * @return   The JSON response from the request
 */
function request( $base_url, $url_params = array() ) {
	try {
		$curl = curl_init();
		if ( false === $curl ) {
			throw new Exception( 'Failed to initialize' );
		}

		$url = $base_url . "?" . http_build_query( $url_params );
		curl_setopt_array( $curl, array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,  // Capture response.
			CURLOPT_ENCODING       => "",  // Accept gzip/deflate/whatever.
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => "GET",
			CURLOPT_HTTPHEADER     => array(
				"cache-control: no-cache",
			),
		) );

		$response = curl_exec( $curl );

		if ( false === $response ) {
			throw new Exception( curl_error( $curl ), curl_errno( $curl ) );
		}
		$http_status = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		if ( 200 != $http_status ) {
			throw new Exception( $response, $http_status );
		}

		curl_close( $curl );
	} catch ( Exception $e ) {
		trigger_error( sprintf(
			'Curl failed with error #%d: %s',
			$e->getCode(), $e->getMessage() ),
			E_USER_ERROR );
	}

	return $response;
}
