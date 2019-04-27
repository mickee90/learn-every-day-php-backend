<?php

namespace MN\Controllers\Api;

use MN\System\Core;
use MN\Middleware\ContentType;
use MN\Middleware\Authentication;
use MN\Services\Response;
use MN\Services\Request;

class Controller {
	
	/**
	 * Holds which HTTP methods are allowed for a specific request
	 *
	 * @override
	 * @const array
	 */
	const VALID_METHODS = [];
	
	/** @var Request */
	public $request;
	
	/** @var Response */
	public $response;
	
	/** @var Authentication */
	public $auth;
	
	/** @var ContentType */
	public $content_type_middleware;
	
	public $ignore_auth = false;
	
	public function __construct() {
		$this->request = new Request();
		$this->response = new Response();
		$this->auth = new Authentication();
		
		$content_type_middleware = new ContentType();
		$this->response->setHeaders([
			'Content-Type' => $content_type_middleware->content_type,
			'Access-Control-Allow-Credentials' => true,
			'Access-Control-Allow-Origin' => '*',
			'Access-Control-Allow-Methods' => implode(',', static::VALID_METHODS),
			'Access-Control-Allow-Headers' => 'Authorization, Content-Type, Origin, X-Requested-With'
		]);
		
		if(!$this->ignore_auth && $this->request->server->get('REQUEST_METHOD') !== 'OPTIONS') $this->auth->call();
	}
	
	protected function options() {
		return $this->response->setStatusCode(200)->send();
	}
	
	public function index(...$args) {
		$method = $this->request->server->get('REQUEST_METHOD');
		
		if(!in_array($method, static::VALID_METHODS)) {
			$this->response->setContent("The method $method is not allowed for this request.")->setStatusCode(405)->send();
			// throw new E\ApiRequestException(
			// 	["The method '%s' is not allowed for this request.", $method],
			// 	Response::HTTP_METHOD_NOT_ALLOWED
			// );
		}
		
		$method = strtolower($method);
		if(!method_exists($this, $method)) {
			$this->response->setContent('Invalid endpoint')->setStatusCode(405)->send();
			// throw new E\ApiRequestException('Invalid endpoint', Response::HTTP_BAD_REQUEST);
		}
		
		return call_user_func_array([$this, $method], $args);
		
		
		
		// $method = $this->validateMethods(static::VALID_METHODS)->methodExists();
		// $this->{$method}($id);
	}
	
	public function validateMethods($methods) {
		$request_method = $this->request->server->get('REQUEST_METHOD');
		
		if(empty($request_method) || !in_array($request_method, $methods)) {
			$this->response->setStatusCode(405)->send();
		}
		
		return $this;
	}
	
	public function methodExists() {
		$method = strtolower($this->request->server->get('REQUEST_METHOD'));
		if(!method_exists($this, $method)) {
			$this->response->setStatusCode(405)->send();
		}
		
		return $method;
	}
}