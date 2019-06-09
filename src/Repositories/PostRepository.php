<?php
namespace MN\Repositories;

use MN\Entities\Media;
use MN\Entities\Post;
use MN\Services\Paginator;
use MN\System\Repository;
use MN\System\AutoPopulator;
use MN\System;
use function MN\System\db;
use \Slim\PDO\Database as PDO;

class PostRepository extends Repository {
	
	/**
	 * @param $post Post
	 * @return Post|bool
	 */
	public static function create($post) {
		$exceptions = ['id', 'ingress', 'updated', 'deleted'];
		$post->uuid = System\genUUID();
		
		$table_fields = self::getPrepareInsertFields($post, $exceptions);
		$table_values = self::getPrepareInsertValues($post, $exceptions);
		$params = self::getParamsInsertStatement($post, $exceptions);
		
		$db = db()->getDb();
		$stmt = $db->prepare("INSERT INTO app_posts ($table_fields) VALUES ($table_values)");
		$stmt->execute($params);
		
		if($stmt->rowCount() > 0) $post->id = $db->lastInsertId();
		if(empty($post->id)) return false;
		
		return $post;
	}
	
	/**
	 * @param $post Post
	 * @return Post|bool
	 */
	public static function update($post) {
		$exceptions = ['id', 'updated', 'created'];
		
		$update_fields = self::getPrepareUpdateStatement($post, $exceptions);
		$params = self::getParamsUpdateStatement($post, $exceptions);
		$params['id'] = $post->id;
		
		$db = db()->getDb();
		$stmt = $db->prepare("UPDATE app_posts SET $update_fields WHERE id = :id");
		$stmt->execute($params);
		
		return !empty($stmt) ? $post : false;
	}
	
