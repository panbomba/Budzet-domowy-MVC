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
		$_SESSION['data_poczatkowa'] = date("Y-m-d", strtotime("first day of this month"));
		$_SESSION['data_koncowa'] = date("Y-m-d", strtotime("today"));

		$tablica4 = BalanceModel::getSumOfExpenses($_SESSION['data_poczatkowa'], $_SESSION['data_koncowa']);
		$tablica5 = BalanceModel::getSumOfIncomes($_SESSION['data_poczatkowa'], $_SESSION['data_koncowa']);
		$_SESSION['suma_wydatkow'] = -(double)$tablica4['SUM(amount)'];
		$_SESSION['suma_przychodow'] = (double)$tablica5['SUM(amount)'];
		$_SESSION['bilans'] = ($_SESSION['suma_przychodow'] + $_SESSION['suma_wydatkow']); 
		static::showSumOfExpenses();
		static::showSumOfIncomes();
		
		View::renderTemplate('Balance/balance.html');
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
			$_SESSION['data_koncowa'] = 	date("Y-m-d", strtotime("today"));			
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
		$_SESSION['suma_wydatkow'] = -(double)$tablica4['SUM(amount)'];
		$_SESSION['suma_przychodow'] = (double)$tablica5['SUM(amount)'];
		$_SESSION['bilans'] = ($_SESSION['suma_przychodow'] + $_SESSION['suma_wydatkow']); 
		
		//z podzialem na kategorie
		$tablica6 = BalanceModel::getExpensesByCategory($_SESSION['data_poczatkowa'], $_SESSION['data_koncowa']);
		$tablica7 = BalanceModel::getIncomesByCategory($_SESSION['data_poczatkowa'], $_SESSION['data_koncowa']);
		var_dump($tablica6);
		echo '<br>';
		var_dump($tablica7);
			/*while ($wiersz2 = $tablica7->fetch_assoc())
			{
				$numer_kategori_przychodow = (int)$wiersz7['income_category_assigned_to_user_id'];
				$kategoria = $polaczenie->query("SELECT * FROM incomes_category_assigned_to_users WHERE id = '$numer_kategori_przychodow' AND user_id = '$user_id'");
				$wiersz9 = $kategoria->fetch_assoc();
				$kategorie_przychodow= '<b>'.$wiersz9['name'].' : </b>'.$wiersz7['SUM(amount)'].'<br>';
				$_SESSION['przychody_kategorie']  .=$kategorie_przychodow;	
				array_push($_SESSION['przychody_tablica'],$wiersz9['name'],$wiersz7['SUM(amount)']);	
			}		*/
	
		View::renderTemplate('Balance/balance.html');		
	}
		
		// przekazanie ustawionych dat do modelu ktory nastepnie zwroci dane do wyswietlenia
		//powrot do strony bilansu
		// czy zapisywac dane do sesji?
		public static function getStartDate()
		{
			if(isset($_SESSION['data_poczatkowa']))
			{
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
		}
		public static function showSumOfExpenses()		
		{
			if(isset($_SESSION['suma_wydatkow']))
			{
				return $_SESSION['suma_wydatkow'];
			}
		}
		public static function showSumOfIncomes()		
		{
			if(isset($_SESSION['suma_przychodow']))
			{
				return $_SESSION['suma_przychodow'];
			}			
		}	
		public static function getBalance()
		{
			if(isset($_SESSION['bilans']))
			{
				return $_SESSION['bilans'];
			}					
		}
	}		