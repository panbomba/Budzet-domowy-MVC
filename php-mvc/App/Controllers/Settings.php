<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\ExpenseTransaction;
use \App\Models\IncomeTransaction;

use \App\Auth;
use \App\Flash;

class Settings extends \Core\Controller
{
	public function newAction()
	{
		$args = [];	
		$args['inc_cat_assigned'] = IncomeTransaction::getIncomeCategoriesAssignedToUser();				
		$args['exp_cat_assigned'] = ExpenseTransaction::getExpenseCategoriesAssignedToUser();
		$args['pay_meth_assigned'] = ExpenseTransaction::getPaymentMethodsAssignedToUser();		
		View::renderTemplate('Settings/settings.html', $args);
	}	
	
	public function createAction()
	{
		if(isset($_POST['kategoria']))
		{
				if($_POST['kategoria'] == 1)
				{
					IncomeTransaction::addNewIncomeCategory($_POST['nowa_nazwa']);
				}
				else if($_POST['kategoria'] == 2)
				{
					ExpenseTransaction::addNewExpenseCategory($_POST['nowa_nazwa']);
				}
				else if($_POST['kategoria'] == 3)
				{
					ExpenseTransaction::addNewPaymentMethod($_POST['nowa_nazwa']);
				}	
		}
		
		if(isset($_POST['edycja']))
		{
				if($_POST['edycja'] == 1)
				{
					IncomeTransaction::changeIncomeCategoryName($_POST['nowa_nazwa']);
				}
				else if($_POST['edycja'] == 2)
				{
					ExpenseTransaction::changeExpenseCategoryName($_POST['nowa_nazwa']);
				}
				else if($_POST['edycja'] == 3)
				{
					ExpenseTransaction::changePaymentMethodName($_POST['nowa_nazwa']);
				}	
		}

		 var_dump($_POST);

		$args = [];
		$args['inc_cat_assigned'] = IncomeTransaction::getIncomeCategoriesAssignedToUser();				
		$args['exp_cat_assigned'] = ExpenseTransaction::getExpenseCategoriesAssignedToUser();
		$args['pay_meth_assigned'] = ExpenseTransaction::getPaymentMethodsAssignedToUser();				
		View::renderTemplate('Settings/settings.html', $args);
	}
	
}