<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\IncomeTransaction;

class Income extends Authenticated
{
	public function newAction()
	{
		$args = [];
		$args['inc_cat_assigned'] = IncomeTransaction::getIncomeCategoriesAssignedToUser();		
		View::renderTemplate('Income/Income.html', $args);
	}

	public function createAction() 
	{
		IncomeTransaction::saveNewIncome();
		$args = [];
		$args['inc_cat_assigned'] = IncomeTransaction::getIncomeCategoriesAssignedToUser();		
		View::renderTemplate('Income/Income.html', $args);
	}
}