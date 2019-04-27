<?php
/**
 * Created by PhpStorm.
 * User: mikaelnilsson
 * Date: 2018-10-21
 * Time: 19:31
 */

namespace MN\Services;

class Request {
	
	public $request;
	
	public $query;
	
	public $cookie;
	
	public $files;
	
	public $server;
	
	public $headers;
	
	public function __construct() {
		if(!empty($_POST)) {
			$this->request = new ParameterBag($_POST);
		} else if($input_php = @file_get_contents("php://input")) {
			$input = json_decode($input_php);
			$this->request = new ParameterBag((array)$input);
		} else {
			$this->request = new ParameterBag();
		}
		$this->query = !empty($_GET) ? new ParameterBag($_GET) : new ParameterBag();
		$this->cookie = !empty($_COOKIE) ? new ParameterBag($_COOKIE) : new ParameterBag();
		$this->files = !empty($_FILES) ? new ParameterBag($_FILES) : new ParameterBag();
		$this->server = !empty($_SERVER) ? new ParameterBag($_SERVER) : new ParameterBag();
		$header = $this->apache_request_headers();
		$this->headers = !empty($header) ? new ParameterBag($header) : new ParameterBag();
	}
	
	public function get($key) {
	
	}
	
	public function isSubmitted() {
		return isset($this->request);
	}

	public function apache_request_headers() {
		$arh = array();
		$rx_http = '/\AHTTP_/';
		foreach($_SERVER as $key => $val) {
			if( preg_match($rx_http, $key) ) {
				$arh_key = preg_replace($rx_http, '', $key);
				$rx_matches = array();
				$rx_matches = explode('_', $arh_key);
				if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
					foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
					$arh_key = implode('-', $rx_matches);
				}
				$arh[$arh_key] = $val;
			}
		}
		return( $arh );
	}
}