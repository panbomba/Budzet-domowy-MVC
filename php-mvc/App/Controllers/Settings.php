<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\ExpenseTransaction;
use \App\Models\IncomeTransaction;

use \App\Auth;
use \App\Flash;

class Settings extends Authenticated
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
		
		if(isset($_POST['akcja']))
		{
				if($_POST['akcja'] == 1)
				{
					IncomeTransaction::changeIncomeCategoryName($_POST['stara_nazwa'], $_POST['nowa_nazwa']);
				}
				else if($_POST['akcja'] == 2)
				{
					ExpenseTransaction::changeExpenseCategoryName($_POST['stara_nazwa'], $_POST['nowa_nazwa'], $_POST['limit']);
				}
				else if($_POST['akcja'] == 3)
				{
					ExpenseTransaction::changePaymentMethodName($_POST['stara_nazwa'], $_POST['nowa_nazwa']);
				}
				else if($_POST['akcja'] == 4)
				{
					IncomeTransaction::deleteIncomeCategory($_POST['usuwana_kategoria']);
				}	
				else if($_POST['akcja'] == 5)
				{
					ExpenseTransaction::deleteExpenseCategory($_POST['usuwana_kategoria']);
				}					
				else if($_POST['akcja'] == 6)
				{
					ExpenseTransaction::deletePaymentMethod($_POST['usuwana_kategoria']);
				}	
				else if($_POST['akcja'] == 7)
				{
					User::changeUserName($_POST['nowa_nazwa_uzytkownika']);
				}		
				else if($_POST['akcja'] == 8)
				{
					User::changeEmail($_POST['nowy_email']);
				}		
				else if($_POST['akcja'] == 9)
				{
					User::changePassword($_POST['stare_haslo'], $_POST['nowe_haslo'], $_POST['nowe_haslo2']); //TUTAJ POTWIERDZENIE STAREGO HASLA ORAZ PODWOJNE WPISANIE NOWEGO HASLA
				}						
		}
		
		$args = [];
		$args['inc_cat_assigned'] = IncomeTransaction::getIncomeCategoriesAssignedToUser();				
		$args['exp_cat_assigned'] = ExpenseTransaction::getExpenseCategoriesAssignedToUser();
		$args['pay_meth_assigned'] = ExpenseTransaction::getPaymentMethodsAssignedToUser();				
		View::renderTemplate('Settings/settings.html', $args);
	}	
}