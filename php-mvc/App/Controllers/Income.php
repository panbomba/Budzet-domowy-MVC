<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
//prawdopodobnie rowniez pozostale modele

use \App\Auth;
use \App\Flash;

class Income extends \Core\Controller
{
	public function newAction()
	{
		View::renderTemplate('Income/income.html');
	}	
}