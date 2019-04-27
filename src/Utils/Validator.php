<?php

namespace MN\Utils;

use MN\Repositories\ValidationRepository;

class Validator {
	
	public function __construct() {
	
	}
	
	public static function notEmpty($input) {
		return !empty($input) ? true : false;
	}
	
	public static function username($username) {
		if(empty($username) || !filter_var($username, FILTER_VALIDATE_EMAIL))
			return false;
		
		return true;
	}
	
	public static function password($password) {
		$regex = (MN_ENV === 'dev') ? "/^[0-9]{3}$/" : "/^(?=.*?[0-9])(?=.*[A-Z]).{6,12}$/";
		
		if(empty($password)) return false;
		
		if(!preg_match($regex, $password)) return false;
		
		return true;
	}
	
	public static function emptyPassword($password1, $password2) {
		return(empty($password1) && empty($password2)) ? true : false;
	}
	
	public static function comparePassword($password1, $password2) {
		if(empty($password1) || empty($password2)) return false;
		
		if($password1 !== $password2) return false;
		
		return true;
	}
	
	public static function validateEmail($email) {
		if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
			return false;
		
		return true;
	}
	
	public static function noBannedContent($content) {
		$banned_words = ValidationRepository::getBannedWords();
		return Validator::checkForWord($content, $banned_words) ? true :  false;
	}
	
	public static function noSalesContent($content) {
		$banned_words = ValidationRepository::getSalesWords();
		return Validator::checkForWord($content, $banned_words) ? true :  false;
	}
	
	public static function checkForWord($text, $words) {
		foreach($words AS $word) {
			if(stripos($text, ' ' . strtolower($word) . ' ') !== false) return false;
		}
		return true;
	}
}