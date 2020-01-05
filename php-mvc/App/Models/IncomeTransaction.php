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
class IncomeTransaction extends \Core\Model
{	
	public static function saveDefaultIncomes($id_just_registered_user)
	{
		$sql = "INSERT INTO incomes_category_assigned_to_users (user_id, name) SELECT $id_just_registered_user AS user_id, name FROM incomes_category_default";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		return $stmt->execute();		
	}
	
	public static function saveNewIncome()
	{
		$user_id = $_SESSION['user_id'];
		$kwota = (double)$_POST['kwota']; //spradzic czy bedzie poprawnie sumowac
		$data_przychodu = date($_POST['data_przychodu']); 
		$komentarz = $_POST['koment'];
		$tablica = static::getIncomeId();
		$income_category_assigned_to_user_id = (int)$tablica['id'];

		$sql = "INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
		VALUES ('$user_id', '$income_category_assigned_to_user_id', '$kwota', '$data_przychodu', '$komentarz')";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		Flash::addMessage('Transakcja dodana pomyślnie.');		
		View::renderTemplate('Income/income.html');

		//tu mozna wstawic informacje o pomyslnym dodaniu transakcji - flash?
		
		return $stmt->execute();			
	}
	
	public static function getIncomeId()
	{
		$user_id = $_SESSION['user_id'];
		$kategoria_przychodu = $_POST['przychod'];

		$sql = "SELECT * FROM incomes_category_assigned_to_users WHERE name='$kategoria_przychodu' AND user_id='$user_id'";
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		$stmt->execute();		
		return $stmt->fetch();	
	}

}