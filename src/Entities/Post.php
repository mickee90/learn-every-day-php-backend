<?php

namespace MN\Entities;

use MN\System\Model;

class Post extends Model {
	
	/** @var int */
	public $id;
	
	/** @var string */
	public $uuid;
	
	/** @var int */
	public $user_id;
	
	/** @var bool */
	public $status = true;
	
	/** @var string */
	public $title;
	
	/** @var string */
	public $ingress;
	
	/** @var string */
	public $content;
	
	/** @var bool */
	public $deleted = false;
	
	/** @var string */
	public $publish_date;
	
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
	 * @return Post
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
	 * @return Post
	 */
	public function setCreated($created) {
		$this->created = $created;
		return $this;
	}
}