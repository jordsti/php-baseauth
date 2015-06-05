<?php

require_once("database/DbConnection.php");
require_once("classes/Group.php");
class DbGroup
{

  public static function GetUserPermissions($user_id)
  {
    $permissions = new PermissionContainer();
  
    $con = new DbConnection();
    
    $query = "SELECT p.permission_id, p.permission_name, p.permission_value, p.permission_description FROM users_groups ug JOIN groups_permissions gp ON gp.group_id = ug.group_id JOIN permissions p ON p.permission_id = gp.permission_id WHERE ug.user_id = ?";
    $st = $con->prepare($query);
    $st->bind_param("i", $user_id);
    $st->bind_result($p_id, $p_name, $p_value, $p_desc);
    $st->execute();
    
    while($st->fetch())
    {
      $data = array(
	'permission_id' => $p_id,
	'permission_name' => $p_name,
	'permission_value' => $p_value,
	'permission_description' => $p_desc
      );
      
      $perm = new Permission($data);
      $permissions->add($perm);
    }
    
    $con->close();
    
    return $permissions;
  }

  public static function RemoveUser($g_id, $u_id)
  {
    $con = new DbConnection();
    
    $query = "DELETE FROM users_groups WHERE group_id = ? AND user_id = ?";
    $st = $con->prepare($query);
    $st->bind_param("ii", $g_id, $u_id);
    $st->execute();
    $con->close();
  }

  public static function AddUser($g_id, $u_id)
  {
    $con = new DbConnection();
    
    $query = "INSERT INTO users_groups (user_id, group_id) VALUES (?, ?)";
    $st = $con->prepare($query);
    $st->bind_param("ii", $u_id, $g_id);
    $st->execute();
    $con->close();
  }

  public static function AddPermission($g_id, $p_id)
  {
    $con = new DbConnection();
    
    $query = "INSERT INTO groups_permissions (group_id, permission_id) VALUES (?, ?)";
    $st = $con->prepare($query);
    $st->bind_param("ii", $g_id, $p_id);
    $st->execute();
    $con->close();
  }
  
  public static function RemovePermission($g_id, $p_id)
  {
      $con = new DbConnection();
    
    $query = "DELETE FROM groups_permissions WHERE group_id = ? AND permission_id = ?";
    $st = $con->prepare($query);
    $st->bind_param("ii", $g_id, $p_id);
    $st->execute();
    $con->close();
  }

  public static function GetById($g_id)
  {
    $group = new Group();
    
    $con = new DbConnection();
    
    $query = "SELECT group_id, group_name FROM groups WHERE group_id = ?";
    $st = $con->prepare($query);
    
    $st->bind_param("i", $g_id);
    $st->bind_result($group_id, $g_name);
    $st->execute();
    
    if($st->fetch())
    {
      $group->id = $group_id;
      $group->name = $g_name;
    }
    
    if(!$group->isNull())
    {
      $query = "SELECT p.permission_id, p.permission_name, p.permission_value, p.permission_description FROM groups_permissions gp JOIN permissions p ON gp.permission_id = p.permission_id WHERE gp.group_id = ?";
      $st = $con->prepare($query);
      $st->bind_param("i", $g_id);
      $st->bind_result($p_id, $p_name, $p_value, $p_desc);
      $st->execute();
      
      while($st->fetch())
      {
	$data = array(
	  'permission_id' => $p_id,
	  'permission_name' => $p_name,
	  'permission_value' => $p_value,
	  'permission_description' => $p_desc
	);
	
	$perm = new Permission($data);
	$group->permissions->add($perm);
      }
    }
    
    $con->close();
    
    return $group;
  }
  
}