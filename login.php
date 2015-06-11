<?php
require_once("actions/LoginAction.php");

$action = new LoginAction();
$action->execute();

require_once("header.php");
require_once("menu.php");

?>
    <div class="container col-sm-4">
      <h2>Please authenticate yourself</h2>
      <form class="form-horizontal" role="form" method="POST" action="login.php">
	<div class="form-group">
	  <label for="username" class="col-sm-2 control-label">Username</label>
	  <div class="col-sm-10">
	    <input type="text" name="username" id="username" class="form-control" placeholder="Username"/>
	  </div>
	</div>
	<div class="form-group">
	  <label for="password" class="col-sm-2 control-label">Password</label>
	  <div class="col-sm-10">
	    <input type="password" name="password" id="password" class="form-control" placeholder="Password"/>
	  </div>
	</div>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10">
	    <button type="submit" class="btn btn-default">Log in</button>
	  </div>
	</div>
      </form>
      <?php
      if($action->isSignUpOpen())
      {
		  ?>
		  <a href="signup.php">Don't have an account, create one !</a>
		  <?php
	  }
      ?>
      
    </div>

<?php
      require_once("footer.php");
