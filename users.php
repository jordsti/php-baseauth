<?php
  require_once("actions/UsersAdministrationAction.php");
  $action = new UsersAdministrationAction();
  $action->execute();

  require_once("header.php");
?>
<div class="container">
	<h4>Users Administration option</h4>
	<ul>
		<li><a href="users.php?action=new_user">New User</a></li>
		<li><a href="users.php?action=browse">Browse users</a></li>
	</ul>
</div>

<?php
if($action->view == UsersAdministrationAction::$BrowseUsers)
{
	?>
	<div class="container">
		<table class="table table-striped">
			<tr>
				<td>ID</td>
				<td>Username</td>
				<td>First Name</td>
				<td>Last Name</td>
				<td>Email</td>
				<td>Created On</td>
				<td>Actions</td>
			</tr>
			
			<?php
				foreach($action->users as $u)
				{
				?>
				<tr>
					<td><?php echo $u->id; ?></td>
					<td><?php echo $u->username; ?></td>
					<td><?php echo $u->firstName; ?></td>
					<td><?php echo $u->lastName; ?></td>
					<td><?php echo $u->email; ?></td>
					<td><?php echo $u->createdOn; ?></td>
					<td><a class="btn btn-default">Delete</a><a class="btn btn-default" href="users.php?action=edit_user&user_id=<?php echo $u->id; ?>">Edit</a></td>
				</tr>
				<?php
				}
			?>
			
		</table>
	</div>
	<?php
}
else if($action->view == UsersAdministrationAction::$EditUserForm)
{
	?>
	<div class="container">
		<div class="row">
			<div class="col-sm-8">
				<h4>User : <?php echo $action->pageUser->username; ?></h4>
				<form method="post" action="users.php?action=save_user" role="form">
					<input type="hidden" name="user_id" value="<?php echo $action->pageUser->id; ?>" />
					<div class="form-group col-sm-8">
						<label for="first_name">First Name</label>
						<input type="text" name="first_name" id="first_name" value="<?php echo $action->pageUser->firstName; ?>" class="form-control"/>
					</div>
					<div class="form-group col-sm-8">
						<label for="last_name">Last Name</label>
						<input type="text" name="last_name" id="last_name" value="<?php echo $action->pageUser->lastName; ?>" class="form-control"/>
					</div>
					<div class="form-group col-sm-8">
						<label for="last_name">Email</label>
						<input type="text" name="email" id="email" value="<?php echo $action->pageUser->email; ?>" class="form-control"/>
					</div>
					<div class="form-group col-sm-8">
						<button class="btn btn-default" type="submit">Save</button>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<h4>Password change</h4>
			<form method="post" action="users.php?action=update_password">
				<input type="hidden" name="user_id" value="<?php echo $action->pageUser->id; ?>" />
				<div class="form-group col-sm-8">
					<label for="password">New Password</label>
					<input type="password" name="password" id="password" />			
				</div>
				<div class="form-group col-sm-8">
					<label for="password2">New Password (Confirmation)</label>
					<input type="password" name="password2" id="password2" />			
				</div>
				<div class="form-group col-sm-8">
					<button class="btn btn-default" type="submit">Change password</button>
				</div>
			</form>
		</div>
		<div class="row">
			<h4>User Groups</h4>
			<div class="col-sm-8">
				<div class="row">
					<div class="col-sm-6">
					Group
					</div>
					<div class="col-sm-6">
					Permission(s)
					</div>
				</div>
				
				<?php
				foreach($action->userGroups as $group)
				{
					?>
					<div class="row">
						<div class="col-sm-6">
						<?php echo $group->name; ?> - <a href="users.php?action=remove_group&group_id=<?php echo $group->id; ?>&user_id=<?php echo $action->pageUser->id; ?>">Remove</a>
						</div>
						<div class="col-sm-6">
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
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
	<?php
}
else if($action->view == UsersAdministrationAction::$NewUserForm)
{
	?>
	<div class="container">
		<h4>New User</h4>
		<div class="col-sm-8">
			<form method="post" action="users.php?action=add_user" role="form">
				<div class="form-group col-sm-8">
					<label for="username">Username</label>
					<input class="form-control" type="text" placeholder="Username" name="username" id="username" />
				</div>
				<div class="form-group col-sm-8">
					<label for="password">Password</label>
					<input class="form-control" type="password" placeholder="Password" name="password" id="password" />
				</div>
				<div class="form-group col-sm-8">
					<label for="password2">Password (Confirmation)</label>
					<input class="form-control" type="password2" placeholder="Password" name="password2" id="password2" />
				</div>
				<div class="form-group col-sm-8">
					<label for="first_name">First Name</label>
					<input class="form-control" type="text" placeholder="First name" name="first_name" id="first_name" />
				</div>
				<div class="form-group col-sm-8">
					<label for="last_name">Last Name</label>
					<input class="form-control" type="text" placeholder="Last name" name="last_name" id="last_name" />
				</div>
				<div class="form-group col-sm-8">
					<label for="email">Email</label>
					<input class="form-control" type="text" placeholder="Email" name="email" id="email" />
				</div>
				<div class="form-group col-sm-8 col-sm-offset-2">
					<button type="submit" class="btn btn-default">Add</button>
				</div>
			</form>
		</div>
	</div>
	<?php
	
}
?>


<?php

  require_once("footer.php");