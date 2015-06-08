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
		users table..to do
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
		<div class="col-sm-8">
			<form method="post" action="users.php?action=save_user">
				<input type="hidden" name="user_id" value="<?php echo $action->user->id; ?>" />
				<h4>User : <?php echo $action->user->username; ?></h4>
				<div class="form-group col-sm-8">
					<label for="first_name">First Name</label>
					<input type="text" name="first_name" id="first_name" value="<?php echo $action->user->firstName; ?>" class="form-control"/>
				</div>
				<div class="form-group col-sm-8">
					<label for="last_name">Last Name</label>
					<input type="text" name="last_name" id="last_name" value="<?php echo $action->user->lastName; ?>" class="form-control"/>
				</div>
				<div class="form-group col-sm-8">
					<button class="btn btn-default" type="submit">Save</button>
				</div>
			</form>
		</div>
	</div>
	<?php
}
else if($action->view == UsersAdministrationAction::$NewUserForm)
{
	?>
	<div class="container">
		new user form here
	</div>
	<?php
	
}
?>


<?php

  require_once("footer.php");