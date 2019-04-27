<?php

namespace MN\Services;

use MN\Utils\Validator;
use MN\Entities\Account;
use MN\Repositories\AccountRepository;

class AccountForm extends Form {
	
	/** @var Account */
	private $account;
	
	/**
	 * @param $account Account
	 */
	public function edit($account) {
		
		if(!Validator::notEmpty($this->request->request->get("id"))) {
			$this->errors[] = "ID saknas";
		} else {
			$account->setId($this->request->request->get("id"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("first_name"))) {
			$this->errors[] = "Förnamn saknas";
		} else {
			$account->setFirstName($this->request->request->get("first_name"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("last_name"))) {
			$this->errors[] = "Efternamn saknas";
		} else {
			$account->setLastName($this->request->request->get("last_name"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("address"))) {
			$this->errors[] = "Adress saknas";
		} else {
			$account->setAddress($this->request->request->get("address"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("zip_code"))) {
			$this->errors[] = "Postnummer saknas";
		} else {
			$account->setZipCode($this->request->request->get("zip_code"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("city"))) {
			$this->errors[] = "Ort saknas";
		} else {
			$account->setCity($this->request->request->get("city"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("email"))) {
			$this->errors[] = "Kontaktmail saknas";
		} else {
			$account->setEmail($this->request->request->get("email"));
		}
		
		if(!Validator::emptyPassword($this->request->request->get("password_1"), $this->request->request->get("password_2"))) {
			if(!Validator::comparePassword($this->request->request->get("password_1"), $this->request->request->get("password_2"))) {
				$this->errors[] = "Lösenorden stämmer inte överens";
			} else {
				if(!Validator::password($this->request->request->get("password_1"))) {
					$this->errors[] = "Felaktigt format på lösenordet";
				} else {
					$account->setPassword(password_hash($this->request->request->get("password_1"), PASSWORD_DEFAULT));
				}
			}
		}
		
		$account->setPhone($this->request->request->get("phone"));
		
		if(!empty($this->request->request->get("submit"))) $this->setIsSubmitted(true);
		if(count($this->errors) === 0) {
			$this->setValidated(true);
			$this->account = $account;
		}
	}
	
	/**
	 *
	 */
	public function add() {
		$account = new Account();
		
		if(!Validator::username($this->request->request->get("username"))) {
			$this->errors[] = "Användarnamn saknas";
		} elseif(AccountRepository::usernameExists($this->request->request->get("username"))) {
			$this->errors[] = "Användarnamn är upptaget";
		} else {
			$account->setUsername($this->request->request->get("username"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("first_name"))) {
			$this->errors[] = "Förnamn saknas";
		} else {
			$account->setFirstName($this->request->request->get("first_name"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("last_name"))) {
			$this->errors[] = "Efternamn saknas";
		} else {
			$account->setLastName($this->request->request->get("last_name"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("address"))) {
			$this->errors[] = "Adress saknas";
		} else {
			$account->setAddress($this->request->request->get("address"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("zip_code"))) {
			$this->errors[] = "Postnummer saknas";
		} else {
			$account->setZipCode($this->request->request->get("zip_code"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("city"))) {
			$this->errors[] = "Ort saknas";
		} else {
			$account->setCity($this->request->request->get("city"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("email"))) {
			$this->errors[] = "Kontaktmail saknas";
		} else {
			$account->setEmail($this->request->request->get("email"));
		}
		
		if(!Validator::comparePassword($this->request->request->get("password_1"), $this->request->request->get("password_2"))) {
			$this->errors[] = "Lösenorden stämmer inte överens";
		} else {
			if(!Validator::password($this->request->request->get("password_1"))) {
				$this->errors[] = "Felaktigt format på lösenordet";
			} else {
				$account->setPassword(password_hash($this->request->request->get("password_1"), PASSWORD_DEFAULT));
			}
		}
		
		
		if(!empty($this->request->request->get("submit"))) $this->setIsSubmitted(true);
		if(count($this->errors) === 0) {
			$this->setValidated(true);
			$this->account = $account;
		}
	}
	
	
	/**
	 * @return Account
	 */
	public function getAccount() {
		return $this->account;
	}
	
	/**
	 * @param Account $account
	 * @return AccountForm
	 */
	public function setAccount($account) {
		$this->account = $account;
		return $this;
	}
	
	
}