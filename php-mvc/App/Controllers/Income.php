<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\IncomeTransaction;

class Income extends \Core\Controller
{
	public function newAction()
	{
		View::renderTemplate('Income/income.html');
	}

	public function createAction() //czy powinienem przekazac id zalogowanego 
	{
		//var_dump($_POST);
		//$new_income = new Income($_POST);
		
		IncomeTransaction::saveNewIncome();
			//wyswietl informacje o powodzeniu transakcji
			// dodac klawisz powrot ktory przekieruje do incomes view
	}
}