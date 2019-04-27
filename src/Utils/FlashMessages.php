<?php

namespace MN\Utils;

class FlashMessages {
	
	const INFO = "i";
	const SUCCESS = "s";
	const ERROR = "e";
	const WARNING = "w";
	
	const DEFAULT_TYPE = self::INFO;
	
	protected $redirect_url = null;
	
	protected $message_types = [
		self::INFO => 'info',
		self::SUCCESS => 'success',
		self::ERROR => 'error',
		self::WARNING => 'warning'
	];
	
	public function __construct() {
	
	}
	
	public function success($message, $redirect = '') {
		$messages = (gettype($message) == 'string') ? [$message] : $message;
		$this->add($messages, self::SUCCESS, $redirect);
	}
	
	public function info($message, $redirect = '') {
		$messages = (gettype($message) == 'string') ? [$message] : $message;
		$this->add($messages, self::INFO, $redirect);
	}
	
	public function error($message, $redirect = '') {
		$messages = (gettype($message) == 'string') ? [$message] : $message;
		$this->add($messages, self::ERROR, $redirect);
	}
	
	public function warning($message, $redirect = '') {
		$messages = (gettype($message) == 'string') ? [$message] : $message;
		$this->add($messages, self::WARNING, $redirect);
	}
	
	public function add($messages, $type, $redirect) {
		if(empty($messages)) return false;
		
		$type = !array_key_exists($type, $this->message_types) ? self::DEFAULT_TYPE : $type;
		
		if (!array_key_exists($type, $_SESSION['flash_messages'] )) $_SESSION['flash_messages'][$type] = array();
		foreach($messages AS $message) {
			$_SESSION['flash_messages'][$type][] = ['message' => $message];
		}
		
		// Handle the redirect if needed
		if (!is_null($redirect)) $this->redirect_url = $redirect;
		$this->doRedirect();
		
		return $this;
	}
	
	public function display($types=null, $print=false) {
		
		if (!isset($_SESSION['flash_messages'])) return false;
		
		$output = '';
		
		// Print all the message types
		if (is_null($types) || !$types || (is_array($types) && empty($types)) ) {
			$types = array_keys($this->message_types);
			
			// Print multiple message types (as defined by an array)
		} elseif (is_array($types) && !empty($types)) {
			$theTypes = $types;
			$types = [];
			foreach($theTypes as $type) {
				$types[] = strtolower($type[0]);
			}
			
			// Print only a single message type
		} else {
			$types = [strtolower($types[0])];
		}
		
		
		// Retrieve and format the messages, then remove them from session data
		foreach ($types as $type) {
			if (!isset($_SESSION['flash_messages'][$type]) || empty($_SESSION['flash_messages'][$type]) ) continue;
			foreach( $_SESSION['flash_messages'][$type] as $msgData ) {
				$output .= $this->formatMessage($msgData, $type);
			}
			$this->clear($type);
		}
		
		// Print everything to the screen (or return the data)
		if ($print) {
			echo $output;
		} else {
			return $output;
		}
	}
	
	public function formatMessage($message, $type) {
		return "<div class='flash_message flash_message_{$this->message_types[$type]}'>{$message['message']}</div>";
	}
	
	protected function doRedirect() {
		if ($this->redirect_url) {
			header('Location: ' . $this->redirect_url);
			exit();
		}
		return $this;
	}
	
	protected function clear($types=[]) {
		if ((is_array($types) && empty($types)) || is_null($types) || !$types) {
			unset($_SESSION['flash_messages']);
		} elseif (!is_array($types)) {
			$types = [$types];
		}
		
		foreach ($types as $type) {
			unset($_SESSION['flash_messages'][$type]);
		}
		
		return $this;
	}
	
	
}