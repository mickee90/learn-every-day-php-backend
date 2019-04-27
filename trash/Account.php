<?php

namespace MN\Entities;

class Account {
	
	/** @var int */
	public $id;
	
	/** @var int */
	public $type = 2;
	
	/** @var string */
	public $type_name;
	
	/** @var string */
	public $username;
	
	/** @var string */
	public $password;
	
	/** @var string */
	public $first_name;
	
	/** @var string */
	public $last_name;
	
	/** @var string */
	public $address;
	
	/** @var string */
	public $zip_code;
	
	/** @var string */
	public $city;
	
	/** @var string */
	public $email;
	
	/** @var string */
	public $phone;
	
	/** @var string */
	public $updated;
	
	/** @var array */
	public $history = [];
	
	/** @var array */
	public $unpublished = [];
	
	/** @var array */
	public $published = [];
	
	/** @var array */
	public $deleted = [];
	
	public function __construct() {
	
	}
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @param int $id
	 * @return Account
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * @param string $username
	 * @return Account
	 */
	public function setUsername($username) {
		$this->username = $username;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * @param string $password
	 * @return Account
	 */
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->first_name;
	}
	
	/**
	 * @param string $first_name
	 * @return Account
	 */
	public function setFirstName($first_name) {
		$this->first_name = $first_name;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->last_name;
	}
	
	/**
	 * @param string $last_name
	 * @return Account
	 */
	public function setLastName($last_name) {
		$this->last_name = $last_name;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}
	
	/**
	 * @param string $address
	 * @return Account
	 */
	public function setAddress($address) {
		$this->address = $address;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getZipCode() {
		return $this->zip_code;
	}
	
	/**
	 * @param string $zip_code
	 * @return Account
	 */
	public function setZipCode($zip_code) {
		$this->zip_code = $zip_code;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}
	
	/**
	 * @param string $city
	 * @return Account
	 */
	public function setCity($city) {
		$this->city = $city;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * @param string $email
	 * @return Account
	 */
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getPhone() {
		return $this->phone;
	}
	
	/**
	 * @param string $phone
	 * @return Account
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getUpdated() {
		return $this->updated;
	}
	
	/**
	 * @param string $updated
	 * @return Account
	 */
	public function setUpdated($updated) {
		$this->updated = $updated;
		return $this;
	}
	
}