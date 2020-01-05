<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Income extends \Core\Model
{
	public static function saveDefaultIncomes($id_just_registered_user)
	{
		
		$sql = "INSERT INTO incomes_category_assigned_to_users (user_id, name) SELECT $id_just_registered_user AS user_id, name FROM incomes_category_default";
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		
		return $stmt->execute();		
	}
	
}