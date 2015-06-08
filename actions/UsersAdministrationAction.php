<?php
require_once('actions/BaseAction.php');
class UsersAdministrationAction extends BaseAction
{
	public static $BrowseUsers = 1;
	public static $NewUserForm = 2;
	public static $EditUserForm = 3;
	
	public $view;
	public $users;
	public $user;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->title = "Users Administration";
		$this->view = UsersAdministrationAction::$BrowseUsers;
		
		$this->mustHavePermission('manage_users');
		$this->users = array();
		$this->user = new User();
	}
	
	public function execute()
	{
		if(isset($_GET['action']))
		{
			$action = $_GET['action'];
		}
		else
		{
			$action = 'browse';
		}
		
		if(strcmp($action, 'browse') == 0)
		{
			$this->view = UsersAdministrationAction::$BrowseUsers;
			$this->title = "Users Administration - Browse Users";
			//retrieve users
			$page = 0;
			$users_per_page = 50;
			
			if(isset($_GET['page']))
			{
				$page = $_GET['page'];
			}
			
			$start = $page * $users_per_page;
			
			$this->users = DbUser::Get($users_per_page, $start);
		}
		else if(strcmp($action, 'new_user') == 0)
		{
			$this->view = UsersAdministrationAction::$NewUserForm;
		}
		else if(strcmp($action, 'edit_user') == 0)
		{
			if(isset($_GET['user_id']))
			{
				$this->user = DbUser::GetById($_GET['user_id']);
				if(!$this->user->isNull())
				{
					$this->view = UsersAdministrationAction::$EditUserForm;
				}
			}
		}
	}
	
}