<?php
namespace Opera;

use function MN\System\db;
use \Slim\PDO\Database as PDO;
use MN\System\Session;

class Logger {
	
	/** @var int */
	public $id;
	
	/** @var int */
	public $user_id = 0;
	
	/** @var string */
	public $page = '';
	
	/** @var int */
	public $status = 400;
	
	/** @var string */
	public $method = '';
	
	/** @var string */
	public $request = '';
	
	/** @var string */
	public $response = '';
	
	/** @var string */
	public $data = '';
	
	/** @var string */
	public $error = '';
	
	/** @var string */
	public $ua = '';
	
	/** @var string */
	public $ip = '';
	
	/** @var int */
	public $date = '';
	
	public function __construct() {
	
	}
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @param int $id
	 * @return Logger
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getUserId() {
		return $this->user_id;
	}
	
	/**
	 * @param int $user_id
	 * @return Logger
	 */
	public function setUserId($user_id) {
		$this->user_id = $user_id;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getPage() {
		return $this->page;
	}
	
	/**
	 * @param string $page
	 * @return Logger
	 */
	public function setPage($page) {
		$this->page = $page;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * @param int $status
	 * @return Logger
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}
	
	/**
	 * @param string $method
	 * @return Logger
	 */
	public function setMethod($method) {
		$this->method = $method;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getRequest() {
		return $this->request;
	}
	
	/**
	 * @param string $request
	 * @return Logger
	 */
	public function setRequest($request) {
		$this->request = $request;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getResponse() {
		return $this->response;
	}
	
	/**
	 * @param string $response
	 * @return Logger
	 */
	public function setResponse($response) {
		$this->response = $response;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getData() {
		return $this->data;
	}
	
	/**
	 * @param string $data
	 * @return Logger
	 */
	public function setData($data) {
		$this->data = $data;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getError() {
		return $this->error;
	}
	
	/**
	 * @param string $error
	 * @return Logger
	 */
	public function setError($error) {
		$this->error = $error;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getUa() {
		return $this->ua;
	}
	
	/**
	 * @param string $ua
	 * @return Logger
	 */
	public function setUa($ua) {
		$this->ua = $ua;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getIp() {
		return $this->ip;
	}
	
	/**
	 * @param string $ip
	 * @return Logger
	 */
	public function setIp($ip) {
		$this->ip = $ip;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getDate() {
		return $this->date;
	}
	
	/**
	 * @param int $date
	 * @return Logger
	 */
	public function setDate($date) {
		$this->date = $date;
		return $this;
	}
	
	public function insert() {
		$db = db()->getDb();
		
		$user_id = Session::getUserId() !== false ? Session::getUserId() : 0;
		$page = !empty($this->page) ? $this->page : '';
		$status = !empty($this->status) ? $this->status : '';
		$method = !empty($this->method) ? $this->method : '';
		$request = !empty($this->request) ? $this->request : '';
		$response = !empty($this->response) ? $this->response : '';
		$data = !empty($this->data) ? $this->data : '';
		$error = !empty($this->error) ? $this->error : '';
		$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		
		$stmt = $db->prepare("INSERT INTO portal_logger (user_id, page, status, method, request, response, data, error, ua, ip, date)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
		$stmt->execute([$user_id, $page, $status, $method, $request, $response, $data, $error, $ua, $ip]);
	}
	
}