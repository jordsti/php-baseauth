<?php
require_once("actions/SignUpAction.php");

$action = new SignUpAction();
$action->execute();

require_once("header.php");

?>
    <div class="container col-sm-4">
      <h2>Create an account</h2>
      <form class="form-horizontal" role="form" method="POST" action="signup.php">
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
	  <label for="password2" class="col-sm-2 control-label">Password (Confirmation)</label>
	  <div class="col-sm-10">
	    <input type="password" name="password2" id="password2" class="form-control" placeholder="Password"/>
	  </div>
	</div>
	<div class="form-group">
	  <label for="email" class="col-sm-2 control-label">Email</label>
	  <div class="col-sm-10">
	    <input type="text" name="email" id="email" class="form-control" placeholder="Email"/>
	  </div>
	</div>
	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10">
	    <button type="submit" class="btn btn-default">Sign up</button>
	  </div>
	</div>
      </form>
    </div>

<?php
      require_once("footer.php");
