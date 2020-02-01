<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\ExpenseTransaction;

class Expense extends \Core\Controller
{
	public function newAction()
	{
		$args = [];
		$args['exp_cat_assigned'] = ExpenseTransaction::getExpenseCategoriesAssignedToUser();
		$args['pay_meth_assigned'] = ExpenseTransaction::getPaymentMethodsAssignedToUser();
		
		View::renderTemplate('Expense/expense.html', $args);
	}
	
	public function createAction()
	{	
		ExpenseTransaction::saveNewExpense();
			//wyswietl informacje o powodzeniu transakcji
			// dodac klawisz powrot ktory przekieruje do incomes view
	}

	
}