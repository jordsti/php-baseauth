<?php

require_once("classes/User.php");
require_once("database/DbUser.php");
require_once("database/DbPermission.php");
require_once("database/DbGroup.php");
session_start();

class ActionConstraint {
  public $name;
  public $value;
  
  public function __construct($name, $value)
  {
    $this->name = $name;
    $this->value = $value;
  }
  
}

class BaseAction
{
	protected $user;
	protected $permissions;
	protected $userPermissions;
	protected $title;
	protected $constraints;
  
  protected function getConstraint($c_name)
  {
    foreach($this->constraints as $c)
    {
      if(strcmp($c->name, $c_name) == 0)
      {
	return $c;
      }
    }
    
    return 0;
  }
  
  public function __construct($constraints=array())
  {
    $this->constraints = $constraints;
    $this->user = new User();
	//todo
	//do some methhods for getBoolConstraint, and other data type
	$no_redirect = $this->getConstraint('no_redirect');
	if(is_int($no_redirect))
	{
		$no_redirect = false;
	}
	else
	{
		$no_redirect = $no_redirect->value;
	}
    
    if(isset($_SESSION['user_id']))
    {
		$user_id = $_SESSION['user_id'];
		$this->user->id = $user_id;
		$user = DbUser::GetById($user_id);
		$perms = DbPermission::GetAll();
		$this->permissions = new PermissionContainer($perms);
      
		if(!$user->isNull())
		{
			$this->user = $user;
			//loading permissions
			$userPermissions = DbGroup::GetUserPermissions($this->user->id);
			$this->userPermissions = $userPermissions->getPermissionsInt();
	
			if($this->user->isClearPassword())
			{
				//force a password change
				//todo
				$no_change = $this->getConstraint("no_change_password");
				if(!is_int($no_change))
				{
					if(!$no_change->value)
					{
					  header('location: change_password.php');
					}
				}
				else
				{
					header('location: change_password.php');
				}
			}
	
		}
		else
		{
			//sending the user directly to the login
			if(!$no_redirect)
			{
				header('location: login.php');
			}
		}
      
    }
	else
	{
		//sending the user directly to the login
		if(!$no_redirect)
		{
			header('location: login.php');
		}
	}
    
    
  }

	public function mustHavePermission($perm_name)
	{
		if(!$this->permissions->testPermission($perm_name, $this->userPermissions))
		{
			//with a error message
			//todo notification
			header('location: index.php');
		}
	}
  
	public function testPermission($p_name)
	{
		return $this->permissions->testPermission($p_name, $this->userPermissions);
	}
  
	public function getTitle()
	{
		return $this->title;
	}
  
	public function execute()
	{

	}
  
	public function isLogged()
	{
		return $this->user->id != 0;
	}
  
	public function getUser()
	{
		return $this->user;
	}
  

}