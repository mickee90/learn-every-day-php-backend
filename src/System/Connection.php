<?php
namespace MN\System;

use \Slim\PDO\Database as PDO;
use \Slim\PDO\Statement;

if(!defined('MN')) die();

class Connection {
	
	/** @var PDO */
	protected $pdo;
	
	/** @var Statement */
	protected $statement;
	
	public function __construct() {
		$this->connect();
	}
	
	protected function connect() {
		
		try{
			$settings = sprintf("%s:dbname=%s;host=%s;port=%d;charset=utf8",
		  		MN_DB_TYPE,
				MN_DB_NAME,
			 	MN_DB_HOST,
				MN_DB_PORT
			);
			$this->pdo = new PDO($settings, MN_DB_USER, MN_DB_PASS, [
				PDO::ATTR_TIMEOUT => 2,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			]);
		} catch(\Exception $ex) {
			print_r($ex);
			print_r($this->pdo);
			print_r($settings);
			exit;
			die('Failed to connect to the database.');
		}
	}
	
	public function query($sql) {
		$this->statement = $this->pdo->prepare($sql, [
			PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL
		]);
		return $this;
	}
	
	public function getDb() {
		return (!empty($this->pdo)) ? $this->pdo : null;
	}
}