	/**
	 * @param $paginator Paginator
	 * @param $args array
	 * @return array
	 */
	public function getAll($args = []) {
		$where = count($args) > 0 ? self::getSimpleWhereStatement($args) : '';
		$posts = [];
		$db = db()->getDb();
		
		$stmt = $db->query("SELECT * FROM app_posts
		$where
		ORDER BY publish_date DESC");
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		if(count($rows) > 0) {
			foreach($rows AS $row) {
				// $row->user = UserRepository::getById($row->user_id);
				$posts[] = (new AutoPopulator(Post::class))
					->setData($row)
					->start();
			}
		}
		return $posts;
	}
	
	/**
	 * @param int $user_id
	 * @return array
	 */
	public function getAllApi($user_id = 0) {
		if($user_id === 0) return [];
		
		$args = [
			['deleted', ' = ', 0, ' AND '],
			['status', ' = ', 1, ' AND '],
			['user_id', ' = ', $user_id, '']
		];
		$where = count($args) > 0 ? self::getSimpleWhereStatement($args) : '';
		$db = db()->getDb();
		
		$stmt = $db->query("SELECT id, title, content, publish_date as `date`, user_id FROM app_posts
		$where
		ORDER BY publish_date DESC");
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		return $rows;
	}
	
	// public function getAll($paginator, $args = []) {
	// 	$where = count($args) > 0 ? self::getSimpleWhereStatement($args) : '';
	// 	$posts = [];
	// 	$db = db()->getDb();
	//
	// 	$stmt = $db->query("SELECT * FROM app_posts
	// 	$where
	// 	ORDER BY id {$paginator->order_by}
	// 	LIMIT {$paginator->start}, {$paginator->items_per_page}");
	// 	$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
	//
	// 	if(count($rows) > 0) {
	// 		foreach($rows AS $row) {
	// 			// $row->user = UserRepository::getById($row->user_id);
	// 			$posts[] = (new AutoPopulator(Post::class))
	// 				->setData($row)
	// 				->start();
	// 		}
	// 	}
	// 	return $posts;
	// }
	
	/**
	 * @param $paginator Paginator
	 * @return array
	 */
	public function getAllPublished($paginator) {
		return self::getAll($paginator, [['status', " = ", 1, '']]);
	}
	
	/**
	 * @param $paginator Paginator
	 * @return array
	 */
	public function getAllUnPublished($paginator) {
		return self::getAll($paginator, [['status', " = ", 0, '']]);
	}
	
	/**
	 * @param array $args
	 * @return mixed
	 */
	public static function getAllCount($args = []) {
		$where = count($args) > 0 ? self::getSimpleWhereStatement($args) : '';
		$db = db()->getDb();
		$stmt = $db->query("SELECT count(*) FROM app_posts $where");
		return $stmt->fetchColumn();
	}
	
	/**
	 * @return mixed
	 */
	public function getAllPublishedCount() {
		return self::getAllCount([['status', " = ", 1, '']]);
	}
	
	/**
	 * @return mixed
	 */
	public function getAllUnpublishedCount() {
		return self::getAllCount([['status', " = ", 0, '']]);
	}
	
	/**
	 * @return mixed
	 */
	public function getAllHistoryCount() {
		return self::getAllCount([['deadline', " < ", "NOW()", '']]);
	}
	
	/**
	 * @param $id
	 * @return array
	 */
	public static function getHistoryByUserId($id) {
		return self::getByArgs([
			['user_id', ' = ', $id, ' AND '],
			['deadline', ' < ', 'NOW()', '']
		]);
	}
	
	/**
	 * @param $id
	 * @return array
	 */
	public static function getUnpublishedByUserId($id) {
		return self::getByArgs([
			['user_id', ' = ', $id, ' AND '],
			['status', ' = ', 0, '']
		]);
	}
	
	/**
	 * @param $id
	 * @return array
	 */
	public static function getPublishedByUserId($id) {
		return self::getByArgs([
			['user_id', ' = ', $id, ' AND '],
			['status', ' = ', 1, ' AND '],
			['deleted', ' = ', 0, ' AND '],
			['deadline', ' > ', 'NOW()', '']
		]);
	}
	
	/**
	 * @param $id
	 * @return array
	 */
	public static function getDeletedByUserId($id) {
		return self::getByArgs([
			['user_id', ' = ', $id, ' AND '],
			['deleted', ' = ', 1, '']
		]);
	}
	
	/**
	 * @param $args
	 * @return array
	 */
	public static function getByArgs($args) {
		$items = [];
		$where = count($args) > 0 ? self::getPrepareWhereStatement($args) : '';
		$params = count($args) > 0 ? self::getParamsWhereStatement($args) : '';
		
		$db = db()->getDb();
		$stmt = $db->prepare("SELECT * FROM app_posts $where");
		$stmt->execute($params);
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		if(count($rows) > 0) {
			foreach($rows AS $row) {
				$items[] = (new AutoPopulator(Post::class))
					->setData($row)
					->start();
			}
		}
		return $items;
	}
	
	/**
	 * @param $id
	 * @return null|Post
	 */
	public static function getById($id) {
		$db = db()->getDb();
		$stmt = $db->prepare("SELECT * FROM app_posts WHERE id = ? AND deleted = 0 LIMIT 1");
		$stmt->execute([$id]);
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		if(!empty($result)) {
			/** @var Post $data */
			$data = (new AutoPopulator(Post::class))
				->setData($result)
				->start();
		}
		return !empty($data) ? $data : null;
	}
	
	/**
	 * @param int $id
	 * @param int $user_id
	 * @return null|Post
	 */
	public static function getByIdApi($id, $user_id = 0) {
		if($user_id === 0) return null;
		
		$args = [
			['deleted', ' = ', 0, ' AND '],
			['status', ' = ', 1, ' AND '],
			['id', ' = ', $id, ' AND '],
			['user_id', ' = ', $user_id, '']
		];
		$where = count($args) > 0 ? self::getSimpleWhereStatement($args) : '';
		$db = db()->getDb();
		
		$stmt = $db->query("SELECT id, title, content, publish_date as `date`, user_id FROM app_posts
		$where
		ORDER BY publish_date DESC");
		$row = $stmt->fetchObject();
		
		return !empty($row) ? $row : null;
	}
	
}