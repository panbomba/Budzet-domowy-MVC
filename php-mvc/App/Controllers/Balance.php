<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\ExpenseTransaction;
use \App\Models\IncomeTransaction;
use \App\Models\BalanceModel;

class Balance extends \Core\Controller
{
		public function newAction()
	{	
		if(isset($_POST['okres']))
		{
				if($_POST['okres'] == 1)
				{
					$data_poczatkowa = date("Y-m-d", strtotime("first day of this month"));
					$data_koncowa = date("Y-m-d", strtotime("today"));
				}
				else if($_POST['okres'] == 2)
				{
					$data_poczatkowa = date("Y-m-d", strtotime("first day of last month"));			
					$data_koncowa = 	date("Y-m-d", strtotime("last day of last month"));				
				}
				else if($_POST['okres'] == 3)
				{
					$data_poczatkowa = date("Y-m-d", strtotime("first day of January"));	
					$data_koncowa = date("Y-m-d", strtotime("today"));			
				}
				else if($_POST['okres'] == 4)
				{
					$data_poczatkowa = $_POST['start'];
					$data_koncowa = $_POST['end'];
				}			
		}
		else
		{
			$data_poczatkowa = date("Y-m-d", strtotime("first day of this month"));		
			$data_koncowa = date("Y-m-d", strtotime("today"));			
		}
		
		$tablica4 = BalanceModel::getSumOfExpenses($data_poczatkowa, $data_koncowa);
		$tablica5 = BalanceModel::getSumOfIncomes($data_poczatkowa, $data_koncowa);		
		$tablica6 = BalanceModel::getSumOfDeletedIncomes($data_poczatkowa, $data_koncowa);		
		$tablica7 = BalanceModel::getSumOfDeletedExpenses($data_poczatkowa, $data_koncowa);		
		$args = [];
		$args['start_date'] = $data_poczatkowa;
		$args['end_date'] = $data_koncowa;
		$args['deleted_expenses'] = (double)$tablica7['SUM(amount)'];
		$args['deleted_incomes'] = (double)$tablica6['SUM(amount)'];
		$args['incomes'] = ((double)$tablica5['SUM(amount)'] + (double)$tablica6['SUM(amount)']) ;
		$args['expenses'] = -((double)$tablica4['SUM(amount)'] + (double)$tablica7['SUM(amount)']);				
		$args['balance'] = ((double)$tablica5['SUM(amount)'] + -(double)$tablica4['SUM(amount)']);
		$args['incomes_categories'] = BalanceModel::getIncomeCategories($data_poczatkowa, $data_koncowa);
		$args['expenses_categories'] = BalanceModel::getExpenseCategories($data_poczatkowa, $data_koncowa);

		View::renderTemplate('Balance/balance.html', $args);		
	}	
}		