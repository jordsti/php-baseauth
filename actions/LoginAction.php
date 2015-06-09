<?php

require_once("actions/BaseAction.php");
require_once("database/DbUser.php");

class LoginAction extends BaseAction {

	public function __construct()
	{
		$constraints = array();
		$constraints[] = new ActionConstraint('no_redirect', true);

		parent::__construct($constraints);
		$this->title = "Login";

		if($this->isLogged())
		{
			header('location: index.php');
		}
	}
  
	public function execute()
	{
		if(isset($_POST['username']) && isset($_POST['password']))
		{
			$username = $_POST['username'];
			$password = $_POST['password'];

			$user = DbUser::GetByUsername($username);

			if(!$user->isNull())
			{
				if($user->testPassword($password))
				{
					$_SESSION['user_id'] = $user->id;
					$this->pushAlert(Alert::CreateSuccess('Success', 'You\'re now connected with success.'));
					header('location: index.php');
				}
				else
				{
					$this->addAlert(Alert::CreateDanger('Error', 'Invalid Username and/or password.'));
				}
			}
			else
			{
				$this->addAlert(Alert::CreateDanger('Error', 'Invalid Username and/or password.'));
			}
		}
	}

}
