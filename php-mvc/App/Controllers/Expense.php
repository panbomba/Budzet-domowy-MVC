<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
//prawdopodobnie rowniez pozostale modele

use \App\Auth;
use \App\Flash;

class Expense extends \Core\Controller
{
	public function newAction()
	{
		View::renderTemplate('Expense/expense.html');
	}	
}