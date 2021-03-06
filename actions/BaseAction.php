<?php

require_once("classes/User.php");
require_once("classes/Alert.php");
require_once("classes/SettingContainer.php");
require_once("classes/SettingFile.php");
require_once("database/DbSetting.php");
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
	public static $AdminUserCreationMethod = "admin_creation";
	public static $OpenUserCreationMethod = "open";
	
	protected $user;
	protected $permissions;
	protected $userPermissions;
	protected $title;
	protected $constraints;
	protected $alerts;
	protected $alertRenderer;
	protected $settings;
	protected $accountCreationMethod;
  
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
  
	public function renderAlerts()
	{
		foreach($this->alerts as $alert)
		{
			echo $this->alertRenderer->render($alert);
		}
		
		$this->alerts = array();
		$_SESSION['alerts'] = array();
	}
	
	public function getPermissions()
	{
		return $this->permissions;
	}
	
	public function containsAlert()
	{
		return count($this->alerts) > 0;
	}
	
	public function pushAlert($alert)
	{
		//push add the alert to the session vars
		if(isset($_SESSION['alerts']))
		{
			$alerts = $_SESSION['alerts'];
			$alerts[] = $alert;
		}
		else
		{
			$alerts = array();
			$alerts[] = $alert;
			$_SESSION['alerts'] = $alerts; 
		}
	}
	
	public function addAlert($alert)
	{
		$this->alerts[] = $alert;
	}
	
	protected function reloadPermissions()
	{
		$perms = DbPermission::GetAll();
		$this->permissions = new PermissionContainer($perms);
	}
	
	protected function reloadSettings()
	{
		$settings = DbSetting::GetAll();
		$this->settings = new SettingContainer($settings);
	}
	
	public function clearAlerts()
	{
		$_SESSION['alerts'] = array();
	}
	
	protected function reloadUser()
	{
		$this->user = DbUser::GetById($this->user->id);
	}
  
	public function __construct($constraints=array())
	{
		$this->alerts = array();
		$this->alertRenderer = new AlertRenderer();
		
		if(isset($_SESSION['alerts']))
		{
			//fetching alerts
			//clearing them when they are show
			$this->alerts = $_SESSION['alerts'];
		}

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

		//loading settings
		$settings = DbSetting::GetAll();
		$this->settings = new SettingContainer($settings);
		
		if($this->settings->size() == 0)
		{
			$this->initSettings();
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
	
	protected function initSettings()
	{
		//$this->settings = new SettingContainer();
		//put that into a SettingFile for import at intial runtime
		//hash type setting
		/*$hashtype = new Setting();
		$hashtype->name = "hash_type";
		$hashtype->value = "sha256";
		
		$user_min = new Setting();
		$user_min->name = "username_min";
		$user_min->value = "4";
		
		$user_max = new Setting();
		$user_max->name = "username_max";
		$user_max->value = "12";
		
		$signup_method = new Setting();
		$signup_method->name = "signup_method";
		$signup_method->value = "admin_creation";
		
		$this->settings->add($hashtype);
		$this->settings->add($user_min);
		$this->settings->add($user_max);
		$this->settings->add($signup_method);*/
		
		$settingFile = new SettingFile('actions/initial_settings.conf');
		
		$settingFile->readFile();
		
		$settings = $settingFile->getContainer();
		
		DbSetting::Save($settings);
	}
	
	public function isSignUpOpen()
	{
		return (strcmp($this->settings->getString('signup_method', BaseAction::$AdminUserCreationMethod), BaseAction::$OpenUserCreationMethod) == 0);
	}

	public function mustHavePermission($perm_name)
	{
		if(!$this->permissions->testPermission($perm_name, $this->userPermissions))
		{
			$this->pushAlert(Alert::CreateWarning('Warning', 'You don\'t have the permission do to this.'));			
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
	
	public function reexecute($vars=array())
	{
		foreach($vars as $n => $v)
		{
			$_GET[$n] = $v;
		}
		
		$this->execute();
	}
  
	public function isLogged()
	{
		return !$this->user->isNull();
	}
  
	public function getUser()
	{
		return $this->user;
	}
  

}
