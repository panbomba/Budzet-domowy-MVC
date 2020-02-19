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
		$args = [];
		$args['exp_cat_assigned'] = ExpenseTransaction::getExpenseCategoriesAssignedToUser();
		$args['pay_meth_assigned'] = ExpenseTransaction::getPaymentMethodsAssignedToUser();
		
		View::renderTemplate('Expense/expense.html', $args);		
	}
	
	public function ajaxAction()
	{
	echo $_POST['radioValue'];	
	echo '<br>';
	echo $_POST['amountValue'];	
	echo '<br>';
	echo $_POST['dateValue'];	
	echo '<br>';
	//tutaj sprawdzenie - jezeli jest limit ustawiony to wysylamy zapytanie
	//funkcja sprawdz czy limit
	//wyslij 
	}

	
}