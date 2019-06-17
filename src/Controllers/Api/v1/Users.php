<?php

namespace MN\Controllers\Api\v1;

use MN\Entities\User;
use MN\Controllers\Api\Controller;
use MN\Services\Paginator;
use MN\Repositories\UserRepository;
use MN\Services\UserForm;
use MN\Services\ApiFilter;
use function MN\System\toCamelCase;

class Users extends Controller {
	
	const VALID_METHODS = ['GET', 'POST', 'PATCH', 'DELETE', 'OPTIONS'];

	public $ignore_auth = true;
	
	protected function get($uuid) {
		$user_repository = new UserRepository();
		
		$users = !empty($uuid) ? $user_repository->getByUuid($uuid) : $user_repository->getAll([['deleted', ' = ', 0, '']]);
		
		if(empty($users)) $this->response->setStatusCode(404)->send();
		
		// if(!is_array($users)) $users = [$users];
		
		$this->response->setStatusCode(200)->setContent($users)->send();
	}
	
	protected function post() {
		
		if(!$username = $this->request->request->get("username")) {
			$this->response->setContent('The username is missing')->send();
		}
		
		if(!$password = $this->request->request->get("password")) {
			$this->response->setContent('The password is missing')->send();
		}
		
		if(!$password_2 = $this->request->request->get("password_2")) {
			$this->response->setContent('Verify the password')->send();
		}

		if($password !== $password_2) {
			$this->response->setContent('The passwords does not match')->send();
		}
		
		if(!$first_name = $this->request->request->get("first_name")) {
			$this->response->setContent('The first name is missing')->send();
		}
		
		if(!$last_name = $this->request->request->get("last_name")) {
			$this->response->setContent('The last name is missing')->send();
		}
		
		if(!$email = $this->request->request->get("email")) {
			$this->response->setContent('The email is missing')->send();
		}
		/* 
		if(!$country_id = $this->request->request->get("country_id")) {
			$this->response->setContent('The country is missing')->send();
		} */
		
		$user = new User();
		$user->username = $username;
		$user->password = $password;
		$user->first_name = $first_name;
		$user->last_name = $last_name;
		$user->email = $email;
		$user->country_id = 1;
		
		$user = UserRepository::create($user);
		
		if(empty($user->id)) $this->response->setContent('Something went wrong. Please try again')->send();
		
		$this->response->setStatusCode(200)->setContent($user)->send();
	}
	
	// public function put($id = 0) {
	// 	if($id === 0)
	// 		$this->response->setContent('Id is missing')->send();
	//
	// 	$user = UserRepository::getById($id);
	// 	$form = new UserForm($this->request, true);
	// 	$form->edit($user);
	//
	// 	if(!$form->isValidated())
	// 		$this->response->setContent($form->getErrors())->send();
	//
	// 	$user = UserRepository::edit($form->getUser());
	// 	if(empty($user->id))
	// 		$this->response->setContent('Något gick fel. Prova igen')->send();
	//
	// 	$this->response->setStatusCode(200)->setContent('Användaren är uppdaterad')->send();
	// }
	
	protected function patch($uuid = '') {
		$update_props = [];
		
		if(empty($uuid)) $this->response->setStatusCode(404)->setContent('The Uuid is missing')->send();
		
		if($username = $this->request->request->get("username")) {
			$update_props['username'] = $username;
		}
		
		if($password = $this->request->request->get("password")) {
			$update_props['password'] = $password;
		}
		
		if($first_name = $this->request->request->get("first_name")) {
			$update_props['first_name'] = $first_name;
		}
		
		if($last_name = $this->request->request->get("last_name")) {
			$update_props['last_name'] = $last_name;
		}
		
		if($email = $this->request->request->get("email")) {
			$update_props['email'] = $email;
		}
		
		if($country_id = $this->request->request->get("country_id")) {
			$update_props['country_id'] = $country_id;
		}
		
		if(empty($update_props)) $this->response->setStatusCode(404)->setContent('Props to update is missing')->send();
		
		$user = UserRepository::getByUuid($uuid);
		$user->setByProps($update_props);
		
		$user = UserRepository::update($user);
		
		if(empty($user->id)) $this->response->setContent('Something went wrong. Please try again')->send();
		
		$this->response->setStatusCode(200)->setContent($user)->send();
	}
	
	protected function delete($id = 0) {
		if((int)$id == 0) $this->response->setStatusCode(404)->setContent('The Id is missing')->send();
		
		$user = UserRepository::getById($id);
		$user->disabled = 1;
		$user = UserRepository::update($user);
		
		if(empty($user->id)) $this->response->setContent('Something went wrong. Please try again')->send();
		
		$this->response->setStatusCode(200)->setContent($user)->send();
	}
}