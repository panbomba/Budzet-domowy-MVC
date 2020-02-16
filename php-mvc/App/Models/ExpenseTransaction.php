<?php

namespace App\Models;
use \Core\View;
use \App\Flash;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class ExpenseTransaction extends \Core\Model
{
	public static function saveDefaultExpenses($id_just_registered_user) 
	{
		$sql = "INSERT INTO expenses_category_assigned_to_users (user_id, name) SELECT $id_just_registered_user AS user_id, name FROM expenses_category_default";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);

		return $stmt->execute();		
	}	
	public static function saveNewExpense()
	{
		$kwota = (double)$_POST['kwota']; //sprawdzic czy bedzie poprawnie sumowac
		$data_wydatku = date($_POST['data_wydatku']); 
		$komentarz = $_POST['komentarz']; 
		$user_id = $_SESSION['user_id'];	 //
		$tablica2 = static::getExpenseId($_POST['wydatek']);
		$expense_category_assigned_to_user_id = (int)$tablica2['id'];
		$tablica3 = static::getPaymentId();
		$sposob_platnosci = (int)$tablica3['id']; 

		$sql = "INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
		VALUES ('$user_id', '$expense_category_assigned_to_user_id', '$sposob_platnosci', '$kwota', '$data_wydatku', '$komentarz')";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);		
		Flash::addMessage('Transakcja dodana pomyślnie.');		
		return $stmt->execute();					
	}

	public static function getExpenseId($nazwa)
	{
		$user_id = $_SESSION['user_id'];
		$kategoria_wydatku = $nazwa;
		$sql = "SELECT * FROM expenses_category_assigned_to_users WHERE name='$kategoria_wydatku' AND user_id='$user_id'";
		$db = static::getDB();
		$stmt = $db->prepare($sql);		
		$stmt->execute();		
		return $stmt->fetch();	
	}

	public static function getPaymentId()
	{
		$user_id = $_SESSION['user_id'];
		$sposob_platnosci = $_POST['sposob'];
		$sql = "SELECT * FROM payment_methods_assigned_to_users WHERE name='$sposob_platnosci' AND user_id='$user_id'";
		$db = static::getDB();
		$stmt = $db->prepare($sql);		
		$stmt->execute();		
		return $stmt->fetch();	
	}	
	
	public static function getExpenseCategoriesAssignedToUser()
	{
		$user_id = $_SESSION['user_id'];
		
		$sql = "SELECT name FROM expenses_category_assigned_to_users WHERE user_id =  '$user_id'";	
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();				
		return $stmt->fetchAll();
	}	

	public static function getPaymentMethodsAssignedToUser()
	{
		$user_id = $_SESSION['user_id'];
		
		$sql = "SELECT name FROM payment_methods_assigned_to_users WHERE user_id =  '$user_id'";	
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();				
		return $stmt->fetchAll();
	}			

	public static function checkIfCategoryExists($category)
	{
		$user_id = $_SESSION['user_id'];
		$sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = '$user_id' AND lower(name) = lower('$category')";	
		$db = static::getDB();
		$stmt = $db->prepare($sql);		
		$stmt->execute();			
		return $stmt->fetchAll();
	}
	
	public static function addNewExpenseCategory($newCategory)
	{
		$check = static::checkIfCategoryExists($newCategory);
		if(empty($check))
		{
			$user_id = $_SESSION['user_id'];
			$sql = "INSERT INTO expenses_category_assigned_to_users (user_id, name) VALUES ('$user_id', '$newCategory')";		
			$db = static::getDB();
			$stmt = $db->prepare($sql);	
			Flash::addMessage('Kategoria '.$newCategory. ' została daodana do Twojego profilu. ', Flash::SUCCESS);			
			return $stmt->execute();					
		}	
		else if(!empty($check))
		{
			Flash::addMessage('Wybrana kategoria już istnieje!', Flash::WARNING);					
		}		
	}

	public static function checkIfPayMethodExists($method)
	{
		$user_id = $_SESSION['user_id'];
		$sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id = '$user_id' AND lower(name) = lower('$method')";	
		$db = static::getDB();
		$stmt = $db->prepare($sql);		
		$stmt->execute();			
		return $stmt->fetchAll();
	}

	public static function addNewPaymentMethod($newMethod)
	{
		$check = static::checkIfPayMethodExists($newMethod);
		if(empty($check))		
		{
			$user_id = $_SESSION['user_id'];
			$sql = "INSERT INTO payment_methods_assigned_to_users (user_id, name) VALUES ('$user_id', '$newMethod')";		
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			Flash::addMessage('Metoda płatności '.$newMethod. ' została dodana do Twojego profilu. ', Flash::SUCCESS);			
			return $stmt->execute();					
		}
		else if(!empty($check))
		{
			Flash::addMessage('Wybrany sposób płatności już istnieje!', Flash::WARNING);					
		}			
	}	
	
	public static function setLimit($oldName, $limit)
	{
		$user_id = $_SESSION['user_id'];		
		if($limit !=0)
		{
		$sql = "UPDATE expenses_category_assigned_to_users SET limity = '$limit' WHERE name = '$oldName' AND user_id = '$user_id'";		
		$db = static::getDB();
		$stmt = $db->prepare($sql);		
		$stmt->execute();	
		Flash::addMessage('Limit został zmieniony na '.$limit, Flash::SUCCESS);
		return $stmt->execute();				
		}
	}
	
	public static function changeExpenseCategoryName($oldName, $newName, $limit)
	{
		static::setLimit($oldName, $limit);
		$user_id = $_SESSION['user_id'];		
		if(!empty($newName))
		{
		$sql = "UPDATE expenses_category_assigned_to_users SET name = '$newName' WHERE name = '$oldName' AND user_id = '$user_id'";		
		$db = static::getDB();
		$stmt = $db->prepare($sql);	
		Flash::addMessage("Zmieniono nazwę $oldName na $newName", Flash::SUCCESS);		
		return $stmt->execute();				
		}		
	}	

	public static function changePaymentMethodName($oldName, $newName)
	{
		$user_id = $_SESSION['user_id'];		
		$sql = "UPDATE payment_methods_assigned_to_users SET name = '$newName' WHERE name = '$oldName' AND user_id = '$user_id'";		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		Flash::addMessage("Zmieniłeś nazwę $oldName na $newName", Flash::SUCCESS);				
		return $stmt->execute();	
	}		

	public static function deleteExpenseCategory($name)
	{
		$tablica = static::getExpenseId($name);
		$expense_category_assigned_to_user_id = (int)$tablica['id'];
		$user_id = $_SESSION['user_id'];
		$sql = "INSERT INTO deleted_expenses SELECT * FROM expenses WHERE expense_category_assigned_to_user_id = '$expense_category_assigned_to_user_id' AND user_id = '$user_id'";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();	
		
		$sql2 = "DELETE FROM expenses WHERE expense_category_assigned_to_user_id = '$expense_category_assigned_to_user_id' AND user_id = '$user_id'";
		$db = static::getDB();
		$stmt = $db->prepare($sql2);
		$stmt->execute();			
		
		$sql3 = "DELETE FROM expenses_category_assigned_to_users WHERE id = '$expense_category_assigned_to_user_id' AND user_id = '$user_id'";
		$db = static::getDB();
		$stmt = $db->prepare($sql3);
		$stmt->execute();			
		Flash::addMessage('Kategoria '.$name. ' została usunięta. ', Flash::SUCCESS);		
		return $stmt->execute();	
	}	

	public static function deletePaymentMethod($name)
	{
		$user_id = $_SESSION['user_id'];
		$sql3 = "DELETE FROM payment_methods_assigned_to_users WHERE name = '$name' AND user_id = '$user_id'";
		$db = static::getDB();
		$stmt = $db->prepare($sql3);
		$stmt->execute();				
		Flash::addMessage('Sposób płatności '.$name. ' został usunięty. ', Flash::SUCCESS);			
		return $stmt->execute();			
	}			
	
}