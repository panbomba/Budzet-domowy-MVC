<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

class Login extends \Core\Controller
{
	public function newAction()
	{
		View::renderTemplate('Login/logowanie.html');
	}
	
	public function createAction()
	{
		$user = User::authenticate($_POST['email'], $_POST['password']);		
		if ($user)
		{
			Auth::login($user);
			View::renderTemplate('Mainmenu/main-menu.html');
		}
		else
		{
			Flash::addMessage('Nieudane logowanie. Spróbuj ponownie.', Flash::WARNING);
			View::renderTemplate('Login/logowanie.html', ['email' => $_POST['email'],]);
		}
	}
	
	public function destroyAction()
	{	
		$this->redirect('/login/show-logout-message');
		Auth::logout();
	}
	
	public function showLogoutMessageAction()
	{
		Flash::addMessage('Wylogowałeś się.');
		$this->redirect('/');		
	}
}