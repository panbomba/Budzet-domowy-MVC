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

	$category = $_POST['radioValue'];
	$selected_amount = (double)$_POST['amountValue'];	
	$selected_date = date($_POST['dateValue']);		
	$limit_exists = ExpenseTransaction::checkIfLimitSetup($category); 
	$category_limit = (double)$limit_exists['SUM(limity)'];
	
	if($category_limit !=0)
		{
			$suma_wydakow_miesiecznych = ExpenseTransaction::checkMonthlyExpensesForSetCategory($category, $selected_date); //TUTAJ DODAC OBSLUGE DATY
			$dotychczas_wydane =  (double)$suma_wydakow_miesiecznych['SUM(amount)']; 
			
			echo '<b>W wybranym miesiącu wydano '. $dotychczas_wydane .' w kategorii '. $category. '</b>';
			echo '<br>';
			if(($dotychczas_wydane + $selected_amount)> $category_limit)
			{
				echo '<span style="color:tomato"><b>Uważaj, ten wydatek spowoduje przekroczenie miesięcznego limitu o '.round(($dotychczas_wydane + $selected_amount - $category_limit),2). '</b></span>' ;
			}
			else if (($dotychczas_wydane + $selected_amount) < $category_limit)
			{
				echo'<span style="color:#5cb85c"><b>Możesz pozwolić sobie na ten wydatek bez przekroczenia ustalonego limitu</b></span>';
			}
			else if (($dotychczas_wydane + $selected_amount) == $category_limit)
			{
				echo '<span style="color:#15B5CA"><b>Dodając ten wydatek osiągniesz miesięczny limit dla kategorii</b></span>';
			}		
		}
	}
}