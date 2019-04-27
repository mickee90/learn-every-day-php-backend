<?php

namespace MN\System;

use Twig_Environment;
use Twig_Extensions_Extension_Intl;
use Twig_Loader_Filesystem;
use MN\Utils\FlashMessages;

class Controller {
	
	public $flash_message;
	
	public function __construct() {
		$this->flash_message = new FlashMessages();
	}
	
	public function run($router) {
		$controller = new $router->class();
		if (!empty($router->args[0])) {
			$controller->{$router->method}($router->args[0]);
		} else {
			$controller->{$router->method}();
		}
	}
	
	public function render($view_file, $args = []) {
		$loader = new Twig_Loader_Filesystem(MN_DIR_VIEWS);
		$twig = new Twig_Environment($loader, array(
			'debug' => true,
		));
		
		// Create url function for css, js and gfx
		foreach (['css', 'js', 'gfx'] as $folder) {
			$twig->addFunction(new \Twig_Function($folder, function ($file) use ($folder) {
				return pathMerge("/Resources", $folder, $file);
			}));
		}
		
		$twig->addFunction(new \Twig_Function('menu', function() {
			$user_session = (Session::getInstance())->getSession();
			
			if($user_session::isLoggedIn()) {
				$file = ($user_session->userType() == 1) ? 'admin_menu.twig' : 'user_menu.twig';
			} else {
				$file = 'default_menu.twig';
			}
			return pathMerge('include/', $file);
		}));
		
		//$twig->addExtension(new Twig_Extensions_Extension_Intl());
		
		
		$template = $twig->load($view_file);
		echo $template->render($args);
	}
	
	public function redirect($url) {
		header("Location: " . $url);
		exit;
	}
	
	/**
	 * Check if request is made with AJAX
	 *
	 * @param string $method (optional) Check if request was GET or POST
	 * @return bool
	 */
	public function isAjax($method = null) {
		return ((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))
			&& (is_null($method) || $_SERVER['REQUEST_METHOD'] == strtoupper($method));
	}
	
	public function isPost() {
		return ($_SERVER['REQUEST_METHOD'] == 'POST') ? true : false;
	}
	
}