<?php

namespace MN\Repositories;

use MN\Entities\Account;
use MN\System;
use MN\System\Repository;
use MN\System\AutoPopulator;
use function MN\System\db;
use \Slim\PDO\Database as PDO;

class AccountRepository extends Repository {
	
	/**
	 * @param $username string
	 * @return bool
	 */
	public static function usernameExists($username) {
		$db = db()->getDb();
		$stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
		$stmt->execute(array($username));
		return ($stmt->fetchColumn()) ? true : false;
	}
	
	/**
	 * @param $id
	 * @return null|Account
	 */
	public static function getById($id) {
		$db = db()->getDb();
		$stmt = $db->prepare("SELECT a.id, a.type, a.username, a.first_name,
		a.last_name, a.address, a.zip_code, a.city, a.email, a.phone, b.name AS type_name
		FROM users a
		JOIN user_type b ON a.type = b.id
		WHERE a.id = ? AND a.disabled = 0 AND a.banned = 0
		LIMIT 1");
		
		$stmt->execute([$id]);
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		if(!empty($result)) {
			$result->history = PostRepository::getHistoryByUserId($id);
			$result->unpublished = PostRepository::getUnpublishedByUserId($id);
			$result->published = PostRepository::getPublishedByUserId($id);
			$result->deleted = PostRepository::getDeletedByUserId($id);
			/** @var Account $data */
			$data = (new AutoPopulator(Account::class))
				->setData($result)
				->start();
		}
		return !empty($data) ? $data : null;
	}
	
	/**
	 * @param $account Account
	 * @return bool|Account
	 */
	public static function create($account) {
		$exceptions = ['id', 'updated', 'type', 'type_name', 'disabled', 'banned', 'token', 'history', 'unpublished', 'published', 'deleted'];
		
		$table_fields = self::getPrepareInsertFields($account, $exceptions);
		$table_values = self::getPrepareInsertValues($account, $exceptions);
		$params = self::getParamsInsertStatement($account, $exceptions);
		
		$db = db()->getDb();
		$stmt = $db->prepare("INSERT INTO users ($table_fields) VALUES ($table_values)");
		$stmt->execute($params);
		
		if($stmt->rowCount() > 0)
			$account->id = $db->lastInsertId();
		
		return !empty($account->id) ? $account : false;
	}
	
	/**
	 * @param $account Account
	 * @return bool|Account
	 */
	public static function edit($account) {
		$exceptions = ['id', 'type', 'type_name', 'username', 'disabled', 'banned', 'token', 'history', 'unpublished', 'published', 'deleted'];
		
		$query_str = self::getPrepareUpdateStatement($account, $exceptions);
		$params = self::getParamsUpdateStatement($account, $exceptions);
		$params['id'] = $account->getId();
		
		$db = db()->getDb();
		$stmt = $db->prepare("UPDATE users SET $query_str WHERE id = :id");
		$stmt->execute($params);
		
		return !empty($stmt) ? $account : false;
	}
	
	public function deleteById($id) {
	
	}
}