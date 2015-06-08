<?php
	require_once("actions/GroupsAdministrationAction.php");
	$action = new GroupsAdministrationAction();
	$action->execute();

	require_once("header.php");
	
?>
<div class="container">
	<h4>Groups Administration option</h4>
	<ul>
		<li><a href="groups.php?action=new_group">New Group</a></li>
		<li><a href="groups.php?action=browse">Browse groups</a></li>
		
		<?php
		if($action->testPermission('manage_permissions'))
		{
		?>
		<li><a href="groups.php?action=permissions">Manage Permission(s)</a></li>
		<?php
		}
		?>
	</ul>
</div>

<?php
if($action->view == GroupsAdministrationAction::$BrowseGroups)
{
	?>
	<div class="container col-sm-10 col-sm-offset-1">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Id</th>
					<th>Name</th>
					<th>Permissions</th>
					<th>Action(s)</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach($action->groups as $group)
			{
				?>
				<tr>
					<td><?php echo $group->id; ?></td>
					<td><?php echo $group->name; ?></td>
					<td>
						<ul>
						<?php
							foreach($group->permissions->getPermissions() as $perm)
							{
								?>
								<li><?php echo $perm->name; ?></li>
								<?php
							}
						?>
						</ul>
					</td>
					<td>
						<button type="button" class="btn btn-default" href="#">Delete</button>
						<a class="btn btn-default" href="groups.php?action=edit_group&group_id=<?php echo $group->id; ?>">Edit</a>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
	</div>
	<?php
}
else if($action->view == GroupsAdministrationAction::$NewGroupForm)
{
?>
<div class="container col-sm-8 col-sm-offset-1">
	<h4>New Group</h4>
	<form method="post" action="groups.php?action=add_group" role="form">
		<div class="row">
			<div class="form-group col-sm-8">
				<label for="group_name">Name</label>
				<input type="text" class="form-control" name="group_name" id="group_name" placeholder="Group Name" />
			</div>
		</div>
		<div class="form-group com-sm-8">
			<h5>Permission(s)</h5>
			<?php
			foreach($action->getPermissions()->getPermissions() as $perm)
			{
				?>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="<?php echo $perm->name; ?>" />
						<?php echo $perm->name; ?> : <?php echo $perm->description; ?>
					</label>
				</div>
				<?php
			}
			?>
		</div>
		<div class="form-group col-sm-8 col-sm-offset-1">
			<button type="submit" class="btn btn-default">Add</button>
		</div>
	</form>
</div>
<?php
}
else if($action->view == GroupsAdministrationAction::$BrowsePermissions)
{
?>
<div class="container">
	<div class="row">
		<div class="col-sm-8 col-sm-offset-1">
			<a href="groups.php?action=new_permission">New Permission</a>
		</div>
	</div>
	<div class="row">
		<h4>Permissions</h4>
		<table class="table table-striped">
			<thead>
			<tr>
				<th>Id</th>
				<th>Name</th>
				<th>Value</th>
				<th>Description</th>
				<th>Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach($action->getPermissions()->getPermissions() as $perm)
			{
				?>
				<tr>
					<td><?php echo $perm->id; ?></td>
					<td><?php echo $perm->name; ?></td>
					<td><?php echo $perm->value; ?></td>
					<td><?php echo $perm->description; ?></td>
					<td>
						<button type="button" class="btn btn-default" href="#">Delete</button>
						<a class="btn btn-default" href="groups.php?action=edit_permission&perm_id=<?php echo $perm->id; ?>">Edit</a>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
	</div>
</div>
<?php
}
else if($action->view == GroupsAdministrationAction::$EditPermissionForm)
{
?>
<div class="container col-sm-8">
	<h4>Edit Permission</h4>
	<form method="post" action="groups.php?action=save_permission" role="form">
		<input type="hidden" name="perm_id" value="<?php echo $action->permission->id; ?>" />
		<div class="form-group col-sm-8">
			<label for="perm_name">Name</label>
			<input type="text" name="perm_name" id="perm_name" value="<?php echo $action->permission->name; ?>" class="form-control" />
		</div>
		<div class="form-group col-sm-8">
			<label for="perm_value">Value</label>
			<input type="text" name="perm_value" id="perm_value" value="<?php echo $action->permission->value; ?>" class="form-control" />
		</div>
		<div class="form-group col-sm-8">
			<label for="perm_desc">Description</label>
			<input type="text" name="perm_desc" id="perm_desc" value="<?php echo $action->permission->description; ?>" class="form-control" />
		</div>
		<div class="form-group col-sm-8 col-sm-offset-1">
			<button type="submit" class="btn btn-default">Save</button>
		</div>
	</form>
</div>
<?php
}
else if($action->view == GroupsAdministrationAction::$NewPermissionForm)
{
?>
<div class="container col-sm-8 col-sm-offset-1">
	<h4>New Permission</h4>
	<form method="post" action="groups.php?action=add_permission" role="form">
		<div class="form-group col-sm-8">
			<label for="perm_name">Name</label>
			<input type="text" name="perm_name" id="perm_name" placeholder="Name" class="form-control" />
		</div>
		<div class="form-group col-sm-8">
			<label for="perm_value">Value</label>
			<input type="text" name="perm_value" id="perm_value" value="<?php echo $action->getPermissions()->highestValue()+1; ?>" class="form-control" />
		</div>
		<div class="form-group col-sm-8">
			<label for="perm_desc">Description</label>
			<input type="text" name="perm_desc" id="perm_desc"  placeholder="Description" class="form-control" />
		</div>
		<div class="form-group col-sm-8 col-sm-offset-1">
			<button type="submit" class="btn btn-default">Add</button>
		</div>
	</form>
</div>
<?php
}
else if($action->view == GroupsAdministrationAction::$EditGroupForm)
{
?>
<div class="container col-sm-8 col-sm-offset-1">
	<h4>Edit Group</h4>
	<form method="post" action="groups.php?action=save_group" role="form">
		<input type="hidden" name="group_id" value="<?php echo $action->group->id; ?>" />
		<div class="form-group col-sm-8">
			<label for="group_name">Name</label>
			<input type="text" name="group_name" id="group_name" value="<?php echo $action->group->name; ?>" class="form-control" />
		</div>
		<div class="form-group col-sm-8">
			<h4>Permission(s)</h4>
			<?php
			foreach($action->getPermissions()->getPermissions() as $perm)
			{
			?>
			<div class="checkbox">
				<label>
				<input type="checkbox" name="<?php echo $perm->name; ?>" <?php if($action->group->permissions->contains($perm)) { echo 'checked'; } ?>/>
				<strong><?php echo $perm->name; ?></strong> : <?php echo $perm->description; ?>
				</label>
			</div>
			<?php	
			}
			?>
			
		</div>
		<div class="form-group col-sm-8 col-sm-offset-1">
			<button type="submit" class="btn btn-default">Save</button>
		</div>
	</form>
</div>
<?php
}
?>

<?php
	require_once("footer.php");
