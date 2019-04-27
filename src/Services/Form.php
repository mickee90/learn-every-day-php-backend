<?php

namespace MN\Services;

class Form {
	
	/** @var array  */
	protected $request;
	
	/** @var bool  */
	public $validated = false;
	
	/** @var bool  */
	public $is_submitted = false;
	
	/** @var array  */
	public $errors = [];
	
	public function __construct($request, $api = false) {
		$this->request = $request;
		if(!empty($this->request->request->get("submit")) || $this->request->headers->get("X-REQUESTED-WITH") === 'XMLHttpRequest' || $api === true) $this->setIsSubmitted(true);
	}
	
	/**
	 * @return bool
	 */
	public function isValidated() {
		return $this->validated;
	}
	
	/**
	 * @param bool $validated
	 * @return Form
	 */
	public function setValidated($validated) {
		$this->validated = $validated;
		return $this;
	}
	
	/**
	 * @return bool
	 */
	public function isSubmitted() {
		return $this->is_submitted;
	}
	
	/**
	 * @param bool $is_submitted
	 * @return Form
	 */
	public function setIsSubmitted($is_submitted) {
		$this->is_submitted = $is_submitted;
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
	}
	
	/**
	 * @param array $errors
	 * @return Form
	 */
	public function setErrors($errors) {
		$this->errors = $errors;
		return $this;
	}
	
	
}