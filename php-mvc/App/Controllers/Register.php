<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\IncomeTransaction;
use \App\Models\ExpenseTransaction;
use \App\Models\Payment;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Register extends \Core\Controller
{
    /**
     * Show the index page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Register/rejestracja.html');
    }
	
	public function createAction()
	{
		$user = new User($_POST);
		if($user->save())
		{
			$user_array = ($user->findByEmail($user->email));
			$id_just_registered_user = (int)($user_array->id);
			IncomeTransaction::saveDefaultIncomes($id_just_registered_user);
			ExpenseTransaction::saveDefaultExpenses($id_just_registered_user);
			Payment::saveDefaultPayments($id_just_registered_user);			
			$this->redirect('/register/success');
		}
		else
		{
			View::renderTemplate('Register/rejestracja.html', [
				'user' => $user
			]);				
		}
	}	
	
	public function successAction()
	{
		View::renderTemplate('Register/pierwsze_logowanie.html');
	}	
}
