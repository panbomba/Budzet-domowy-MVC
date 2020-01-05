<?php

namespace App\Models;

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
		$data_wydatku = $_POST['data_wydatku']; 
		//$kategoria_wydatku = $_POST['wydatek'];
		$komentarz = $_POST['komentarz']; 
		//$sposob_platnosci = $_POST['sposob']; 
		$sposob_platnosci = 2; //ma byc integer
		$user_id = $_SESSION['user_id'];	 //
		$expense_category_assigned_to_user_id = 1; //do poprawy	
	
		$sql = "INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
		VALUES ($user_id, $expense_category_assigned_to_user_id, $sposob_platnosci, $kwota, $data_wydatku, 'komentarz')";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		return $stmt->execute();		
	}	
	
}