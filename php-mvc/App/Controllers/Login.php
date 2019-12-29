<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;

class Login extends \Core\Controller
{
	public function newAction()
	{
		View::renderTemplate('Login/logowanie.html');
	}
	
	public function createAction()
	{
		//$user = User::findByEmail($_POST['email']);
		//var_dump($user);
		$user = User::authenticate($_POST['email'], $_POST['password']);
		
		if ($user)
		{
			Auth::login($user);
			
			$this->redirect(Auth::getReturnToPage());
		}
		else
		{
			View::renderTemplate('Login/logowanie.html', ['email' => $_POST['email'],]);
		}
	}
	
	public function destroyAction()
	{
		Auth::logout();
		$this->redirect('/');
	}
}