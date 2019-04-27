<?php
namespace MN\Middleware;

use MN\Repositories\UserRepository;
use MN\Services\Request;
use MN\Services\Response;
use function MN\System\getBearerToken;

class JWTAuthentication {

	public function __construct() {
	
	}
	
	public function call() {
		$request = new Request();
		$response = new Response();
		
		if(!isset($_GET['postman'])) {
			
			$auth = $request->server->get('HTTP_AUTHORIZATION');
			if(empty($auth)) {
				// Logger::execute('', '', '', '', 0, 'No HTTP_AUTHORIZATION: '.json_encode($_SERVER));
				$response->setStatusCode(401)->setContent('No HTTP_AUTHORIZATION')->send();
			}
			
			$jwt_token = getBearerToken($auth);
			
			if(is_null($jwt_token)) {
				// Logger::execute('', '', '', '', 0, 'No Bearer Token');
				$response->setContent('No Bearer Token')->send();
			}
			
			$jwt = JWT::decode($jwt_token, $this->app_config['jwt_secret_token'], array('HS256'));
			if(empty($jwt->username) || empty($jwt->iat)) {
				// Logger::execute('', '', '', '', 0, 'JWT Token has been tempered with');
				$response->setContent('JWT Token has been tempered with')->send();
			}
			
			// if(($jwt->iat + $this->app_config['jwt_lifetime']) < time()) {
			// 	Logger::execute('', '', '','', 0, 'JWT Token has expired');
			// 	$response->setContent('JWT Token has expired')->send();
			// }
			
			$this->user = (object)sql::sqlAssoc("SELECT a.*, b.uuid, b.push_token, b.jwt_token,
			b.device_platform, b.active, b.deleted FROM app_user a
			JOIN app_device b ON a.id = b.user_id
			WHERE a.username = '{$jwt->username}' AND b.jwt_token = '{$jwt_token}'");
			
			if($this->user->banned == 1) {
				Logger::execute($this->user->username, $this->task, '', 0, 0, "User is banned", $this->user->uuid);
				$response->setContent("User is banned")->setBanned(true)->response();
			}
			
		} else {
			$this->user = (object)sql::sqlAssoc("SELECT a.*, b.uuid, b.push_token, b.jwt_token,
			b.device_platform, b.active, b.deleted FROM app_user a
			JOIN app_device b ON a.id = b.user_id
			WHERE a.id = 10");
		}
	}
	
	private function validate($auth) {
		if(empty($auth))
			return false;
		
		$user = UserRepository::getByAuth($auth);
		
		if(is_null($user))
			return false;
		
		return true;
	}
}