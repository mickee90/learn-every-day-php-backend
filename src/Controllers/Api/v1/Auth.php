<?php

namespace MN\Controllers\Api\v1;

use MN\Controllers\Api\Controller;
use MN\Repositories\UserRepository;

class Auth extends Controller {
	
	const VALID_METHODS = ['POST', 'OPTIONS'];
	
	public $ignore_auth = true;
	
	protected function post() {
		
		if(!$username = $this->request->request->get("username")) {
			$this->response->setContent('The username is missing')->send();
		}
		
		if(!$password = $this->request->request->get("password")) {
			$this->response->setContent('The password is missing')->send();
		}

		$user = UserRepository::login($username, $password);
		
		if(empty($user)) {
			$this->response->setStatusCode(400)->setContent('Invalid credentials')->send();
		} else {
			$this->response->setStatusCode(200)->setContent($user)->send();
		}
	}
}