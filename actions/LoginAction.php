<?php

require_once("actions/BaseAction.php");
require_once("database/DbUser.php");

class LoginAction extends BaseAction {

  public function __construct()
  {
    parent::__construct();
    
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
	  
	    header('location: index.php');
	  }
	  else
	  {
	    echo "Password mismatch";
	  }
	}
	else
	{
	  echo "Invalid user";
	}
    }
  }

}