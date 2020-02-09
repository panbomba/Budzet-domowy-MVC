<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\IncomeTransaction;

class Income extends \Core\Controller
{
	public function newAction()
	{
		$args = [];
		$args['inc_cat_assigned'] = IncomeTransaction::getIncomeCategoriesAssignedToUser();		
		View::renderTemplate('Income/income.html', $args);
	}

	public function createAction() //czy powinienem przekazac id zalogowanego 
	{
		IncomeTransaction::saveNewIncome();
		$args = [];
		$args['inc_cat_assigned'] = IncomeTransaction::getIncomeCategoriesAssignedToUser();		
		View::renderTemplate('Income/income.html', $args);
	}
}