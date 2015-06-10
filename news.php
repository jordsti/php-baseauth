<?php
	require_once("actions/NewsAction.php");
	require_once("classes/TimeStamp.php");
	$action = new NewsAction();
	$action->execute();

	require_once("header.php");
?>
	<div class="container">
		<div class="row">
			<h4>News Management</h4>
			<ul>
				<li><a href="news.php?action=browse">Browse News</a></li>
				<li><a href="news.php?action=new_news">New News</a></li>
			</ul>
		</div>
	</div>
<?php
	if($action->view == NewsAction::$BrowseNews)
	{
	?>
	<div class="container">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Id</th>
					<th>Title</th>
					<th>Posted On</th>
					<th>Posted By</th>
					<th>Action(s)</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($action->news as $n)
				{
					$timeStamp = new TimeStamp($n->postedOn);
					?>
					<tr>
						<td><?php echo $n->id; ?></td>
						<td><?php echo $n->title; ?></td>
						<td><?php echo $timeStamp->timeAgo(); ?></td>
						<td><?php echo $n->user->username; ?></td>
						<td>
							<a class="btn btn-default" onclick="deleteNewsPrompt(<?php echo $n->id; ?>);" ondblclick="deleteNews(<?php echo $n->id; ?>);" id="news_delete_<?php echo $n->id; ?>">Delete</a>
							<a class="btn btn-default" href="news.php?action=edit&news_id=<?php echo $n->id; ?>">Edit</a>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<?php	
	}
	else if($action->view == NewsAction::$NewNewsForm)
	{
	?>
	<div class="container">
		<h4>Post a news</h4>
		<form method="post" action="news.php?action=add_news" role="form">
			<div class="form-group col-sm-8">
				<label for="news_title">Title</label>
				<input type="text" name="news_title" id="news_title" placeholder="Title" class="form-control" />
			</div>
			<div class="form-group col-sm-8">
				<label for="news_content">Content</label><br />
				<textarea name="news_content" id="news_content" class="form-control">
				</textarea>
			</div>
			<div class="form-group col-sm-8 col-sm-offset-1">
				<button type="submit" class="btn btn-default">Post</button>
			</div>
		</form>
	</div>
	<?php
	}
	else if($action->view == NewsAction::$EditNewsForm)
	{
	?>
	<div class="container">
		<h4>Edit a news</h4>
		<form method="post" action="news.php?action=save_news" role="form">
			<input type="hidden" name="news_id" value="<?php echo $action->currentNews->id; ?>" />
			<div class="form-group col-sm-8">
				<label for="news_title">Title</label>
				<input type="text" name="news_title" id="news_title" value="<?php echo $action->currentNews->title; ?>" class="form-control" />
			</div>
			<div class="form-group col-sm-8">
				<label for="news_content">Content</label><br />
				<textarea name="news_content" id="news_content" class="form-control">
					<?php echo $action->currentNews->content; ?>
				</textarea>
			</div>
			<div class="form-group col-sm-8 col-sm-offset-1">
				<button type="submit" class="btn btn-default">Save</button>
			</div>
		</form>
	</div>
	<?php
	}
?>

<?php
	require_once("footer.php");
