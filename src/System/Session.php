<?php

namespace MN\System;

use MN\Entities\User;

class Session {
	
	/** @var int */
	protected $last_activity;
	
	/**
	 * 1 = admin, 2 = user
	 * @var int
	 */
	protected $user_type = 0;
	
	/** @var int */
	protected $user_id;
	
	protected static $_instance = null;
	
	public function __construct() {
	
	}
	
	public function setSession($args) {
		/** @var User $user */
		$user = (isset($args['user']) && !empty($args['user']->id)) ? $args['user'] : '';
		if(!empty($user)) {
			$this->user_id = $user->id;
			$this->user_type = $user->type;
			$this->last_activity = time();
			$_SESSION['mn_user'] = serialize($this);
		}
		return !empty($user) ? true : false;
	}
	
	public static function getSession() {
		return isset($_SESSION['mn_user']) ? unserialize($_SESSION['mn_user']) : new self();
	}
	
	public static function getInstance() {
		if(!self::$_instance instanceof self) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public static function isLoggedIn() {
		return !empty((self::getSession())->user_id) ? true : false;
	}
	
	public static function getUserId() {
		return !empty((self::getSession())->user_id) ? (self::getSession())->user_id : false;
	}
	
	public function userType() {
		return (self::getSession())->user_type;
	}
	
	public static function userLoggedIn() {
		return (self::getSession())->user_type == 2 ? true : false;
	}
	
	public static function adminLoggedIn() {
		return (self::getSession())->user_type == 1 ? true : false;
	}
}