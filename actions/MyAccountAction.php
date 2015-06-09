<?php

require_once("actions/BaseAction.php");

class MyAccountAction extends BaseAction
{
	public $accountPermissions;
	public $accountGroups;
	
	public function __construct()
	{
		parent::__construct();
		if(!$this->isLogged())
		{
			header('location: login.php');
		}
	}
	
	public function execute()
	{
		$action = "";
		if(isset($_GET['action']))
		{
			$action = $_GET['action'];
		}
		
		if(strcmp($action, 'save_info') == 0)
		{
			//save user info here
			//todo
			
			if(isset($_POST['first_name']) && isset($_POST['last_name']))
			{
				$firstName = $_POST['first_name'];
				$lastName = $_POST['last_name'];
				
				$user = $this->user;
				
				$user->firstName = $firstName;
				$user->lastName = $lastName;
				
				DbUser::Update($user);
			}
			
			$this->addAlert(Alert::CreateSuccess('Success', 'Account information saved.'));
			$this->reloadUser();
		}
		
		
		
		$this->accountPermissions = DbGroup::GetUserPermissions($this->user->id);
		$this->accountGroups = DbGroup::GetUserGroups($this->user->id);
	}
}
