<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{
	
	public $errors = [];
	
	public function __construct($data)
	{
		foreach ($data as $key => $value)
		{
			$this->$key = $value;
		};
	}

	public function save()
	{
		
		$this->validate();
		
		if(empty($this->errors))
		{
		$password_hash = password_hash($this->password, PASSWORD_DEFAULT);
		
		$sql = 'INSERT INTO users (id, username, password, email)
		VALUES (NULL, :username, :password, :email)';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
		$stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		
		return $stmt->execute();
		}
		
		return false;
	}
	
	public function validate()
	{
		//Name
		if ($this->username == '')
		{
			$this->errors[] = 'Musisz podać swoje imię';
		}
		
		//email
		if (filter_var($this->email,  FILTER_VALIDATE_EMAIL)===false)
		{
			$this->errors[] = 'Niepoprawny adres email';
		}		
		if ($this->emailExists($this->email))
		{
			$this->errors[] = 'Wybrany email jest już zajęty';
		}				
		//password
		if ($this->password != $this->password2)
		{
			$this->errors[] = 'Podane hasła są różne';
		}
		if((strlen($this->password)<8) || (strlen($this->password)>255))
		{
			$this->errors[] = 'Niepoprawna długość hasła';			
		}		
	}
	protected function emailExists($email)
	{
		$sql = 'SELECT * FROM users WHERE email = :email';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		
		$stmt->execute();
		
		return $stmt->fetch() !== false;
	}

}
