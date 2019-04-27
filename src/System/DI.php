<?php

namespace MN\System;

class DI {
	
	private static $map;
	
	/**
	 * @param $class_name
	 * @param null $arguments
	 *
	 * @return object
	 * @throws \ReflectionException
	 */
	public static function getInstanceOf($class_name, $arguments = null) {
		// check if the class exists
		if(!class_exists($class_name))
			throw new \Exception("DI: missing class '" . $class_name . "'");
			
		$reflection = new \ReflectionClass($class_name);
		
		if(is_null($arguments) || count($arguments) == 0) {
			$obj = new $class_name;
		} else {
			if(!is_array($arguments))
				$arguments = [$arguments];
			
			$obj = $reflection->newInstanceArgs($arguments);
		}
		
		if($doc = $reflection->getDocComment()) {
			$lines = explode("\n", $doc);
			foreach($lines AS $line) {
				if(count($parts = explode("@inject", $line)) > 1) {
					$parts = explode(" ", $parts[1]);
					if(count($parts) > 1) {
						$key = $parts[1];
						$key = str_replace("\n", "", $key);
						$key = str_replace("\r", "", $key);
						if(isset(self::$map->$key)) {
							
							switch(self::$map->$key->type) {
							
								case "value":
									$obj->$key = self::$map->$key->value;
									break;
									
								case "class":
									$obj->$key = self::getInstanceOf(self::$map->$key->value, self::$map->$key->arguments);
									break;
									
								case "classSingleton":
									if(is_null(self::$map->$key->instance)) {
										$obj->$key = self::$map->$key->instance = self::getInstanceOf(self::$map->$key->valie, self::$map->$key->arguments);
									} else {
										$obj->$key = self::$map->$key->instance;
									}
									break;
							}
						}
					}
				}
			}
		}
		
		return $obj;
	}
	
	/**
	 * @param $key
	 * @param $obj
	 */
	public static function addToMap($key, $obj) {
		if(is_null(self::$map))
			self::$map = (object)[];
		
		self::$map->$key = $obj;
	}
	
	/**
	 * @param $key
	 * @param $value
	 */
	public static function mapValue($key, $value) {
		self::addToMap($key, (object)[
			'value' => $value,
			'type' => 'value'
		]);
	}
	
	/**
	 * @param $key
	 * @param $value
	 * @param null $arguments
	 */
	public static function mapClass($key, $value, $arguments = null) {
		self::addToMap($key, (object)[
			'value' => $value,
			'type' => 'value',
			'arguments' => $arguments
		]);
	}
	
	/**
	 * @param $key
	 * @param $value
	 * @param null $arguments
	 */
	public static function mapClassAsSingleton($key, $value, $arguments = null) {
		self::addToMap($key, (object)[
			'value' => $value,
			'type' => 'value',
			'instance' => null,
			'arguments' => $arguments
		]);
	}
	
	
}