<?php

require_once("database/DbConnection.php");
require_once("classes/Group.php");
class DbGroup
{
	public static function Update($group)
	{
		if(!$group->isNull())
		{
			$g_name = $group->name;
			$g_id = $group->id;
			
			$con = new DbConnection();
			
			$query = "UPDATE groups SET group_name = ? WHERE group_id = ?";
			$st = $con->prepare($query);
			$st->bind_param("si", $g_name, $g_id);
			$st->execute();
			
			$con->close();
		}
	}
	
	public static function Add($group_name)
	{
		$group = new Group();
		$group->name = $group_name;
		
		$con = new DbConnection();
		
		$query = "INSERT INTO groups (group_name) VALUES (?)";
		$st = $con->prepare($query);
		
		$st->bind_param("s", $group_name);
		$st->execute();
		
		$con->close();
		
		$con = new DbConnection();
		
		$query = "SELECT group_id FROM groups WHERE group_name = ?";
		$st2 = $con->prepare($query);
		$st2->bind_param("s", $group_name);
		$st2->bind_result($g_id);
		$st2->execute();
		
		if($st2->fetch())
		{
			$group->setId($g_id);
		}
		
		$con->close();
		return $group;
	}
	
	public static function GetAll()
	{
		$groups = array();
		
		$last_group_id = -1;
		$last_group = 0;
		
		$con = new DbConnection();
		
		$query = "SELECT g.group_id, g.group_name, p.permission_id, p.permission_name, p.permission_value, p.permission_description
		FROM groups g
		LEFT JOIN groups_permissions gp ON g.group_id = gp.group_id
		LEFT JOIN permissions p ON gp.permission_id = p.permission_id
		ORDER BY g.group_id";
		
		$st = $con->prepare($query);
		$st->bind_result($g_id, $g_name, $p_id, $p_name, $p_value, $p_desc);
		$st->execute();
		
		while($st->fetch())
		{
			if($last_group_id != $g_id)
			{
				$last_group = new Group();
				$last_group_id = $g_id;
				$last_group->id = $g_id;
				$last_group->name = $g_name;
				
				$groups[] = $last_group;
			}
			
			if(strlen($p_name) > 0)
			{
				$perm = new Permission();
				$perm->id = $p_id;
				$perm->name = $p_name;
				$perm->value = $p_value;
				$perm->description = $p_desc;
				
				$last_group->permissions->add($perm);
			}
		}
		
		$con->close();
		return $groups;
	}

	public static function GetUserGroups($user_id)
	{
		$groups = array();
		$last_group_id = -1;
		$last_group = 0;

		$con = new DbConnection();

		$query = "SELECT g.group_id, g.group_name, p.permission_id, p.permission_name, p.permission_value, p.permission_description
			FROM users_groups ug
			JOIN groups g ON g.group_id = ug.group_id
			LEFT JOIN groups_permissions gp ON gp.group_id = ug.group_id
			LEFT JOIN permissions p ON p.permission_id = gp.permission_id
			WHERE ug.user_id = ? ORDER BY g.group_id";

		$st = $con->prepare($query);
		$st->bind_param("i", $user_id);
		$st->bind_result($g_id, $g_name, $p_id, $p_name, $p_value, $p_desc);
		$st->execute();

		while($st->fetch())
		{
			if($last_group_id != $g_id)
			{
				$last_group_id = $g_id;
				$last_group = new Group();

				$last_group->name = $g_name;
				$last_group->id = $g_id;

				$groups[] = $last_group;
			}

			$perm = new Permission();
			$perm->id = $p_id;
			$perm->name = $p_name;
			$perm->value = $p_value;
			$perm->description = $p_desc;

			$last_group->permissions->add($perm);
		}

		$con->close();

		return $groups;
	}

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

	public static function Delete($g_id)
	{
		DbGroup::RemoveAllPermissions($g_id);
		DbGroup::RemoveAllUsers($g_id);
		
		$con = new DbConnection();
		$query = "DELETE FROM groups WHERE group_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $g_id);
		$st->execute();
		$con->close();
	}

	public static function RemoveAllPermissions($g_id)
	{
		$con = new DbConnection();
		$query = "DELETE FROM groups_permissions WHERE group_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $g_id);
		$st->execute();
		$con->close();
	}

	public static function RemoveAllUsers($g_id)
	{
		$con = new DbConnection();
		$query = "DELETE FROM users_groups WHERE group_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $g_id);
		$st->execute();
		$con->close();
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
	
	public static function RemovePermissions($g_id)
	{
		$con = new DbConnection();
		
		$query = "DELETE FROM groups_permissions WHERE group_id = ?";
		
		$st = $con->prepare($query);
		$st->bind_param("i", $g_id);
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
		
		$con->close();

		if(!$group->isNull())
		{
			$con = new DbConnection();
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
			
			$con->close();
		}


		return $group;
	}
  
}
