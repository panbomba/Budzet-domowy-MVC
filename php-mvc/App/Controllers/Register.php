<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Register extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Register/rejestracja.html');
    }
	
	public function createAction()
	{
		//var_dump($_POST);
		$user = new User($_POST);
		//var_dump($user);
		if($user->save())
		{
			View::renderTemplate('Register/pierwsze_logowanie.html');
		}
		else
		{
			var_dump($user->errors);
		}
	}	
}
