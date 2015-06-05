<?php
  require_once("actions/BaseAction.php");
  $action = new BaseAction();
  $action->execute();

  require_once("header.php");
?>
<h3>Welcome, <?php echo $action->getUser()->firstName; ?></h3>  
<?php

  require_once("footer.php");