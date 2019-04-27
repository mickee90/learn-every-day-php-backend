<?php

namespace MN\Controllers;

use MN\System\Controller;

class Index extends Controller {
	
	public function __construct() {
	
	}
	
	public function index() {
		$this->render('index.twig', ['h1' => 'Test']);
	}
	
	public function notFound() {
		$this->render('404.twig', ['h1' => '404 - Sidan hittades inte']);
	}
	
	public function notAuthorized() {
		$this->render('401.twig', ['h1' => '401 - Åtkomst nekad']);
	}
	
}