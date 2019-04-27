<?php
namespace MN\Middleware;

use MN\Services\Request;
use MN\Services\Response;

class ContentType {
	
	/** @var string */
	public $content_type;

	public function __construct() {
		$request = new Request();
		$response = new Response();
		
		$content_type = !empty($request->server->get('CONTENT_TYPE')) ? $request->server->get('CONTENT_TYPE') : 'application/json';
		
		if(!in_array($content_type, ['application/json', 'application/xml'])) $response->setStatusCode(415)->send();
		
		$this->content_type = $content_type;
	}
	
	/**
	 * @return string
	 */
	public function getContentType() {
		return $this->content_type;
	}
	
	/**
	 * @param string $content_type
	 * @return ContentType
	 */
	public function setContentType($content_type) {
		$this->content_type = $content_type;
		return $this;
	}
	
	
	
}