<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

class Mainmenu extends Authenticated
{
	public function newAction()
	{
		View::renderTemplate('Mainmenu/main-menu.html');
	}	
}