<?php
/**
 * Created by PhpStorm.
 * User: mikaelnilsson
 * Date: 2018-09-28
 * Time: 22:24
 */

namespace MN\Controllers;

use MN\Services\Request;
use MN\Services\Response;
use MN\Services\LoginForm;
use MN\Services\AccountForm;
use MN\System\Controller;
use MN\System\Session;
use MN\Services\Authentication;
use MN\Repositories\AccountRepository;
use MN\Utils\FlashMessages;

class Account extends Controller {
	
	
	public function index() {
		if(!Session::isLoggedIn()) $this->redirect('/notAuthorized');
		
		$account = AccountRepository::getById(Session::getUserId());
		$this->render('account/index.twig', [
			'h1' => 'Konto',
			'account' => $account,
			'form_response' => $this->flash_message->display()
		]);
	}
	
	public function login() {
		$request = new Request();
		$response = new Response();
		$form = new LoginForm($request);
		
		if($form->isSubmitted()) {
			$form->login();
			if($form->isValidated()) {
				/** @var bool $session */
				$session = (new Authentication())->login($request);
				
				if($session) {
					$redirect = (Session::getSession()->userType() == 1) ? "/Admin/index" : '/Account/index';
					$response->setContent($redirect)->setStatusCode(301)->send();
				} else {
					$response->setContent('Inloggningen misslyckades')->setStatusCode(404)->send();
					//$response->setContent(['type' => 'error', 'message' => "Inloggningen misslyckades"])->setStatusCode(404)->send();
				}
			} else {
				$response->setContent($form->getErrors())->send();
			}
		} else {
			$this->flash_message->error($form->getErrors());
		}
		
		$this->render('account/login.twig', [
			'h1' => 'Logga in',
			'form_response' => $this->flash_message->display()
		]);
	}
	
	public function create() {
		$request = new Request();
		$response = new Response();
		$form = new AccountForm($request);
		
		if($form->isSubmitted()) {
			$form->add();
			if($form->isValidated()) {
				$account = AccountRepository::create($form->getAccount());
				
				if(!empty($account->id)) {
					$login = (new Authentication())->login($request);
					
					$this->flash_message->success("Kontot är skapad");
					$response->setStatusCode(301)->setContent('/account/index')->send();
				} else {
					//$this->flash_message->error("Något gick fel. Prova igen");
					$response->setContent('Något gick fel. Prova igen')->send();
				}
			} else {
				$response->setContent($form->getErrors())->send();
				//foreach($form->errors AS $error) {
				//	$this->flash_message->error($error);
				//}
			}
		} else {
			$this->flash_message->error($form->getErrors());
		}
		$this->render('account/create.twig', [
			'h1' => 'Skapa konto',
			'form_response' => $this->flash_message->display()
		]);
	}
	
	public function edit($id) {
		if(!Session::adminLoggedIn() && Session::getUserId() !== (int)$id) $this->redirect('/notAuthorized');
		
		$request = new Request();
		$account = AccountRepository::getById($id);
		$form = new AccountForm($request);
		
		if($form->isSubmitted()) {
			$form->edit($account);
			if($form->isValidated()) {
				$account = AccountRepository::edit($form->getAccount());
				
				if ($account) {
					$this->flash_message->success("Uppgifterna är uppdaterade", "/account/edit/".$id);
				} else {
					$this->flash_message->error("Något gick fel. Prova igen");
				}
			} else {
				foreach($form->errors AS $error) {
					$this->flash_message->error($error);
				}
			}
		}
		
		$this->render('account/edit.twig', [
			'h1' => 'Uppdatera uppgifter',
			'account' => $account,
			'form_response' => $this->flash_message->display()
		]);
	}
	
	public function logout() {
		(new Authentication())->logout();
		$this->redirect('/account/login');
	}
	
	public function history() {
		$this->render('account/history.twig', ['h1' => 'Dina tidigare inlägg']);
	}
	
	public function published() {
		$this->render('account/published.twig', ['h1' => 'Dina publicerade inlägg just nu']);
	}
	
	/*
	public function preview() {
		$this->render('account/index.twig', ['h1' => 'Konto']);
	}
	*/
}