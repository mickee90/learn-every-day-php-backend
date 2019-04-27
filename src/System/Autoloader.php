<?php

namespace MN\System;

class Autoloader {
	
	public function __construct() {
	
	}
	
	public function locate($classname) {
		$levels = explode("\\", $classname);
		$path = MN_DIR;
		
		foreach($levels AS $level) {
			$check = $path.$level;
			
			if(is_dir($check)) {
				$path = $path.$level.DS;
			} else {
				if(is_readable($file = $check.EXT)) {
					return $file;
				} else continue;
			}
		}
	}
	
	public function autoload($classname) {
		if($filename = $this->locate($classname)) {
			include($filename);
			return true;
		}
	}
	
	public function register() {
		return spl_autoload_register([$this, 'autoload']);
	}
	
}


