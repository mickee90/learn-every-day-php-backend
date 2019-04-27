<?php
namespace MN\Utils;

class Url {
	
	public $full_url;
	
	public $scheme;
	
	public $base_url;
	
	public $uri;
	
	public $params;
	
	public function __construct($url = '') {
		if(!empty($url)) {
			$this->full_url = $url;
			$this->scheme = parse_url($url, PHP_URL_SCHEME);
			$this->base_url = parse_url($url, PHP_URL_HOST);
			$this->uri = parse_url($url, PHP_URL_PATH);
			$this->params = parse_url($url, PHP_URL_QUERY);
		}
	}
	
	public static function splitGetStringParams($params) {
		parse_str($params, $gets);
		return $gets;
	}
	
	public static function currentPathUrl() {
		return $_SERVER['REQUEST_URI'];
	}
	
	public static function redirectUri() {
		return trim(MN_HTTP_URI, '/');
	}
}