
<nav class="navbar navbar-inverse navbar-static-top"> 
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">InvoiceMaster</a>
    </div>
    <ul class="nav navbar-nav">
<?php
if($action->isLogged())
{
?>
  <li><a href="index.php">Dashboard</a></li>
  
<?php
  if($action->testPermission("manage_users"))
  {
  ?>
  <li><a href="users.php">Users</a></li>
  <?php
  }  

  if($action->testPermission("manage_groups"))
  {
  ?>
  <li><a href="groups.php">Groups</a></li>
  <?php
  }  
  
   if($action->testPermission("manage_settings"))
  {
  ?>
  <li><a href="settings.php">Settings</a></li>
  <?php
  }  
?>
	<li><a href="my_account.php">My Account</a></li>
	<li><a href="logout.php">Log out</a></li>
<?php
}

?>
  </ul>
  </div>
</nav>
<!-- Alerts section -->
<?php
if($action->containsAlert())
{
?>
<div class="row">
	<div class="container col-sm-6 col-sm-offset-2" id="alerts">
		<?php
		$action->renderAlerts();
		?>
	</div>
</div>
<?php
}
?>
