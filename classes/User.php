<?php

class User {

  public static $UsernameMaxLength = 40;

  public $id;
  public $username;
  public $firstName;
  public $lastName;
  public $salt;
  public $password;
  public $hashType;
  public $createdOn;
  public $email;
  
  public function __construct()
  {
    $this->id = 0;
  }
  
  public function isNull()
  {
    return $this->id == 0;
  }
  
  public function isClearPassword()
  {
    return (strcmp($this->hashType, 'clear') == 0);
  }
  
  public function testPassword($clear_password)
  {
    if(strcmp($this->hashType, "clear") == 0)
    {
      //force a password update on this login !
      if(strcmp($clear_password, $this->password) == 0)
      {
	return true;
      }
      else
      {
	return false;
      }
    }
    else
    {
      $hash_password = hash($this->hashType, $this->salt.$clear_password);
      if(strcmp($hash_password, $this->password) == 0)
      {
	return true;
      }
      else
      {
	return false;
      }
    }
  }
  
  public static function GenerateSalt()
  {
    $str = 'qwertyuiopasdfghjklzxcvbnm;QWERTYUIOPASDFGHJKLZXCVBNM!$%?&*()1234567890-=+';
    $str = str_shuffle($str);
    $str = substr($str, 0, 32);
    return $str;
  }
  
}
