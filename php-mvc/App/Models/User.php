<?php

namespace App\Models;
use App\Flash;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{
	
	public $errors = [];
	
	public function __construct($data = [])
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
		return static::findByEmail($email) !== false;
	}
	
	public static function findByEmail($email)
	{
		$sql = 'SELECT * FROM users WHERE email = :email';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		
		$stmt->execute();
		
		return $stmt->fetch();	
	}
	
	public static function authenticate($email, $password)
	{
		$user = static::findByEmail($email);
		
		if($user)
		{
			if (password_verify($password, $user->password))
			{
				return $user;
			}
		}
		return false;
	}
	
	public static function findByID($id)
	{
		$sql = 'SELECT * FROM users WHERE id = :id';
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		
		$stmt->execute();
		
		return $stmt->fetch();	
	}	

	public static function changeUserName($newName)
	{
		$user_id = $_SESSION['user_id'];
		
		$sql = "UPDATE users SET username = '$newName' WHERE id = '$user_id'";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		return $stmt->execute();
	}	

	public static function changeEmail($newEmail)
	{
		if(static::findByEmail($newEmail))
		{
			Flash::addMessage('Wybrany adres email jest zajęty! Wybierz ponownie.', Flash::WARNING);		
		}
		else
		{
		$user_id = $_SESSION['user_id'];
		
		$sql = "UPDATE users SET email = '$newEmail' WHERE  id = '$user_id'"; //DODAC SPRAWDZENIE EMAILA
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		return $stmt->execute();		
		}

	}	
	
	public static function getUserPassword($userId)
	{		
		$sql = "SELECT password FROM users WHERE  id = '$userId'"; 
		$db = static::getDB();
		$stmt = $db->prepare($sql);	
		$stmt->execute();		
		return $stmt->fetch();			
	}
	
	public static function changePassword($password, $newPassword, $newPassword2)
	{
		$user_id = $_SESSION['user_id'];
		$hash = static::getUserPassword($user_id);
		if(!password_verify($password, $hash['password']))
		{
			Flash::addMessage('Podane hasło jest niepoprawne', Flash::WARNING);		
		}
		else if($newPassword != $newPassword2)
		{
			Flash::addMessage('Podane hasła są różne', Flash::WARNING);					
		}
		else if((strlen($newPassword)<8) || (strlen($newPassword)>255))
		{
			Flash::addMessage('Hasło powinno mieć przynajmniej 8 znaków', Flash::WARNING);					
		}		
		
		else if((password_verify($password, $hash['password'])) && ($newPassword == $newPassword2))
		{			
	Flash::addMessage('Hasło zostało zmienione', Flash::SUCCESS);
			$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
			$sql = "UPDATE users SET password = '$newPasswordHash' WHERE  id = '$user_id'";		
			$db = static::getDB();
			$stmt = $db->prepare($sql);			
			return $stmt->execute();				
			
		}

	}		
}
