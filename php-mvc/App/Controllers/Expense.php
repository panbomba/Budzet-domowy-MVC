<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\ExpenseTransaction;

class Expense extends \Core\Controller
{
	public function newAction()
	{
		View::renderTemplate('Expense/expense.html');
	}
	
	public function createAction()
	{	
		//var_dump($_POST);
		ExpenseTransaction::saveNewExpense();
			//wyswietl informacje o powodzeniu transakcji
			// dodac klawisz powrot ktory przekieruje do incomes view
	}
	
}