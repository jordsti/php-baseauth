<?php
require_once('actions/BaseAction.php');
require_once('database/DbGroup.php');

class UsersAdministrationAction extends BaseAction
{
	public static $BrowseUsers = 1;
	public static $NewUserForm = 2;
	public static $EditUserForm = 3;
	
	public $view;
	public $users;
	public $pageUser;
	public $userGroups;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->title = "Users Administration";
		$this->view = UsersAdministrationAction::$BrowseUsers;
		
		$this->mustHavePermission('manage_users');
		$this->users = array();
		$this->pageUser = new User();
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
				$this->pageUser = DbUser::GetById($_GET['user_id']);
				
				if(!$this->pageUser->isNull())
				{
					$this->userGroups = DbGroup::GetUserGroups($this->pageUser->id);
					$this->view = UsersAdministrationAction::$EditUserForm;
				}
				else
				{
					$this->addAlert(Alert::CreateDanger('Error', 'Invalid User.'));
					
					$this->view = UsersAdministrationAction::$BrowseUsers;
					$this->reexecute(array('action' => 'browse'));
				}
			}
		}
		else if(strcmp($action, 'save_user') == 0)
		{
			if(isset($_POST['user_id']) &&
			isset($_POST['first_name']) &&
			isset($_POST['last_name']) &&
			isset($_POST['email']))
			{
				$user_id = $_POST['user_id'];
				$firstName = $_POST['first_name'];
				$lastName = $_POST['last_name'];
				$email = $_POST['email'];
				
				$user = DbUser::GetById($user_id);
				
				if(!$user->isNull())
				{
					$user->firstName = $firstName;
					$user->lastName = $lastName;
					$user->email = $email;
					
					DbUser::Update($user);

					$this->addAlert(Alert::CreateSuccess('Success', 'User updated.'));
					$this->reexecute(array('action' => 'edit_user', 'user_id' => $user_id));
				}
				else
				{
					//error user not found
					$this->addAlert(Alert::CreateDanger('Error', 'This user doesn\'t exists.'));
					$this->reexecute(array('action' => 'browse'));
				}
			}
			else
			{
				//missing field, so edit form again
				$this->view = UsersAdministrationAction::$EditUserForm;
			}
		}
		else if(strcmp($action, 'remove_group') == 0)
		{
			if(isset($_GET['group_id']) && isset($_GET['user_id']))
			{
				DbGroup::RemoveUser($_GET['group_id'], $_GET['user_id']);
				
				$this->addAlert(Alert::CreateSuccess('Success', 'Group removed.'));
				$this->reexecute(array('action' => 'edit_user'));
			}
		}
		else if(strcmp($action, 'add_user') == 0)
		{
			if(isset($_POST['username']) &&
				isset($_POST['password']) &&
				isset($_POST['password2']) &&
				isset($_POST['first_name']) &&
				isset($_POST['last_name']) &&
				isset($_POST['email']))
			{
				$username = $_POST['username'];
				$password = $_POST['password'];
				$password2 = $_POST['password2'];
				$firstName = $_POST['first_name'];
				$lastName = $_POST['last_name'];
				$email = $_POST['email'];
				
				if(strcmp($password, $password2) == 0)
				{
					if(!DbUser::IsUsernameOrEmailExists($username, $email))
					{
						//creating the user
						$salt = User::GenerateSalt();
						$hashType = 'sha256';
						
						DbUser::Add($username, $salt, $hashType, $password, $firstName, $lastName, $email);
						
						$this->addAlert(Alert::CreateSuccess('Success', 'User added !'));
						$this->reexecute(array('action' => 'browse'));
						
					}
					else
					{
						$this->view = UsersAdministrationAction::$NewUserForm;
						$this->addAlert(Alert::CreateDanger('Warning', 'Username and/or Email already exists in the database.'));
					}
				}
				else
				{
					$this->view = UsersAdministrationAction::$NewUserForm;
					$this->addAlert(Alert::CreateDanger('Warning', 'Password mismatches.'));
				}
			}
			else
			{
				//need to revmap this with a method
				$this->reexecute(array('action' => 'browse'));
			}
		}
		else if(strcmp($action, 'change_password'))
		{
			if(isset($_POST['user_id']) &&
				isset($_POST['password']) &&
				isset($_POST['password2']))
			{
				$user_id = $_POST['user_id'];
				$password = $_POST['password'];
				$password2 = $_POST['password2'];
				
				if(strcmp($password, $password2) == 0)
				{
					$salt = User::GenerateSalt();
					$hashType = 'sha256';
					
					DbUser::UpdateUserPassword($user_id, $hashType, $salt, $password);
					$this->addAlert(Alert::CreateSuccess('Success', 'Password changed !'));
					$this->reexecute(array('action' => 'edit_user', 'user_id' => $user_id));
				}
				else
				{
					$this->addAlert(Alert::CreateWarning('Warning', 'Password mismatches.'));
					$this->reexecute(array('action' => 'edit_user', 'user_id' => $user_id));
				}
			}
			else
			{
				$this->reexecute(array('action' => 'browse'));
			}
		}
	}
	
	
}