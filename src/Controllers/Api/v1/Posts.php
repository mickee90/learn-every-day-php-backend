<?php
namespace MN\Controllers\Api\v1;

use MN\Entities\Post;
use MN\Controllers\Api\Controller;
use MN\Repositories\PostRepository;
use MN\Services\PostForm;
use MN\Services\ApiFilter;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Posts extends Controller {
	
	const VALID_METHODS = ['GET', 'POST', 'PATCH', 'DELETE', 'OPTIONS'];
	
	// public function index($id = 0) {
	// 	// $method = $this->validateMethods(['GET', 'POST', 'PATCH', 'PUT', 'DELETE', 'OPTION'])->methodExists();
	// 	$this->{$method}($id);
	// }
	
	// protected function get($id = 0) {
	// 	$post_repository = new PostRepository();
		
	// 	$posts = ((int)$id > 0)
	// 		? $post_repository->getByIdApi($id, $this->auth->user_id)
	// 		: $post_repository->getAllApi($this->auth->user_id);
		
	// 	if(empty($posts)) $this->response->setStatusCode(404)->send();
		
	// 	if(!is_array($posts)) $posts = [$posts];
		
	// 	$this->response->setStatusCode(200)->setContent($posts)->send();
	// }
	
	protected function get($uuid = '') {
		$post_repository = new PostRepository();
		
		$posts = !empty($uuid)
			? $post_repository->getByUuidApi($uuid, $this->auth->user_id)
			: $post_repository->getAllApi($this->auth->user_id);
		
		if(empty($posts)) {
			$msg = !empty($uuid) ? 'The post was not found. Send you back to the post list' : 'No posts was found';
			$this->response->setStatusCode(404)->setContent($msg)->send();
		}
		
		if(!is_array($posts)) $posts = [$posts];
		
		$this->response->setStatusCode(200)->setContent($posts)->send();
	}
	
	protected function post() {
		if(!$title = $this->request->request->get("title")) {
			$this->response->setContent('The title is missing')->send();
		}

		// if(!$ingress = $this->request->request->get("ingress")) {
		// 	$this->response->setIngress('The ingress is missing')->send();
		// }
		
		if(!$content = $this->request->request->get("content")) {
			$this->response->setContent('The content is missing')->send();
		}
		
		if(!$publish_date = $this->request->request->get("publish_date")) {
			$this->response->setContent('The publish date is missing')->send();
		}
		
		$post = new Post();
		$post->title = $title;
		// $post->ingress = $ingress;
		$post->content = $content;
		$post->publish_date = date('Y-m-d H:i:s', strtotime($publish_date));
		$post->user_id = $this->auth->user_id;
		
		$post = PostRepository::create($post);
		
		if(empty($post->id)) $this->response->setContent('Something went wrong. Please try again')->send();
		
		$this->response->setStatusCode(200)->setContent($post)->send();
	}
	
	// protected function put($id = 0) {
	// }
	
	protected function patch($uuid = '') {
		$update_props = [];
	
		if(empty($uuid)) $this->response->setStatusCode(404)->setContent('The Id is missing')->send();
		
		
		if($title = $this->request->request->get("title")) {
			$update_props['title'] = $title;
		}
		
		if($content = $this->request->request->get("content")) {
			$update_props['content'] = $content;
		}
		
		if($publish_date = $this->request->request->get("publish_date")) {
			$update_props['publish_date'] = $publish_date;
		}
		
		if(empty($update_props)) $this->response->setStatusCode(404)->setContent('Props to update is missing')->send();
		
		// print_r($update_props);exit;
		$update_post = PostRepository::getByUuid($uuid, $this->auth->user_id);
		$update_post->setByProps($update_props);
	
		$update_post = PostRepository::update($update_post);
		$post = PostRepository::getByUuid($uuid, $this->auth->user_id);
		
		if(empty($post->id)) $this->response->setContent('Something went wrong. Please try again')->send();
		
		$this->response->setStatusCode(200)->setContent($post)->send();
	}
	
	protected function delete($uuid = '') {
		if(empty($uuid)) $this->response->setStatusCode(404)->setContent('The Id is missing')->send();
		
		$post = PostRepository::getByUuidApi($uuid, $this->auth->user_id);
		$post->deleted = 1;
		$post = PostRepository::update($post);
		
		if(empty($post->id)) $this->response->setContent('Something went wrong. Please try again')->send();
		
		$this->response->setStatusCode(200)->setContent($post)->send();
	}
}