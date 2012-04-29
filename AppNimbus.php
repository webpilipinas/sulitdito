<?php

class AppNimbus {

	var $app_key;
	var $app_secret;

	public function __construct($key, $secret) {
		$this->app_key = $key;
		$this->app_secret = $secret;
	}
	
	public function _restCall($controller, $action, $payload_array) {
		$app_key = $this->app_key;
		$app_secret = $this->app_secret;
		
		$api_url = "http://api.appnimbus.local/{$controller}/{$action}";
		$ch = curl_init();
		
		$signature = "POST\n" . $api_url . "\nbody_sha1=". sha1(json_encode($payload_array)); 
		$query = "app_key={$app_key}";
		
		$auth_signature = hash_hmac('sha256', $signature.$query, $app_secret, false);
		$query .= "&auth_signature={$auth_signature}";
		
		$api_url .= "?{$query}";
		
		curl_setopt($ch, CURLOPT_URL, $api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload_array));
		
		$results = curl_exec($ch);
		
		if( $results == false ) {
			$results = curl_error($ch);
		}
		
		curl_close( $ch );
		if( !is_null(json_decode($results)) ) {
			$results = json_decode($results, true);
		}

		return $results;
	}
}