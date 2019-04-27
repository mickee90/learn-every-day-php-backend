<?php

namespace MN\System;

class Model {
	
	public function setByProps($props) {
		if(!empty($props)) {
			foreach($props as $key => $val) {
				if(property_exists(static::class, $key)) $this->{$key} = $val;
			}
		}
	}
	
}