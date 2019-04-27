<?php
/**
 * Created by PhpStorm.
 * User: mikaelnilsson
 * Date: 2018-10-21
 * Time: 20:02
 */

namespace MN\Services;

class ParameterBag {
	
	protected $parameters;
	
	public function __construct($parameters = array()) {
		$this->parameters = $parameters;
	}
	
	public function all() {
		return $this->parameters;
	}
	
	public function keys() {
		return array_keys($this->parameters);
	}
	
	public function replace(array $parameters = array()) {
		$this->parameters = $parameters;
	}
	
	public function get($key) {
		return isset($this->parameters[$key]) ? $this->parameters[$key] : '';
	}
	
	public function set($key, $value) {
		$this->parameters[$key] = $value;
	}
	
	public function has($key) {
		return array_key_exists($key, $this->parameters);
	}
	
	public function remove($key) {
		unset($this->parameters[$key]);
	}
	
}