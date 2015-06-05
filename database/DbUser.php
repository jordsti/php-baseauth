<?php

require_once("classes/User.php");
require_once("database/DbConnection.php");

class DbUser
{
 
  public static function IsUsernameOrEmailExists($username, $email)
  {
    $exists = false;
  
    $u_name = $username;
    $u_email = $email;
    
    $con = new DbConnection();
    
    $query = "SELECT user_id, username, email FROM users WHERE username = ? OR email = ?";
    $st = $con->prepare($query);
    $st->bind_param("ss", $u_name, $u_email);
    $st->bind_result($u_id, $uname, $uemail);
    $st->execute();
    
    if($st->fetch())
    {
      $exists = true;
    }
    
    $con->close();
    return $exists;
  }

  public static function AddUser($username, $hash_type, $salt, $password, $first_name, $last_name, $email, $created_on)
  {
    $u_name = $username;
    $u_hashType = $hash_type;
    $u_salt = $salt;
    $u_pass = hash($hash_type, $salt.$password);
    $u_firstName = $first_name;
    $u_lastName = $last_name;
    $u_email = $email;
    $u_createdOn = $created_on;
  
    $con = new DbConnection();
    
    $query = "INSERT INTO users (username, first_name, last_name, salt, password, hash_type, email, created_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $st = $con->prepare($query);
    
    $st->bind_param("sssssssi", $u_name, $u_firstName, $u_lastName, $u_salt, $u_pass, $u_hashType, $u_email, $u_createdOn);
    $st->execute();
    
    $con->close();
  }

  public static function UpdateUserPassword($user_id, $hash_type, $salt, $password)
  {
    $u_salt = $salt;
    $u_hashType = $hash_type;
    $u_password = hash($hash_type, $salt.$password);
    $u_id = $user_id;
  
    $con = new DbConnection();
    $query = "UPDATE users SET password = ?, salt = ?, hash_type = ? WHERE user_id = ?";
    
    $st = $con->prepare($query);
    $st->bind_param("sssi", $u_password, $u_salt, $u_hashType, $u_id);
    $st->execute();
    
    $con->close();
  }

  public static function GetByUsername($username)
  {
    $user = new User();
    $con = new DbConnection();
    
    $query = "SELECT user_id, username, first_name, last_name, salt, password, hash_type, email, created_on FROM users WHERE username = ?";
    $st = $con->prepare($query);
    $st->bind_param("s", $username);
    $st->bind_result($u_id, $u_name, $u_firstName, $u_lastName, $u_salt, $u_password, $u_hashType, $u_email, $u_createdOn);
    $st->execute();
    
    if($st->fetch())
    {
      $user->id = $u_id;
      $user->username = $u_name;
      $user->firstName = $u_firstName;
      $user->lastName = $u_lastName;
      $user->salt = $u_salt;
      $user->password = $u_password;
      $user->hashType = $u_hashType;
      $user->email = $u_email;
      $user->createdOn = $u_createdOn;
    }
    
    $con->close();
    
    return $user;
  }
  
  public static function GetById($user_id)
  {
    $user = new User();
    
    $query = "SELECT user_id, username, first_name, last_name, salt, password, hash_type, email, created_on FROM users WHERE user_id = ?";
    
    $con = new DbConnection();
    
    $st = $con->prepare($query);
    
    $st->bind_param("i", $user_id);
    $st->bind_result($u_id, $u_name, $u_firstName, $u_lastName, $u_salt, $u_password, $u_hashType, $u_email, $u_createdOn);
    $st->execute();
    
    if($st->fetch())
    {
      $user->id = $u_id;
      $user->username = $u_name;
      $user->firstName = $u_firstName;
      $user->lastName = $u_lastName;
      $user->salt = $u_salt;
      $user->password = $u_password;
      $user->hashType = $u_hashType;
      $user->email = $u_email;
      $user->createdOn = $u_createdOn;
    }
    
    $con->close();
    return $user;
  }
}