<?php
	require_once("actions/MyAccountAction.php");
	$action = new MyAccountAction();
	$action->execute();

	require_once("header.php");
	
?>
<div class="container">
	<div class="row">
		<div class="col-sm-8">
			<h4>My Account</h4>
			<form method="post" action="my_account.php?action=save_info" role="form">
				<div class="form-group col-sm-8">
					<strong>Username</strong> : <?php echo $action->getUser()->username; ?>
				</div>
				<div class="form-group col-sm-8">
					<label for="first_name">First Name</label>
					<input type="text" name="first_name" id="first_name" value="<?php echo $action->getUser()->firstName; ?>" class="form-control" />
				</div>
				<div class="form-group col-sm-8">
					<label for="first_name">Last Name</label>
					<input type="text" name="last_name" id="last_name" value="<?php echo $action->getUser()->lastName; ?>" class="form-control" />
				</div>
				<div class="form-group col-sm-8">
					<strong>Email</strong> : <?php echo $action->getUser()->email; ?>
				</div>
				<div class="form-group col-sm-8 col-sm-offset-1">
					<button type="submit" class="btn btn-default">Save</button>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-8">
			<a href="change_password.php">Change my password</a>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<h4>My group(s)</h4>
			<!-- Groups -->
			<ul>
			<?php
			foreach($action->accountGroups as $group)
			{
				?>
				<li><?php echo $group->name; ?></li>
				<?php
			}
			?>
			</ul>
		</div>
		<div class="col-sm-6">
			<h4>My permission(s)</h4>
			<!-- Permissions -->
						<ul>
			<?php
			foreach($action->accountPermissions->getPermissions() as $perm)
			{
				?>
				<li><?php echo $perm->name; ?></li>
				<?php
			}
			?>
			</ul>
		</div>
	</div>
</div>
<?php
	require_once("footer.php");
