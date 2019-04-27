<?php

namespace MN\Controllers\Api\v1;

use MN\Entities\Post;
use MN\Controllers\Api\Controller;
use MN\Repositories\PostRepository;
use MN\Services\PostForm;
use MN\Services\ApiFilter;

class Posts extends Controller {
	
	const VALID_METHODS = ['GET', 'POST', 'PATCH', 'DELETE', 'OPTIONS'];
	
	// public function index($id = 0) {
	// 	// $method = $this->validateMethods(['GET', 'POST', 'PATCH', 'PUT', 'DELETE', 'OPTION'])->methodExists();
	// 	$this->{$method}($id);
	// }
	
	protected function get($id = 0) {
		$post_repository = new PostRepository();
		
		$posts = ((int)$id > 0)
			? $post_repository->getByIdApi($id, $this->auth->user_id)
			: $post_repository->getAllApi($this->auth->user_id);
		
		if(empty($posts)) $this->response->setStatusCode(404)->send();
		
		if(!is_array($posts)) $posts = [$posts];
		
		$this->response->setStatusCode(200)->setContent($posts)->send();
	}
	
	protected function post() {
		if(!$title = $this->request->request->get("title")) {
			$this->response->setContent('The title is missing')->send();
		}
		
		if(!$content = $this->request->request->get("content")) {
			$this->response->setContent('The content is missing')->send();
		}
		
		if(!$publish_date = $this->request->request->get("publish_date")) {
			$this->response->setContent('The publish date is missing')->send();
		}
		
		$post = new Post();
		$post->title = $title;
		$post->content = $content;
		$post->publish_date = $publish_date;
		$post->user_id = $this->auth->user_id;
		
		$post = PostRepository::create($post);
		
		if(empty($post->id)) $this->response->setContent('Something went wrong. Please try again')->send();
		
		$this->response->setStatusCode(200)->setContent($post)->send();
	}
	
	// protected function put($id = 0) {
	// }
	
	protected function patch($id = 0) {
		$update_props = [];
		
		if((int)$id == 0) $this->response->setStatusCode(404)->setContent('The Id is missing')->send();
		
		
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
		
		$post = PostRepository::getById($id);
		$post->setByProps($update_props);
		
		$post = PostRepository::update($post);
		
		if(empty($post->id)) $this->response->setContent('Something went wrong. Please try again')->send();
		
		$this->response->setStatusCode(200)->setContent($post)->send();
	}
	
	protected function delete($id = 0) {
		if((int)$id == 0) $this->response->setStatusCode(404)->setContent('The Id is missing')->send();
		
		$post = PostRepository::getById($id);
		$post->deleted = 1;
		$post = PostRepository::update($post);
		
		if(empty($post->id)) $this->response->setContent('Something went wrong. Please try again')->send();
		
		$this->response->setStatusCode(200)->setContent($post)->send();
	}
}