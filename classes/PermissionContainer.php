<?php

require_once("classes/Permission.php");

class PermissionContainer
{

	private $permissions;

	public function __construct($permissions=array())
	{
		//this is an array of Permission class
		$this->permissions = $permissions;
	}

	public function add($permission)
	{
		$this->permissions[] = $permission;
	}

	public function getPermissionsInt()
	{
		$permission_int = 0;
		foreach($this->permissions as $perm)
		{
			$permission_int = $permission_int | $perm->getPermissionInt();
		}
		return $permission_int;
	}

	public function getPermissions()
	{
		return $this->permissions;
	}

	public function getPermissionByName($name)
	{
		foreach($this->permissions as $perm)
		{
			if(strcmp($name, $perm->name) == 0)
			{
				return $perm;
			}
		}

		return new Permission();
	}

	public function highestValue()
	{
		$value = 0;

		foreach($this->permissions as $perm)
		{
			if($perm->value > $value)
			{
				$value = $perm->value;
			}
		}

		return $value;
	}

	public function testPermission($name, $user_permissions)
	{
		$perm = $this->getPermissionByName($name);
		if(!$perm->isNull())
		{
			return $perm->testPermission($user_permissions);
		}

		return false;
	}
	
	public function contains($perm)
	{
		foreach($this->permissions as $p)
		{
			if($p->id == $perm->id)
			{
				return true;
			}
		}
		
		return false;
	}

}
