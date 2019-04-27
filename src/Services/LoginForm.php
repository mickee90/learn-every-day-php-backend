<?php

namespace MN\Services;

use MN\Utils\Validator;

class LoginForm extends Form {
	
	public function login() {
		
		if (!Validator::username($this->request->request->get("username"))) {
			$this->errors[] = "Användarnamn saknas";
		}
		
		if (!Validator::notEmpty($this->request->request->get("password"))) {
			$this->errors[] = "Lösenord saknas";
		}
		
		if (!empty($this->request->request->get("submit"))){
			$this->setIsSubmitted(true);
		}
		
		if (count($this->errors) === 0) {
			$this->setValidated(true);
		}
	}
}