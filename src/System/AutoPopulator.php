<?php

namespace MN\System;

use \ReflectionObject;
use \ReflectionProperty;
use \stdClass;

class AutoPopulator {
	
	/** @var ReflectionObject */
	private $instance;
	
	/** @var array|mixed */
	private $properties;
	
	/** @var stdClass */
	private $data;
	
	// private $class;
	
	/**
	 * AutoPopulator constructor.
	 *
	 * @param $class
	 */
	public function __construct($class) {
		
		$this->class = $class;
		
		$this->instance = new $class;
		
		$reflection = new ReflectionObject($this->instance);
		
		$this->properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
	}
	
	/**
	 * Populate the properties with database values
	 *
	 * @return ReflectionObject
	 */
	public function start() {
		
		foreach ($this->properties as $property) {
			
			$propName = $property->getName();
			
			if(property_exists($this->data, $propName)) {
				$value = $this->data->{$propName};
				$modifiedKey = $this->toCamelCase($propName);
				
				if(method_exists($this->instance, 'set'.$modifiedKey)) {
					call_user_func([
						$this->instance,
						'set'.$modifiedKey
					], $value);
				} else {
					$this->instance->{$propName} = $value;
				}
			}
		}
		
		return $this->instance;
	}
	
	/**
	 * @return mixed
	 */
	public function getData() {
		return $this->data;
	}
	
	/**
	 * @param mixed $data
	 * @return AutoPopulator
	 */
	public function setData($data) {
		$this->data = $data;
		return $this;
	}
	
	/**
	 * Adjust properties from db row stdClass to camelCase
	 *
	 * @return $this
	 */
	public function adjustKeys() {
		
		$content = [];
		
		foreach ($this->data as $key => $value) {
			
			$modifiedKey = $this->toCamelCase($key);
			$content[$modifiedKey] = $value;
			unset($this->data->{$key});
		}
		
		foreach ($content as $key => $value) {
			$this->data->{$key} = $value;
		}
		
		return $this;
	}
	
	/**
	 * @param $str
	 * @return mixed
	 */
	private function toCamelCase($str) {
		return str_replace('_', '', lcfirst(ucwords($str, '_')));
	}
}