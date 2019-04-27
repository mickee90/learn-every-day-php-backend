<?php

namespace MN\Services;

class Paginator {
	
	/** @var int */
	public $total_items;
	
	/** @var int */
	public $items_per_page;
	
	/** @var int */
	public $current_page;
	
	/** @var string */
	public $url_pattern = '';
	
	/** @var int */
	public $number_of_pages;
	
	/** @var array */
	public $pages;
	
	/** @var int */
	public $start;
	
	/** @var string */
	public $order_by = 'DESC';
	
	/**
	 * Paginator constructor.
	 *
	 * @param $total_items
	 * @param int $items_per_page
	 * @param int $current_page
	 */
	public function __construct($total_items = 10, $items_per_page = 20, $current_page = null) {
		$this->total_items = $total_items;
		$this->items_per_page = $items_per_page;
		$this->current_page = !is_null($current_page) ? $current_page : (isset($_GET['paginator']) ? (int)$_GET['paginator'] : 1);
		$this->number_of_pages = ceil($this->total_items / $this->items_per_page);
		for($i = 1; $i <= $this->number_of_pages; $i++) {
			$this->pages[$i] = ['number' => $i, 'url' => $this->url_pattern . "?paginator=" . $i, "current_page" => (($current_page == $i) ? true : false)];
		}
		
		$this->start = ($this->current_page -1) * $this->items_per_page;
	}
	
	/**
	 * @return int
	 */
	public function getTotalItems() {
		return $this->total_items;
	}
	
	/**
	 * @param int $total_items
	 * @return Paginator
	 */
	public function setTotalItems($total_items) {
		$this->total_items = $total_items;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getItemsPerPage() {
		return $this->items_per_page;
	}
	
	/**
	 * @param int $items_per_page
	 * @return Paginator
	 */
	public function setItemsPerPage($items_per_page) {
		$this->items_per_page = $items_per_page;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getCurrentPage() {
		return $this->current_page;
	}
	
	/**
	 * @param int $current_page
	 * @return Paginator
	 */
	public function setCurrentPage($current_page) {
		$this->current_page = $current_page;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getUrlPattern() {
		return $this->url_pattern;
	}
	
	/**
	 * @param string $url_pattern
	 * @return Paginator
	 */
	public function setUrlPattern($url_pattern) {
		$this->url_pattern = $url_pattern;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getOrderBy() {
		return $this->order_by;
	}
	
	/**
	 * @param string $order_by
	 * @return Paginator
	 */
	public function setOrderBy($order_by) {
		$this->order_by = $order_by;
		return $this;
	}
	
	public function getStart() {
		return $this->start;
	}
	
	public function getNumberOfPages() {
		return $this->number_of_pages;
	}
	
	public function getPages() {
		return $this->pages;
	}
	
	public function getNextPage() {
		return $this->pages[$this->current_page +1];
	}
	
	public function getPrevPage() {
		return $this->pages[$this->current_page -1];
	}
	
}