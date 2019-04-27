<?php

namespace MN\Repositories;

use MN\Entities\User;
use MN\Services\ApiFilter;
use MN\System;
use MN\System\Repository;
use MN\System\AutoPopulator;
use MN\Services\Paginator;
use function MN\System\db;
use \Slim\PDO\Database as PDO;

class UserRepository extends Repository{
	
	private $valid_api_fields = [
		['a', 'id', 'id'],
		['a', 'uuid', 'uuid'],
		['a', 'username', 'username'],
		['a', 'first_name', 'first_name'],
		['a', 'last_name', 'last_name'],
		['a', 'address', 'address'],
		['a', 'zip_code', 'zip_code'],
		['a', 'city', 'city'],
		['a', 'email', 'email'],
		['a', 'phone', 'phone'],
		['a', 'disabled', 'disabled'],
		['a', 'banned', 'banned'],
		['a', 'updated', 'updated'],
		['a', 'created', 'created'],
		['b', 'name', 'type_name']
	];
	
	/**
	 * @param $username
	 * @param $password
	 * @return bool|User
	 */
	public static function login($username, $password) {
		$response = false;
		$db = db()->getDb();
		$stmt = $db->prepare("SELECT id, password FROM app_users WHERE username = ?");
		$stmt->execute([$username]);
		$user = $stmt->fetchObject();
		
		if($user) {
			if(password_verify($password, $user->password)) {
				/** @var User $response */
				self::setAuthTokenExpire($user->id);
				$response = self::setAuthUser(self::getById($user->id));
			}
		}
		
		return $response;
	}
	
	public static function hashPass($password) {
		return password_hash($password, PASSWORD_DEFAULT);
	}
	
	public static function setAuthTokenExpire($user_id) {
		$token_expire = date('Y-m-d H:m:s', strtotime("+3 days"));
		$params = ['id' => $user_id, 'expire' => $token_expire];
		
		$db = db()->getDb();
		$stmt = $db->prepare("UPDATE app_users SET auth_token_expire = :expire WHERE id = :id");
		$stmt->execute($params);
		
		return !empty($stmt) ? true : false;
	}
	
