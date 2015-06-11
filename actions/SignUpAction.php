<?php

require_once("actions/BaseAction.php");

class SignUpAction extends BaseAction
{
	public function __construct()
	{
		$constraints = array();
		$constraints[] = new ActionConstraint('no_redirect', true);
		
		parent::__construct($constraints);
	}
	
	public function execute()
	{
		if($this->isSignUpOpen())
		{
			if(isset($_POST['username']) &&
				isset($_POST['password']) &&
				isset($_POST['password2']) &&
				isset($_POST['email'])
			)
			{
				$u_name = $_POST['username'];
				$u_pass = $_POST['password'];
				$pass2 = $_POST['password2'];
				$u_email = $_POST['email'];
				
				if(strcmp($u_pass, $pass2) == 0)
				{
					if(!DbUser::IsUsernameOrEmailExists($u_name, $u_email))
					{
						$len_username = strlen($u_name);
						
						if($len_username >= $this->settings->getInt("username_min", 4) && $len_username <= $this->settings->getInt("username_max", 12))
						{
							//creating the user
							$salt = User::GenerateSalt();
							$hashType = $this->settings->getString('hash_type', 'sha256');
							
							DbUser::Add($u_name, $salt, $hashType, $u_pass, "", "", $u_email);
							
							$default_group = $this->settings->getString('default_user_group', 'Users');
							
							$group = DbGroup::GetByName($default_group);
							
							if(!$group->isNull())
							{
								$user = DbUser::GetByUsername($u_name);
								if(!$user->isNull())
								{
									DbGroup::AddUser($group->id, $user->id);
								}
							}
							
							$this->pushAlert(Alert::CreateSuccess('Success', 'Account created!'));
							header('location: index.php');
						}
						else
						{
							$this->addAlert(Alert::CreateWarning('Warning', 'Username must be between '.$this->settings->getInt("username_min", 4).' and '.$this->settings->getInt("username_max", 12).' characters.'));
						}
					}
					else
					{
						$this->addAlert(Alert::CreateWarning('Warning', 'Username and/or Email already exists in the database.'));
					}
				}
				else
				{
					$this->addAlert(Alert::CreateWarning('Warning', 'Password mismatches.'));
				}
			}
		}
		else
		{
			$this->addAlert(Alert::CreateWarning('Warning', 'You can\'t create an account!'));
		}
	}
}
