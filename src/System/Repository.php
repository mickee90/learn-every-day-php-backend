<?php

namespace MN\System;

class Repository {
	
	public function __construct() {
	
	}
	
	public static function getSimpleWhereStatement($args, $exceptions = []) {
		$out = 'WHERE ';
		foreach($args AS $arg) {
			if(!in_array($arg[0], $exceptions)) {
				$out .= $arg[0] . $arg[1] . $arg[2] . (isset($arg[3]) ? $arg[3] : '');
			}
		}
		return $out;
	}
	
	public static function getPrepareInsertFields($args, $exceptions = []) {
		$out = '';
		
		foreach($args AS $property_name => $value) {
			if(!in_array($property_name, $exceptions)) {
				$out .= "`$property_name`,";
			}
		}
		
		return rtrim($out, ",");
	}
	
	public static function getPrepareInsertValues($args, $exceptions = []) {
		$out = '';
		
		foreach($args AS $property_name => $value) {
			if(!in_array($property_name, $exceptions)) {
				$out .= ":$property_name,";
			}
		}
		
		return rtrim($out, ",");
	}
	
	public static function getParamsInsertStatement($args, $exceptions = []) {
		$out = [];
		
		foreach($args AS $property_name => $value) {
			if(!in_array($property_name, $exceptions)) {
				$out[$property_name] = $value;
			}
		}
		
		return $out;
	}
	
	public static function getPrepareWhereStatement($args, $exceptions = []) {
		$out = 'WHERE ';
		foreach($args AS $arg) {
			if(!in_array($arg[0], $exceptions)) {
				if(strpos($arg[0], '.') !== false) {
					$pieces = explode(".", $arg[0]);
					$placeholder = $pieces[1];
				} else {
					$placeholder = $arg[0];
				}
				if($arg[2] == 'NOW()') {
					$out .= $arg[0] . $arg[1] . $arg[2] . (isset($arg[3]) ? $arg[3] : '');
				} else {
					$out .= $arg[0] . $arg[1] . ':' . $placeholder . (isset($arg[3]) ? $arg[3] : '');
				}
			}
		}
		return $out;
	}
	
	public static function getParamsWhereStatement($args, $exceptions = []) {
		$params = [];
		foreach($args AS $arg) {
			if(strpos($arg[0], '.') !== false) {
				$pieces = explode(".", $arg[0]);
				$placeholder = $pieces[1];
			} else {
				$placeholder = $arg[0];
			}
			if(!in_array($placeholder, $exceptions) && $arg[2] != 'NOW()') {
				$params[$placeholder] = $arg[2];
			}
		}
		return $params;
	}
	
	public static function getPrepareUpdateStatement($args, $exceptions = []) {
		$out = "";
		
		foreach($args AS $property_name => $value) {
			if(!in_array($property_name, $exceptions)) {
				$out .= "`$property_name` = :$property_name,";
			}
		}
		
		return rtrim($out, ",");
	}
	
	public static function getParamsUpdateStatement($args, $exceptions = []) {
		$params = [];
		
		foreach($args AS $property_name => $value) {
			if(!in_array($property_name, $exceptions)) {
				$params[$property_name] = $value;
			}
		}
		
		return $params;
	}
	
}