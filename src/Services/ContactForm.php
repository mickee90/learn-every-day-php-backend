<?php

namespace MN\Services;

use MN\Utils\Validator;

class ContactForm extends Form {
	
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
		
		if(!empty($this->request->request->get("submit"))) $this->setIsSubmitted(true);
		if(count($this->errors) === 0) {
			$this->setValidated(true);
		}
		
		return $this;
	}
	
	public function sendContactForm($config) {
		/**
		 * Add PHPMailer or something alike
		 */
		$mailbody[] = "Sender Name: " . $this->name;
		$mailbody[] = "Sender Email: " . $this->email;
		$mailbody[] = "Sender Content: " . $this->content;
		$mailbody[] = "Receiver Email: " . $config->contact_mail;
		return $mailbody;
	}
	
}