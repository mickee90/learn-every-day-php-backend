<?php

namespace MN\Entities;

use MN\System\Model;

class User extends Model {
	
	/** @var int */
	public $id;
	
	/** @var string */
	public $uuid;
	
	/** @var int */
	public $user_type_id = 3;
	
	/** @var string */
	public $username;
	
	/** @var string */
	public $password;
	
	/** @var string */
	public $first_name;
	
	/** @var string */
	public $last_name;
	
	/** @var string */
	public $address = '';
	
	/** @var string */
	public $zip_code = '';
	
	/** @var string */
	public $city = '';
	
	/** @var string */
	public $email = '';
	
	/** @var string */
	public $phone = '';
	
	/** @var bool */
	public $disabled = false;
	
	/** @var bool */
	public $banned = false;
	
	/** @var string */
	public $auth_token = '';
	
	/** @var string */
	public $auth_token_expire;
	
	/** @var string */
	public $auth_refresh_token;
	
	/** @var int */
	public $country_id = 1;
	
	/** @var string */
	protected $updated;
	
	/** @var string */
	protected $created;
	
	public function __construct() {
	
	}
	
	/**
	 * @return string
	 */
	public function getUpdated() {
		return $this->updated;
	}
	
	/**
	 * @param string $updated
	 * @return User
	 */
	public function setUpdated($updated) {
		$this->updated = $updated;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getCreated() {
		return $this->created;
	}
	
	/**
	 * @param string $created
	 * @return User
	 */
	public function setCreated($created) {
		$this->created = $created;
		return $this;
	}
	
	
	
}