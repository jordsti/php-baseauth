
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
  <li><a href="#">Dashboard</a></li>
  
<?php
  if($action->testPermission("manage_users"))
  {
  ?>
  <li><a href="users.php">Manage users</a></li>
  <?php
  }  
?>
  <li><a href="logout.php">Log out</a></li>
<?php
}

?>
  </ul>
  </div>
</nav>