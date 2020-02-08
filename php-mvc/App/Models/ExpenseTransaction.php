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
		$tablica2 = static::getExpenseId();
		$expense_category_assigned_to_user_id = (int)$tablica2['id'];
		$tablica3 = static::getPaymentId();
		$sposob_platnosci = (int)$tablica3['id']; 

		$sql = "INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
		VALUES ('$user_id', '$expense_category_assigned_to_user_id', '$sposob_platnosci', '$kwota', '$data_wydatku', '$komentarz')";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		Flash::addMessage('Transakcja dodana pomyślnie.');		
		View::renderTemplate('Expense/expense.html');

		//tu mozna wstawic informacje o pomyslnym dodaniu transakcji - flash?
		return $stmt->execute();		
			
	}

	public static function getExpenseId()
	{
		$user_id = $_SESSION['user_id'];
		$kategoria_wydatku = $_POST['wydatek'];

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
	
	public static function addNewExpenseCategory($newCategory)
	{
		$user_id = $_SESSION['user_id'];
		$sql = "INSERT INTO expenses_category_assigned_to_users (user_id, name) VALUES ('$user_id', '$newCategory')";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		return $stmt->execute();		
	}

	public static function addNewPaymentMethod($newMethod)
	{
		$user_id = $_SESSION['user_id'];
		$sql = "INSERT INTO payment_methods_assigned_to_users (user_id, name) VALUES ('$user_id', '$newMethod')";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		return $stmt->execute();		
	}	

	public static function changeExpenseCategoryName($newName)
	{
		;
		/*$user_id = $_SESSION['user_id'];
		$sql = "INSERT INTO payment_methods_assigned_to_users (user_id, name) VALUES ('$user_id', '$newMethod')";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		return $stmt->execute();		*/
	}	

	public static function changePaymentMethodName($newName)
	{
		;
		/*$user_id = $_SESSION['user_id'];
		$sql = "INSERT INTO payment_methods_assigned_to_users (user_id, name) VALUES ('$user_id', '$newMethod')";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		return $stmt->execute();		*/
	}		
	
}