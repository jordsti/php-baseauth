<?php

require_once("actions/BaseAction.php");

class ChangePasswordAction extends BaseAction
{
  public static $Success = 1;
  public static $Error = 2;
  public static $ShowForm = 3;
  
  public $message;
  public $status;
  public function __construct()
  {
    $constraints = array();
    $constraints[] = new ActionConstraint("no_change_password", true);
  
    parent::__construct($constraints);
    $this->title = "Change your password";
    $this->status = ChangePasswordAction::$ShowForm;
  }
  
  public function execute()
  {
    if($this->isLogged())
    {
      if(isset($_POST['cur_password']) &&
      isset($_POST['new_password']) &&
      isset($_POST['new_password2'])
      )
      {
	$cur_password = $_POST['cur_password'];
	$new_password = $_POST['new_password'];
	$new_password2 = $_POST['new_password2'];
	
	//test current password
	
	if($this->user->testPassword($cur_password))
	{
	  
	  //testing if the two new password are the same
	  if(strcmp($new_password, $new_password2) == 0)
	  {
	    //change the password !
	    //todo
	    //settings with default hash,
	    //generate a salt
	    $salt = User::GenerateSalt();
	    $hash_type = 'sha256';
	    DbUser::UpdateUserPassword($this->user->id, $hash_type, $salt, $new_password);
	    
	    $this->status = ChangePasswordAction::$Success;
	  }
	  else
	  {
	    $this->status = ChangePasswordAction::$Error;
	    $this->message = "The two passwords aren't the same";
	  }
	  
	}
	else
	{
	  $this->status = ChangePasswordAction::$Error;
	  $this->message = "Invalid current password";
	}
	
	
      }
    }
    else
    {
      //how you can change your password !?!
      //todo normalize a redirection with BaseAction::__construct
      header('location: login.php');
    }
  }
}