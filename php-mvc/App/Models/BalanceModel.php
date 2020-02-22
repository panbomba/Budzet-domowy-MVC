<?php

namespace App\Models;
use \Core\View;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
 
class BalanceModel extends \Core\Model
{	
	public static function getSumOfExpenses($start, $end)
	{
		$user_id = $_SESSION['user_id'];
		$data_poczatkowa = date($start);
		$data_koncowa = date($end);		
		$sql = "SELECT SUM(amount) FROM expenses WHERE date_of_expense BETWEEN '$data_poczatkowa' AND '$data_koncowa' AND user_id = '$user_id'";	
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();				
		return $stmt->fetch();	
	}
	
	public static function getSumOfIncomes($start, $end)
	{
		$data_poczatkowa = date($start);
		$data_koncowa = date($end);		
		$user_id = $_SESSION['user_id'];		
		$sql = "SELECT SUM(amount) FROM incomes WHERE date_of_income BETWEEN '$data_poczatkowa' AND '$data_koncowa' AND user_id = '$user_id'";	
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();				
		return $stmt->fetch();	
	}
	
	public static function getSumOfDeletedIncomes($start, $end)
	{
		$data_poczatkowa = date($start);
		$data_koncowa = date($end);		
		$user_id = $_SESSION['user_id'];		
		$sql = "SELECT SUM(amount) FROM deleted_incomes WHERE date_of_income BETWEEN '$data_poczatkowa' AND '$data_koncowa' AND user_id = '$user_id'";	
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();		
		
		return $stmt->fetch();	
	}

	public static function getSumOfDeletedExpenses($start, $end)
	{
		$data_poczatkowa = date($start);
		$data_koncowa = date($end);		
		$user_id = $_SESSION['user_id'];		
		$sql = "SELECT SUM(amount) FROM deleted_expenses WHERE date_of_expense BETWEEN '$data_poczatkowa' AND '$data_koncowa' AND user_id = '$user_id'";	
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();				
		return $stmt->fetch();	
	}	
	
	public static function getIncomeCategories($start, $end)
	{
		$data_poczatkowa = date($start);
		$data_koncowa = date($end);		
		$user_id = $_SESSION['user_id'];		
		
		$sql =  "SELECT name, SUM(amount) FROM incomes AS t2 INNER JOIN incomes_category_assigned_to_users AS t1 ON t2.income_category_assigned_to_user_id = t1.id AND t2.user_id = '$user_id' AND t2.date_of_income BETWEEN '$data_poczatkowa' AND '$data_koncowa' GROUP BY t1.name ";		
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();				
		return $stmt->fetchAll();
	}		

	public static function getExpenseCategories($start, $end)
	{
		$data_poczatkowa = date($start);
		$data_koncowa = date($end);		
		$user_id = $_SESSION['user_id'];		

		$sql =  "SELECT name, SUM(amount) FROM expenses AS t2 INNER JOIN expenses_category_assigned_to_users AS t1 ON t2.expense_category_assigned_to_user_id = t1.id AND t2.user_id = '$user_id' AND t2.date_of_expense BETWEEN '$data_poczatkowa' AND '$data_koncowa' GROUP BY t1.name ";		
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();				
		return $stmt->fetchAll();
	}
}