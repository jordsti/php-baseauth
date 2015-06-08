<?php

require_once("actions/BaseAction.php");
require_once("database/DbGroup.php");

class GroupsAdministrationAction extends BaseAction
{
	public static $BrowseGroups = 1;
	public static $NewGroupForm = 2;
	public static $BrowsePermissions = 3;
	public static $EditPermissionForm = 4;
	public static $NewPermissionForm = 5;
	public static $EditGroupForm = 6;
	
	public $view;
	public $groups;
	public $group;
	public $permission;

	public function __construct()
	{
		parent::__construct();
		
		$this->groups = array();
		$this->group = new Group();
		$this->permission = new Permission();
			
		$this->mustHavePermission('manage_groups');
		$this->title = "Groups Administration";
		$this->view = GroupsAdministrationAction::$BrowseGroups;
	}
	
	public function execute()
	{
		$action = 'browse';
		if(isset($_GET['action']))
		{
			$action = $_GET['action'];
		}
		
		if(strcmp($action, 'browse') == 0)
		{
			$this->groups = DbGroup::GetAll();
			$this->view = GroupsAdministrationAction::$BrowseGroups;
		}
		else if(strcmp($action, 'new_group') == 0)
		{
			$this->view = GroupsAdministrationAction::$NewGroupForm;
		}
		else if(strcmp($action, 'add_group') == 0)
		{
			if(isset($_POST['group_name']))
			{
				$group_name = $_POST['group_name'];
				
				//only contains the ID of the permissions
				$group_perms = array();
				
				$permissions = $this->permissions->getPermissions();
				
				foreach($permissions as $perm)
				{
					if(isset($_POST[$perm->name]))
					{
						$value = $_POST[$perm->name];
						if(strcmp($value, 'on') == 0)
						{
							$group_perms[] = $perm->id;
						}
					}
				}
				
				$group = DbGroup::Add($group_name);
				$g_id = $group->id;
				foreach($group_perms as $p_id)
				{
					DbGroup::AddPermission($g_id, $p_id);
				}
				
				$this->addAlert(Alert::CreateSuccess('Success', 'Group added.'));
			}

			$this->reexecute(array('action' => 'browse'));
		}
		else if(strcmp($action, 'permissions') == 0)
		{
			$this->mustHavePermission('manage_permissions');
			$this->view = GroupsAdministrationAction::$BrowsePermissions;
		}
		else if(strcmp($action, 'edit_permission') == 0)
		{
			$this->mustHavePermission('manage_permissions');
			
			if(isset($_GET['perm_id']))
			{
				$this->permission = DbPermission::GetById($_GET['perm_id']);
				$this->view = GroupsAdministrationAction::$EditPermissionForm;
				
				if($this->permission->isNull())
				{
					$this->addAlert(Alert::CreateDanger('Error', 'Invalid Permission.'));
					$this->reexecute(array('action' => 'permissions'));
				}
			}
			else
			{
				$this->reexecute(array('action' => 'permissions'));
			}
		}
		else if(strcmp($action, 'save_permission') == 0)
		{
			$this->mustHavePermission('manage_permissions');
			
			if(isset($_POST['perm_id']) &&
				isset($_POST['perm_name']) &&
				isset($_POST['perm_value']) &&
				isset($_POST['perm_desc'])
			)
			{
				$perm_id = $_POST['perm_id'];
				
				$perm = DbPermission::GetById($perm_id);
				
				if(!$perm->isNull())
				{
					$perm->name = $_POST['perm_name'];
					$perm->value = $_POST['perm_value'];
					$perm->description = $_POST['perm_desc'];
					
					DbPermission::Update($perm);
					
					$this->addAlert(Alert::CreateSuccess('Success', 'Permission saved.'));
					
					$this->reloadPermissions();
				}
				else
				{
					$this->addAlert(Alert::CreateDanger('Error', 'Invalid Permission.'));
				}
			}
			
			$this->reexecute(array('action' => 'permissions'));
		}
		else if(strcmp($action, 'new_permission') == 0)
		{
			$this->mustHavePermission('manage_permissions');
			$this->view = GroupsAdministrationAction::$NewPermissionForm;
		}
		else if(strcmp($action, 'add_permission') == 0)
		{
			$this->mustHavePermission('manage_permissions');
			
			if(isset($_POST['perm_name']) &&
				isset($_POST['perm_value']) &&
				isset($_POST['perm_desc'])
			)
			{
				$perm = new Permission();
				$perm->name = $_POST['perm_name'];
				$perm->value = $_POST['perm_value'];
				$perm->description = $_POST['perm_desc'];
				
				DbPermission::Add($perm);
				
				$this->addAlert(Alert::CreateSuccess('Success', 'Permission added.'));
				
				$this->reloadPermissions();
			}
			
			$this->reexecute(array('action' => 'permissions'));
		}
		else if(strcmp($action, 'edit_group') == 0)
		{
			if(isset($_GET['group_id']))
			{
				$this->group = DbGroup::GetById($_GET['group_id']);
				$this->view = GroupsAdministrationAction::$EditGroupForm;
			}
			else
			{
				$this->reexecute(array('action' => 'browse'));
			}
		}
		else if(strcmp($action, 'save_group') == 0)
		{
			if(isset($_POST['group_id']) && 
				isset($_POST['group_name']))
			{
				$group_id = $_POST['group_id'];
				$group_name = $_POST['group_name'];
				
				$perm_id = array();
				$permissions = $this->permissions->getPermissions();
				
				foreach($permissions as $perm)
				{
					echo $perm->name;
					if(isset($_POST[$perm->name]))
					{
						$value = $_POST[$perm->name];
						if(strcmp($value, 'on') == 0)
						{
							$perm_id[] = $perm->id;
						}
					}
				}
				
				$group = DbGroup::GetById($group_id);
				if(!$group->isNull())
				{
					$group->name = $group_name;
					DbGroup::Update($group);
					
					DbGroup::RemovePermissions($group->id);
					
					foreach($perm_id as $p_id)
					{
						DbGroup::AddPermission($group->id, $p_id);
					}
					
					$this->addAlert(Alert::CreateSuccess('Success', 'Group modified.'));
				}
				else
				{
					$this->addAlert(Alert::CreateDanger('Error', 'Invalid Group'));
				}
			}
			
			$this->reexecute(array('action' => 'browse'));
		}
	}

}
