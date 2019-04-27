<?php

namespace MN\System;

use http\Exception;
use MN\Services\Response;
use MN\Utils\Url;

class Router {
	
	/** @var string */
	public $url;
	
	/** @var string */
	public $file = MN_DIR_CONTROLLERS . "/index" . EXT;
	
	/** @var string */
	public $class = MN.'\Controllers\index';
	
	/** @var string */
	public $method = "notFound";
	
	/** @var array */
	public $args = [];
	
	/** @var array */
	public $get = [];
	
	public function __construct() {
		$this->url = new Url(MN_HTTP_FULL_URL);
	}
	
	public function setRouter() {
		$path = $this->url->uri;
		$dir = MN_DIR_CONTROLLERS;
		$namespace = MN.'\Controllers';
		
		$path = $this->checkRedirect($path);
		
		$path = trim($path, '/');
		if(empty($path)) $path = 'index';
		
		$chunks = explode("/", $path);
		
		
		// Find Controller folder
		foreach($chunks AS $chunk) {
			if(is_dir($dir.$chunk)) {
				$dir = $dir.$chunk.DS;
				$namespace = $namespace."\\".$chunk;
				array_shift($chunks);
				
			}
		}
		
		// Find Controller file
		if(is_readable($dir.$chunks[0].EXT)) {
			$file = $dir.$chunks[0].EXT;
			$class = $namespace."\\".$chunks[0];
			array_shift($chunks);
			
			if(sizeof($chunks) === 0) $chunks[] = 'index';
			$method = ucfirst($chunks[0]);
			
			
			if(!method_exists(new $class, $method)) {
				$method = 'index';
			} else {
				array_shift($chunks);
			}
			
			if(method_exists(new $class, $method)) {
				
				$this->class = $class;
				$this->file = $file;
				$this->method = $method;
				$this->args = $chunks;
				$this->get = $this->url->splitGetStringParams($this->url->params);
			}
			
		}
		
		if($this->method === 'notFound')
			$this->checkApiCall();
		
		return $this;
	}
	
	public function checkApiCall() {
		if((bool)preg_match('|^/api/v.*$|', MN_HTTP_URI))
			(new Response())->setStatusCode(404)->setContent('API endpoint not found')->send();
	}
	
	public function checkRedirect($path) {
		$redirects = [
			'/notAuthorized' => '/index/notAuthorized'
		];
		
		return (array_key_exists($path, $redirects)) ? $redirects[$path] : $path;
	}
	
}