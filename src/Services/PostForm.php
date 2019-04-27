<?php

namespace MN\Services;

use MN\Utils\Validator;
use MN\Entities\Post;

class PostForm extends Form {
	
	/** @var Post */
	private $post;
	
	/**
	 * @param $post Post
	 */
	public function edit($post) {
		$post->setUserId(2);
		$post->setStatus(true);
		$post->setDeleted(false);
		
		if(!Validator::notEmpty($this->request->request->get("id"))) {
			$this->errors[] = "ID saknas";
		} else {
			$post->setId($this->request->request->get("id"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("title"))) {
			$this->errors[] = "Rubrik saknas";
		} else {
			$post->setTitle($this->request->request->get("title"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("ingress"))) {
			$this->errors[] = "Ingress saknas";
		} else {
			$post->setIngress($this->request->request->get("ingress"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("content"))) {
			$this->errors[] = "Text saknas";
		} else {
			$post->setContent($this->request->request->get("content"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("deadline"))) {
			$this->errors[] = "Deadline saknas";
		} else {
			$post->setDeadline($this->request->request->get("deadline"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("email"))) {
			$this->errors[] = "E-mail saknas";
		} else {
			$post->setEmail($this->request->request->get("email"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("phone"))) {
			$this->errors[] = "Telefon saknas";
		} else {
			$post->setPhone($this->request->request->get("phone"));
		}
		
		$images = $this->request->request->get("images");
		
		if(!empty($images)) {
			$post->setImages($images);
		}
		
		if(!empty($this->request->request->get("submit"))) $this->setIsSubmitted(true);
		if(count($this->errors) === 0) {
			$this->setValidated(true);
			$this->post = $post;
		}
	}
	
	public function contactForm() {
		
		if(!Validator::notEmpty($this->request->request->get("name"))) {
			$this->errors[] = "Namn saknas";
		} else {
			$this->name = $this->request->request->get("name");
		}
		
		if(!Validator::notEmpty($this->request->request->get("email"))) {
			$this->errors[] = "E-post saknas";
		} else {
			$this->email = $this->request->request->get("email");
		}
		
		if(!Validator::notEmpty($this->request->request->get("content"))) {
			$this->errors[] = "Meddelande saknas";
		} else {
			$this->content = $this->request->request->get("content");
		}
		
		$this->phone = $this->request->request->get("phone");
		
		if(!empty($this->request->request->get("submit"))) $this->setIsSubmitted(true);
		if(count($this->errors) === 0) {
			$this->setValidated(true);
		}
		
		return $this;
	}
	
	public function sendContactForm($user) {
		/**
		 * Add PHPMailer or something alike
		 */
		$mailbody[] = "Sender Name: " . $this->name;
		$mailbody[] = "Sender Email: " . $this->email;
		$mailbody[] = "Sender Content: " . $this->content;
		$mailbody[] = "Sender Phone: " . $this->phone;
		$mailbody[] = "Receiver Name: " . $user->first_name . " " . $user->last_name;
		$mailbody[] = "Receiver Email: " . $user->email;
		return $mailbody;
	}
	
	/**
	 *
	 */
	public function add() {
		$post = new Post();
		
		$post->setStatus(true);
		$post->setDeleted(false);
		$post->setRenewed(0);
		
		if(!Validator::notEmpty($this->request->request->get("title"))) {
			$this->errors[] = "Rubrik saknas";
		} else {
			$post->setTitle($this->request->request->get("title"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("ingress"))) {
			$this->errors[] = "Ingress saknas";
		} else {
			$post->setIngress($this->request->request->get("ingress"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("content"))) {
			$this->errors[] = "Text saknas";
		} else if(!Validator::noBannedContent($this->request->request->get("content"))){
			$this->errors[] = "Texten innehåller förbjudna ord.";
		} else if(!Validator::noSalesContent($this->request->request->get("content"))){
			$post->setContent($this->request->request->get("content"));
			$post->setInspection(1);
		} else {
			$post->setContent($this->request->request->get("content"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("deadline"))) {
			$this->errors[] = "Deadline saknas";
		} else {
			$post->setDeadline($this->request->request->get("deadline"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("email"))) {
			$this->errors[] = "E-mail saknas";
		} else {
			$post->setEmail($this->request->request->get("email"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("phone"))) {
			$this->errors[] = "Telefon saknas";
		} else {
			$post->setPhone($this->request->request->get("phone"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("user_id"))) {
			$this->errors[] = "Konto-ID saknas";
		} else {
			$post->setUserId($this->request->request->get("user_id"));
		}
		
		$images = $this->request->request->get("images");
		
		if(!empty($images)) {
			$post->setImages($images);
		}
		
		if(!empty($this->request->request->get("submit"))) $this->setIsSubmitted(true);
		if(count($this->errors) === 0) {
			$this->setValidated(true);
			$this->post = $post;
		}
	}
	
	/**
	 * @return Post
	 */
	public function getPost() {
		return $this->post;
	}
	
	/**
	 * @param Post $post
	 * @return PostForm
	 */
	public function setPost($post) {
		$this->post = $post;
		return $this;
	}
	
	
}