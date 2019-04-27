<?php
namespace MN\Services;

use MN\System\Session;
use MN\Utils\Validator;
use MN\Repositories\UserRepository;

class Authentication {
	
	public function __construct() {
	
	}
	
	public function login($request) {
		$username = $request->request->get("username");
		$password = !empty($request->request->get("password")) ? $request->request->get("password") : (!empty($request->request->get("password_1")) ? $request->request->get("password_1") : '');
		
		$user = UserRepository::login($username, $password);
		if(!$user) return false;
		
		$session = (new Session)->setSession([
			'user' => $user,
			'is_logged_in' => true
		]);
		
		return $session;
	}
	
	public static function logout() {
		unset($_SESSION['mn_user']);
	}
	
}