<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * PHP version 7.0
 */
class Payment extends \Core\Model
{
	public static function saveDefaultPayments($id_just_registered_user)
	{
		$sql = "INSERT INTO payment_methods_assigned_to_users (user_id, name) SELECT $id_just_registered_user AS user_id, name FROM payment_methods_default";
		$db = static::getDB();
		$stmt = $db->prepare($sql);		
		return $stmt->execute();		
	}		
}