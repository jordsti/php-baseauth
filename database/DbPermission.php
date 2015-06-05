<?php

require_once("database/DbConnection.php");
require_once("classes/Permission.php");

class DbPermission
{
  public static function GetAll()
  {
    $permissions = array();
    $con = new DbConnection();
    
    $query = "SELECT permission_id, permission_name, permission_value, permission_description FROM permissions";
    $st = $con->prepare($query);
    $st->bind_result($p_id, $p_name, $p_value, $p_desc);
    $st->execute();
    
    while($st->fetch())
    {
      $data = array(
	'permission_id' => $p_id,
	'permission_name' => $p_name,
	'permission_value' => $p_value,
	'permission_description' => $p_desc,
      );
    
      $perm = new Permission($data);
      $permissions[] = $perm;
    }
    
    $con->close();
    return $permissions;
  }
  
  public static function GetId($p_name, $p_value)
  {
    $perm_id = 0;
    $con = new DbConnection();
    
    $query = "SELECT permission_id FROM permissions WHERE permission_name = ? AND permission_value = ?";
    $st = $con->prepare($query);
    $st->bind_param("si", $p_name, $p_value);
    $st->bind_result($p_id);
    $st->execute();
    
    if($st->fetch())
    {
      $perm_id = $p_id;
    }
    
    $con->close();
    return $perm_id;
  }
  
  public static function Add($permission)
  {
    if($permission->isNull())
    {
      $p_name = $permission->name;
      $p_value = $permission->value;
      $p_desc = $permission->description;
  
      $con = new DbConnection();
      
      $query = "INSERT INTO permissions (permission_name, permission_value, permission_description) VALUES (?, ?, ?)";
      $st = $con->prepare($query);
      $st->bind_param("sis", $p_name, $p_value, $p_desc);
      $st->execute();
      
      $con->close();
      
      //fetching the id
      $permission->id = DbPermission::GetId($p_name, $p_value);
    }
    
  }
  
  public static function Delete($p_id)
  {
    $con = new DbConnection();
    $query = "DELETE FROM permissions WHERE permission_id = ?";
    $st = $con->prepare($query);
    $st->bind_param("i", $p_id);
    $st->execute();
    $con->close();
  }

}