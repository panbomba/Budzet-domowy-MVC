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
	
	public static function getExpensesByCategory($start, $end)
	{
		$data_poczatkowa = date($start);
		$data_koncowa = date($end);		
		$user_id = $_SESSION['user_id'];	
		$sql = "SELECT *, SUM(amount) FROM expenses WHERE date_of_expense BETWEEN '$data_poczatkowa' AND '$data_koncowa' AND user_id = '$user_id' GROUP BY expense_category_assigned_to_user_id";
		$db = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->execute();		
		return $stmt->fetch();	
	}
	public static function getIncomesByCategory($start, $end)
	{
		$data_poczatkowa = date($start);
		$data_koncowa = date($end);		
		$user_id = $_SESSION['user_id'];	
		$sql = "SELECT *, SUM(amount) FROM incomes WHERE date_of_income BETWEEN '$data_poczatkowa' AND '$data_koncowa' AND user_id = '$user_id' GROUP BY income_category_assigned_to_user_id";
		$db = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->execute();		
		return $stmt->fetch();			
	}
}