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
		$tablica4 = BalanceModel::getSumOfExpenses($_SESSION['data_poczatkowa'], $_SESSION['data_koncowa']);
		$tablica5 = BalanceModel::getSumOfIncomes($_SESSION['data_poczatkowa'], $_SESSION['data_koncowa']);			
		$args = [];
		$args['incomes'] = (double)$tablica5['SUM(amount)'];
		$args['expenses'] = -(double)$tablica4['SUM(amount)'];
		$args['balance'] = ((double)$tablica5['SUM(amount)'] + -(double)$tablica4['SUM(amount)']);
		$args['incomes_categories'] = BalanceModel::getIncomeCategories($_SESSION['data_poczatkowa'], $_SESSION['data_koncowa']);
		$args['expenses_categories'] = BalanceModel::getExpenseCategories($_SESSION['data_poczatkowa'], $_SESSION['data_koncowa']);		
		
		View::renderTemplate('Balance/balance.html', $args);
		unset($_SESSION['data_poczatkowa']);
		unset($_SESSION['data_koncowa']);
	}		
	
		public function createAction()
	{
		if($_POST['okres'] == 1)
		{
			$_SESSION['data_poczatkowa'] = date("Y-m-d", strtotime("first day of this month"));
			$_SESSION['data_koncowa'] = date("Y-m-d", strtotime("today"));
		}
		else if($_POST['okres'] == 2)
		{
			$_SESSION['data_poczatkowa'] = date("Y-m-d", strtotime("first day of last month"));
			$_SESSION['data_koncowa'] = 	date("Y-m-d", strtotime("last day of last month"));				
		}
		else if($_POST['okres'] == 3)
		{
			$_SESSION['data_poczatkowa'] = date("Y-m-d", strtotime("first day of January"));
			$_SESSION['data_koncowa'] = date("Y-m-d", strtotime("today"));			
		}
		else if($_POST['okres'] == 4)
		{
			$poczatek = $_POST['start'];
			$koniec = $_POST['end'];
			$_SESSION['data_poczatkowa'] = $poczatek;
			$_SESSION['data_koncowa'] = $koniec;
		}
		
		$tablica4 = BalanceModel::getSumOfExpenses($_SESSION['data_poczatkowa'], $_SESSION['data_koncowa']);
		$tablica5 = BalanceModel::getSumOfIncomes($_SESSION['data_poczatkowa'], $_SESSION['data_koncowa']);		
		$args = [];
		$args['incomes'] = (double)$tablica5['SUM(amount)'];
		$args['expenses'] = -(double)$tablica4['SUM(amount)'];
		$args['balance'] = ((double)$tablica5['SUM(amount)'] + -(double)$tablica4['SUM(amount)']);
		$args['incomes_categories'] = BalanceModel::getIncomeCategories($_SESSION['data_poczatkowa'], $_SESSION['data_koncowa']);
		$args['expenses_categories'] = BalanceModel::getExpenseCategories($_SESSION['data_poczatkowa'], $_SESSION['data_koncowa']);				

		View::renderTemplate('Balance/balance.html', $args);		
		unset($_SESSION['data_poczatkowa']);
		unset($_SESSION['data_koncowa']);		
	}
		public static function getStartDate()
		{
			if(isset($_SESSION['data_poczatkowa']))
			{
				return $_SESSION['data_poczatkowa'];
			}
			else 
			{
				$_SESSION['data_poczatkowa'] = date("Y-m-d", strtotime("first day of this month"));
				return $_SESSION['data_poczatkowa'];
			}				
		}
		public static function getEndDate()
		{
			if(isset($_SESSION['data_koncowa']))
			{
				return $_SESSION['data_koncowa'];
			}
			else 
			{
				$_SESSION['data_koncowa'] = date("Y-m-d", strtotime("today"));
				return $_SESSION['data_koncowa'];
			}					
		}
		
		public static function getExpenseForPieChart()
		{
			if(isset($_SESSION['wydatki_pie_chart']))
			{
				return $_SESSION['wydatki_pie_chart'];
			}					
		}			
	}		