<?php
require_once("actions/ChangePasswordAction.php");

$action = new ChangePasswordAction();
$action->execute();

require_once("header.php");

?>
    <div class="container col-sm-4">
    <?php
    if($action->status == ChangePasswordAction::$ShowForm)
    {
    ?>
    
      <h2>Change your password</h2>
      <form class="form-horizontal" role="form" method="POST" action="change_password.php">
	<div class="form-group">
	  <label for="cur_password" class="col-sm-2 control-label">Current Password</label>
	  <div class="col-sm-10">
	    <input type="password" name="cur_password" id="cur_password" class="form-control" placeholder="Current Password"/>
	  </div>
	</div>
	<div class="form-group">
	  <label for="new_password" class="col-sm-2 control-label">New Password</label>
	  <div class="col-sm-10">
	    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password"/>
	  </div>
	</div>
	<div class="form-group">
	  <label for="new_password2" class="col-sm-2 control-label">New Password (Confirmation)</label>
	  <div class="col-sm-10">
	    <input type="password" name="new_password2" id="new_password2" class="form-control" placeholder="New Password"/>
	  </div>
	</div>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10">
	    <button type="submit" class="btn btn-default">Log in</button>
	  </div>
	</div>
      </form>
      
      <?php
      }
      else if($action->status == ChangePasswordAction::$Success)
      {
      ?>
      <h4>Password changed with success!</h4>
      <?php
      }
      else
      {
      ?>
      <h4><?php echo $action->message; ?></h4>
      <?php
      }
      ?>
      
    </div>

<?php
      require_once("footer.php");