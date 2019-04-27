<?php

namespace MN\Services;

use MN\Utils\Validator;
use MN\Entities\User;
use MN\Repositories\UserRepository;

class UserForm extends Form {
	
	/** @var User */
	private $user;
	
	/**
	 * @param $user User
	 */
	public function edit($user) {
		
		if(!Validator::username($this->request->request->get("username"))) {
			$this->errors[] = "Användarnamn saknas";
		} else {
			$user->setUsername($this->request->request->get("username"));
		}
		
		// if(!Validator::notEmpty($this->request->request->get("id"))) {
		// 	$this->errors[] = "ID saknas";
		// } else {
		// 	$user->setId($this->request->request->get("id"));
		// }
		
		if(!Validator::notEmpty($this->request->request->get("first_name"))) {
			$this->errors[] = "Förnamn saknas";
		} else {
			$user->setFirstName($this->request->request->get("first_name"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("last_name"))) {
			$this->errors[] = "Efternamn saknas";
		} else {
			$user->setLastName($this->request->request->get("last_name"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("address"))) {
			$this->errors[] = "Adress saknas";
		} else {
			$user->setAddress($this->request->request->get("address"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("zip_code"))) {
			$this->errors[] = "Postnummer saknas";
		} else {
			$user->setZipCode($this->request->request->get("zip_code"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("city"))) {
			$this->errors[] = "Ort saknas";
		} else {
			$user->setCity($this->request->request->get("city"));
		}
		
		if(!Validator::notEmpty($this->request->request->get("email"))) {
			$this->errors[] = "Kontaktmail saknas";
		} else {
			$user->setEmail($this->request->request->get("email"));
		}
		
		if(!Validator::emptyPassword($this->request->request->get("password_1"), $this->request->request->get("password_2"))) {
			if(!Validator::comparePassword($this->request->request->get("password_1"), $this->request->request->get("password_2"))) {
				$this->errors[] = "Lösenorden stämmer inte överens";
			} else {
				if(!Validator::password($this->request->request->get("password_1"))) {
					$this->errors[] = "Felaktigt format på lösenordet";
				} else {
					$user->setPassword(password_hash($this->request->request->get("password_1"), PASSWORD_DEFAULT));
				}
			}
		}
		
		if(!empty($this->request->request->get("submit"))) $this->setIsSubmitted(true);
		if(count($this->errors) === 0) {
			$this->setValidated(true);
			$this->user = $user;
		}
	}
	
	/**
	 * @param $user User
	 */
	public function patch($user) {
		
		if($this->request->request->has("id")) {
			$this->errors[] = "Kan inte uppdatera ID";
		}
		
		if($this->request->request->has("username")) {
			if(!Validator::username($this->request->request->get("username"))) {
				$this->errors[] = "Användarnamn saknas";
			} else {
				$user->setUsername($this->request->request->get("username"));
			}
		}
		
		if($this->request->request->has("first_name")) {
			if(!Validator::notEmpty($this->request->request->get("first_name"))) {
				$this->errors[] = "Förnamn saknas";
			} else {
				$user->setFirstName($this->request->request->get("first_name"));
			}
		}
		
		if($this->request->request->has("last_name")) {
			if(!Validator::notEmpty($this->request->request->get("last_name"))) {
				$this->errors[] = "Efternamn saknas";
			} else {
				$user->setLastName($this->request->request->get("last_name"));
			}
		}
		
		if($this->request->request->has("address")) {
			if(!Validator::notEmpty($this->request->request->get("address"))) {
				$this->errors[] = "Adress saknas";
			} else {
				$user->setAddress($this->request->request->get("address"));
			}
		}
		
		if($this->request->request->has("zip_code")) {
			if(!Validator::notEmpty($this->request->request->get("zip_code"))) {
				$this->errors[] = "Postnummer saknas";
			} else {
				$user->setZipCode($this->request->request->get("zip_code"));
			}
		}
		
		if($this->request->request->has("city")) {
			if(!Validator::notEmpty($this->request->request->get("city"))) {
				$this->errors[] = "Ort saknas";
			} else {
				$user->setCity($this->request->request->get("city"));
			}
		}
		
		if($this->request->request->has("email")) {
			if(!Validator::notEmpty($this->request->request->get("email"))) {
				$this->errors[] = "Kontaktmail saknas";
			} else {
				$user->setEmail($this->request->request->get("email"));
			}
		}
		
		if($this->request->request->has("password_1") && $this->request->request->has("password_2")) {
			if(!Validator::emptyPassword($this->request->request->get("password_1"), $this->request->request->get("password_2"))) {
				if(!Validator::comparePassword($this->request->request->get("password_1"), $this->request->request->get("password_2"))) {
					$this->errors[] = "Lösenorden stämmer inte överens";
				} else {
					if(!Validator::password($this->request->request->get("password_1"))) {
						$this->errors[] = "Felaktigt format på lösenordet";
					} else {
						$user->setPassword(password_hash($this->request->request->get("password_1"), PASSWORD_DEFAULT));
					}
				}
			}
		}
		
		if($this->request->request->has("password_1") && !$this->request->request->has("password_2")) {
			$this->errors[] = "Båda lösenorden måste vara ifyllda";
		}
		
		if(!$this->request->request->has("password_1") && $this->request->request->has("password_2")) {
			$this->errors[] = "Båda lösenorden måste vara ifyllda";
		}
		
		$this->setIsSubmitted(true);
		if(count($this->errors) === 0) {
			$this->setValidated(true);
			$this->user = $user;
		}
	}
	
	/**
	 *
	 */
	public function add() {
		$user = new User();
		$existing_username = 0;
		
		if(!Validator::username($this->request->request->get("username"))) {
			$this->errors[] = "Användarnamn saknas eller har inte ett giltigt email-format";
		} else {
			$user->setUsername($this->request->request->get("username"));
			$existing_username = UserRepository::doUserExists($user->username);
		}
		
		if($existing_username > 0) {
			$this->errors[] = "Användarnamnet finns redan registrerat";
		} else {
			if(!Validator::notEmpty($this->request->request->get("first_name"))) {
				$this->errors[] = "Förnamn saknas";
			} else {
				$user->setFirstName($this->request->request->get("first_name"));
			}
			
			if(!Validator::notEmpty($this->request->request->get("last_name"))) {
				$this->errors[] = "Efternamn saknas";
			} else {
				$user->setLastName($this->request->request->get("last_name"));
			}
			
			if(!Validator::notEmpty($this->request->request->get("address"))) {
				$this->errors[] = "Adress saknas";
			} else {
				$user->setAddress($this->request->request->get("address"));
			}
			
			if(!Validator::notEmpty($this->request->request->get("zip_code"))) {
				$this->errors[] = "Postnummer saknas";
			} else {
				$user->setZipCode($this->request->request->get("zip_code"));
			}
			
			if(!Validator::notEmpty($this->request->request->get("city"))) {
				$this->errors[] = "Ort saknas";
			} else {
				$user->setCity($this->request->request->get("city"));
			}
			
			if(!Validator::notEmpty($this->request->request->get("email"))) {
				$this->errors[] = "Kontaktmail saknas";
			} else {
				$user->setEmail($this->request->request->get("email"));
			}
			
			if(!Validator::comparePassword($this->request->request->get("password_1"), $this->request->request->get("password_2"))) {
				$this->errors[] = "Lösenorden stämmer inte överens";
			} else {
				if(!Validator::password($this->request->request->get("password_1"))) {
					$this->errors[] = "Felaktigt format på lösenordet";
				} else {
					$user->setPassword(password_hash($this->request->request->get("password_1"), PASSWORD_DEFAULT));
				}
			}
			
			$user->auth_token = "123";
		}
		
		
		if(!empty($this->request->request->get("submit"))) $this->setIsSubmitted(true);
		if(count($this->errors) === 0) {
			$this->setValidated(true);
			$this->user = $user;
		}
	}
	
	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}
	
	/**
	 * @param User $user
	 * @return UserForm
	 */
	public function setUser($user) {
		$this->user = $user;
		return $this;
	}
	
	
}