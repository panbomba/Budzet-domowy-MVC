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
		$tablica = static::getIncomeId($_POST['przychod']);
		$income_category_assigned_to_user_id = (int)$tablica['id'];

		$sql = "INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
		VALUES ('$user_id', '$income_category_assigned_to_user_id', '$kwota', '$data_przychodu', '$komentarz')";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		Flash::addMessage('Transakcja dodana pomyślnie.');				
		return $stmt->execute();			
	}
	
	public static function getIncomeId($nazwa)
	{
		$user_id = $_SESSION['user_id'];
		$kategoria_przychodu = $nazwa;
		$sql = "SELECT * FROM incomes_category_assigned_to_users WHERE name='$kategoria_przychodu' AND user_id='$user_id'";
		$db = static::getDB();
		$stmt = $db->prepare($sql);		
		$stmt->execute();		
		return $stmt->fetch();	
	}
	
	public static function getIncomeCategoriesAssignedToUser()
	{
		$user_id = $_SESSION['user_id'];		
		$sql = "SELECT name FROM incomes_category_assigned_to_users WHERE user_id =  '$user_id'";	
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();				
		return $stmt->fetchAll();
	}		
	
	public static function checkIfCategoryExists($category)
	{
		$user_id = $_SESSION['user_id'];
		$sql = "SELECT * FROM incomes_category_assigned_to_users WHERE user_id = '$user_id' AND lower(name) = lower('$category')";	
		$db = static::getDB();
		$stmt = $db->prepare($sql);		
		$stmt->execute();				
		return $stmt->fetchAll();
	}
	
	public static function addNewIncomeCategory($newCategory)
	{
		$check = static::checkIfCategoryExists($newCategory);
		if(empty($check))
		{
			$user_id = $_SESSION['user_id'];
			$sql = "INSERT INTO incomes_category_assigned_to_users (user_id, name) VALUES ('$user_id', '$newCategory')";
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			Flash::addMessage('Kategoria '.$newCategory. ' została dodana do Twojego profilu. ', Flash::SUCCESS);				
			return $stmt->execute();				
		}		
		else if(!empty($check))
		{
			Flash::addMessage('Wybrana kategoria już istnieje!', Flash::WARNING);					
		}	
	}

	public static function changeIncomeCategoryName($oldName, $newName)
	{
		$user_id = $_SESSION['user_id'];		
		$sql = "UPDATE incomes_category_assigned_to_users SET name = '$newName' WHERE name = '$oldName' AND user_id = '$user_id'";		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		Flash::addMessage("Zmieniłeś nazwę $oldName na $newName", Flash::SUCCESS);		
		return $stmt->execute();	
	}

	public static function deleteIncomeCategory($name)
	{
		$tablica = static::getIncomeId($name);
		$income_category_assigned_to_user_id = (int)$tablica['id'];
		$user_id = $_SESSION['user_id'];
		$sql = "INSERT INTO deleted_incomes SELECT * FROM incomes WHERE income_category_assigned_to_user_id = '$income_category_assigned_to_user_id' AND user_id = '$user_id'";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();	
		
		$sql2 = "DELETE FROM incomes WHERE income_category_assigned_to_user_id = '$income_category_assigned_to_user_id' AND user_id = '$user_id'";
		$db = static::getDB();
		$stmt = $db->prepare($sql2);
		$stmt->execute();			
		
		$sql3 = "DELETE FROM incomes_category_assigned_to_users WHERE id = '$income_category_assigned_to_user_id' AND user_id = '$user_id'";
		$db = static::getDB();
		$stmt = $db->prepare($sql3);
		$stmt->execute();			
		Flash::addMessage('Kategoria '.$name. ' została usunięta. ', Flash::SUCCESS);				
		return $stmt->execute();	
	}		
}