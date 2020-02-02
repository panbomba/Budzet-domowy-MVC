<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

class Mainmenu extends \Core\Controller
{
	public function newAction()
	{
		var_dump($_POST);
		View::renderTemplate('Mainmenu/main-menu.html');
	}	
}