	/**
	 * @param $id
	 * @return null|User
	 */
	public static function getById($id) {
		$where = self::getPrepareWhereStatement([
			['a.disabled', ' = ', 0, ' AND '],
			['a.banned', ' = ', 0, ' AND '],
			['a.id', ' = ', $id, '']
		]);
		$params =  self::getParamsWhereStatement([
			['disabled', ' = ', 0, ' AND '],
			['banned', ' = ', 0, ' AND '],
			['id', ' = ', $id, '']
		]);
		$db = db()->getDb();
		$stmt = $db->prepare("SELECT a.*, b.name AS type_name FROM app_users a
		JOIN app_user_type b ON a.user_type_id = b.id
		$where
		LIMIT 1");
		$stmt->execute($params);
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		if(!empty($result)) {
			$result->posts = PostRepository::getByArgs([['user_id', ' = ', $result->id, '']]);
			/** @var User $data */
			$data = (new AutoPopulator(User::class))
				->setData($result)
				->start();
		}
		return !empty($data) ? $data : null;
	}
	
	/**
	 * @param $user
	 * @return array
	 */
	public static function setAuthUser($user) {
		if(empty($user->id)) return [];
		
		$data = [
			'uuid' => $user->uuid,
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'username' => $user->username,
			'email' => $user->email,
			'auth_token' => $user->auth_token,
			'auth_token_expire' => $user->auth_token_expire,
			'auth_refresh_token' => $user->auth_refresh_token,
			'country' => $user->country_id
		];
		
		return $data;
	}
	
	/**
	 * @param $id
	 * @return null|User
	 */
	public static function getByAuth($auth_token) {
		$where = self::getPrepareWhereStatement([
			['a.disabled', ' = ', 0, ' AND '],
			['a.banned', ' = ', 0, ' AND '],
			['a.auth_token', ' = ', $auth_token, '']
		]);
		$params =  self::getParamsWhereStatement([
			['disabled', ' = ', 0, ' AND '],
			['banned', ' = ', 0, ' AND '],
			['auth_token', ' = ', $auth_token, '']
		]);
		$db = db()->getDb();
		$stmt = $db->prepare("SELECT a.*, b.name AS type_name FROM app_users a
		JOIN app_user_type b ON a.user_type_id = b.id
		$where
		LIMIT 1");
		$stmt->execute($params);
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		if(!empty($result)) {
			$result->posts = PostRepository::getByArgs([['user_id', ' = ', $result->id, '']]);
			/** @var User $data */
			$data = (new AutoPopulator(User::class))
				->setData($result)
				->start();
		}
		return !empty($data) ? $data : null;
	}
	
	/**
	 * @param $user User
	 * @return bool|User
	 */
	public static function create($user) {
		$exceptions = ['id', 'updated'];
		$user->user_type_id = 3;
		$user->uuid = System\genUUID();
		
		$user->setCreated(date("Y-m-d H:i:s"));
		$user->auth_token = bin2hex(random_bytes(64));
		$user->auth_refresh_token = bin2hex(random_bytes(64));
		
		$table_fields = self::getPrepareInsertFields($user, $exceptions);
		$table_values = self::getPrepareInsertValues($user, $exceptions);
		$params = self::getParamsInsertStatement($user, $exceptions);
		
		$db = db()->getDb();
		$stmt = $db->prepare("INSERT INTO app_users ($table_fields) VALUES ($table_values)");
		$stmt->execute($params);
		if($stmt->rowCount() > 0) $user->id = $db->lastInsertId();
		
		return !empty($user->id) ? $user : false;
	}
	
	/**
	 * @param $user User
	 * @return bool|User
	 */
	public static function update($user) {
		$exceptions = ['id', 'uuid', 'auth_refresh_token', 'updated', 'created', 'user_type_id'];
		
		$update_fields = self::getPrepareUpdateStatement($user, $exceptions);
		$params = self::getParamsUpdateStatement($user, $exceptions);
		$params['id'] = $user->id;
		
		$db = db()->getDb();
		$stmt = $db->prepare("UPDATE app_users SET $update_fields WHERE id = :id");
		$stmt->execute($params);
		
		return !empty($stmt) ? $user : false;
	}
	
	// public static function edit($user) {
	// 	$exceptions = ['id', 'updated', 'created', 'user_type_id'];
	//
	// 	$query_str = self::getPrepareUpdateStatement($user, $exceptions);
	// 	$params = self::getParamsUpdateStatement($user, $exceptions);
	// 	$params['id'] = $user->id;
	//
	// 	$db = db()->getDb();
	// 	$stmt = $db->prepare("UPDATE app_users SET $query_str WHERE id = :id");
	// 	$stmt->execute($params);
	//
	// 	return !empty($stmt) ? $user : false;
	// }
	
	public static function delete($id) {
		$db = db()->getDb();
		$stmt = $db->prepare("UPDATE app_users SET deleted = 1 WHERE id = ?");
		$stmt->execute([$id]);
		
		return $stmt->rowCount() > 0 ? true : false;
	}
	
	/**
	 * @param $paginator
	 * @return array
	 */
	public static function getAll($paginator) {
		$posts = [];
		$db = db()->getDb();
		$stmt = $db->query("SELECT a.*, b.name AS type_name FROM app_users a
 		JOIN app_user_type b ON a.user_type_id = b.id
 		ORDER BY a.id {$paginator->order_by}
 		LIMIT {$paginator->start}, {$paginator->items_per_page}");
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		if(count($rows) > 0) {
			foreach($rows AS $row) {
				$row->nr_of_posts = PostRepository::getAllCount([['user_id', ' = ', $row->id, '']]);
				$posts[] = (new AutoPopulator(User::class))
					->setData($row)
					->start();
			}
		}
		return $posts;
	}
	
	public function getValidField($field, $offset) {
		if(empty($field)) return false;
		if(empty($offset)) return false;

		foreach($this->valid_api_fields AS $valid_field) {
			if($valid_field[$offset] == $field) return $valid_field;
		}
		return false;
	}
	
	/**
	 * @param ApiFilter $api_filter
	 * @return array
	 */
	public function apiSearch($api_filter) {
		$params = [];
		$query = "SELECT ";
		
		if(!empty($api_filter->fields)) {
			$counter = 1;
			$fields = count($api_filter->fields);
			foreach($api_filter->fields AS $field) {
				$separator = ($counter === $fields) ? '' : ',';
				$field_data = $this->getValidField($field, 1);
				if($field_data)
					$query .= $field_data[0].'.'.$field_data[1].' AS ' . $field_data[2] . $separator . " ";
				++$counter;
			}
		} else {
			$counter = 1;
			$fields = count($this->valid_api_fields);
			foreach($this->valid_api_fields AS $field) {
				$separator = ($counter === $fields) ? '' : ',';
					$query .= $field[0].'.'.$field[1].' AS ' . $field[2] . $separator . " ";
				++$counter;
			}
		}
		$query .= "FROM app_users a
						JOIN app_user_type b ON a.user_type_id = b.id ";
		
		
		if(!empty($api_filter->filters)) {
			$where_statement = [];
			$counter = 1;
			$filters = count($api_filter->filters);
			foreach($api_filter->filters AS $key => $val) {
				$prefix = $key === 'app_user_type' ? 'b.' : 'a.';
				$last = ($counter === $filters) ? '' : ' AND ';
				$where_statement[] = [$prefix.$key, ' = ', $val, $last];
				++$counter;
			}
			
			$query .= self::getPrepareWhereStatement($where_statement);
			$params =  self::getParamsWhereStatement($where_statement);
		}
		
		$order_by_value = (!empty($api_filter->order_value) && $this->getValidField($api_filter->order_value, 1)) ? 'a.' . $api_filter->order_value : 'a.id';
		$order_by = !empty($api_filter->order_by) ? $api_filter->order_by : 'DESC';
		
		$query .= " ORDER BY " . $order_by_value . " " . $order_by;
		$query .= " LIMIT {$api_filter->start}, {$api_filter->items_per_page}";
		
		$db = db()->getDb();
		$stmt = $db->prepare($query);
		if($params) {
			$stmt->execute($params);
		} else {
			$stmt->execute();
		}
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		$posts = count($rows) > 0 ? $rows : [];
		
		return $posts;
	}
	
	/**
	 * @return mixed
	 */
	// public function getAllCount() {
	// 	$db = db()->getDb();
	// 	$stmt = $db->query("SELECT count(*) FROM app_users");
	// 	return $stmt->fetchColumn();
	// }
	
	// public static function doUserExists($username) {
	// 	$db = db()->getDb();
	// 	$stmt = $db->prepare("SELECT id FROM app_users WHERE username = ?");
	// 	$stmt->execute([$username]);
	// 	return $stmt->fetchColumn();
	// }
	
	// public function deleteById($id) {
	//
	// }
}