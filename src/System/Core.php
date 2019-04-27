<?php
namespace MN\System;


class Core {
	
	/** @var self */
	private static $_instance;
	
	/** @var Connection */
	protected $db;
	
	private function __construct() {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		ini_set('xdebug.var_display_max_depth', -1);
		ini_set('xdebug.var_display_max_children', -1);
		ini_set('xdebug.var_display_max_data', -1);
		
		error_reporting(E_ALL);
		
		session_start();
		
		require('const.php');
		require('helpers.php');
		require('Autoloader.php');
		require(MN_DIR.'vendor/autoload.php');
		
		(new Autoloader())->register();
		
		$this->db = new Connection();
	}
	
	public static function getInstance() {
		if(!self::$_instance instanceof self) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function run() {
		$router = (new Router())->setRouter();
		(new Controller())->run($router);
	}
	
	public function getDb() {
		return $this->db;
	}
}