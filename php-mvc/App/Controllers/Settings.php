<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\ExpenseTransaction;
use \App\Models\IncomeTransaction;
//prawdopodobnie rowniez pozostale modele

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
				if($_POST['kategoria'] == 1) //przychod
				{
					//echo $_POST['nowa_nazwa'];
//dopisz nowa kategorie przychodu 
//IncomeTransaction::addNewIncomeCategory($_POST['nowa_nazwa']);
				}
				else if($_POST['kategoria'] == 2)
				{
//					echo 'lalala';
//dopisz nowa kategorie wydatku			
//ExpenseTransaction::addNewExpenseCategory($_POST['nowa_nazwa']);
				}
				else if($_POST['kategoria'] == 3)
				{;
//dopisz nowa metode platnosci
//ExpenseTransaction::addNewPaymentMethod($_POST['nowa_nazwa']);
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