<?php
  require_once("actions/DashboardAction.php");
  require_once("classes/TimeStamp.php");
  $action = new DashboardAction();
  $action->execute();

  require_once("header.php");
?>
<h3>Welcome, <?php echo $action->getUser()->firstName; ?></h3> 

<?php
	if(!$action->lastNews->isNull())
	{
		$timeStamp = new TimeStamp($action->lastNews->postedOn);
?>
<div class="container">
	<div class="row">
		<div class="col-sm-8">
			<?php echo htmlentities($action->lastNews->title); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-8">
			<?php echo htmlentities($action->lastNews->content); ?>
			<br />
			<em>Posted by <?php echo $action->lastNews->user->firstName; ?> <?php echo $action->lastNews->user->lastName; ?>, <?php echo $timeStamp->timeAgo(); ?></em>
		</div>
	</div>
</div>
<?php
	}

	if($action->testPermission('manage_news'))
	{
		?>
		<div class="container">
			<a href="news.php">Manage news</a>
		</div>
		<?php
	}
	
	require_once("footer.php");
