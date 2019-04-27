<?php

namespace MN\Services;

class ApiFilter extends Paginator {
	
	/** @var string */
	public $order_value;
	
	/** @var array */
	public $fields = [];
	
	/** @var array  */
	public $filters = [];
	
	/** @var object */
	public $obj;
	
	public function __construct(Request $request, $obj) {
		parent::__construct();
		
		$this->obj = $obj;
		
		if(count($request->query->all()) > 0) {
			foreach($request->query->all() AS $key => $value) {
				
				if($key === 'fields' && !empty($value)) {
					$this->setFields($value);
				} else if($key === 'sort' && !empty($value)) {
					$this->setSort($value);
				} else if(!empty($key) && !empty($value) && property_exists($this->obj, $key)){
					$this->filters[$key] = $value;
				}
			}
		}
	}
	
	public function setFields($value = '') {
		$fields = explode(',', $value);
		foreach($fields AS $field) {
			if(property_exists($this->obj, $field))
				$this->fields[] = $field;
		}
	}
	
	public function setSort($value) {
		if(strpos($value, '-') !== false) {
			$order_values = explode('-', $value);
			$this->order_value = $order_values[0];
			$this->order_by = !empty($order_values[1]) ? strtoupper($order_values[1]) : $this->order_by;
		} else {
			$this->order_value = $value;
		}
	}
	
}