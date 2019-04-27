<?php
namespace MN\Middleware;

use MN\Repositories\UserRepository;
use MN\Services\Request;
use MN\Services\Response;

class Authentication {
	
	public $user_id;

	public function __construct() {
	
	}
	
	public function call() {
		$request = new Request();
		$response = new Response();
		
		$auth = !empty($request->server->get('REDIRECT_HTTP_AUTHORIZATION')) ? trim(str_replace('Bearer', '', $request->server->get('REDIRECT_HTTP_AUTHORIZATION'))) : '';
		
		if(!($auth && $this->validate($auth))) $response->setStatusCode(401)->send();
	}
	
	private function validate($auth) {
		if(empty($auth)) return false;
		
		$user = UserRepository::getByAuth($auth);
		if(is_null($user)) return false;
		
		$this->user_id = $user->id;
		
		return true;
	}
}