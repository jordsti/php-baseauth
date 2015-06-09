<?php
	require_once("actions/SettingsAdministrationAction.php");
	$action = new SettingsAdministrationAction();
	$action->execute();

	require_once("header.php");

if($action->view == SettingsAdministrationAction::$BrowseSettings)
{
?>
<div class="container">
	<div class="row">
		<div class="col-sm-8">
			<h4>Setting(s)</h4>
			<a href="settings.php?action=new_setting">New Setting</a>
			<form method="post" action="settings.php?action=save_settings" role="form">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Id</th>
							<th>Name</th>
							<th>Value</th>
							<th>Action(s)</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$settings = $action->getSettings()->getSettings();
						foreach($settings as $setting)
						{
							?>
							<tr>
								<td><?php echo $setting->id; ?></td>
								<td><?php echo $setting->name; ?></td>
								<td><input type="text" name="setting_<?php echo $setting->id; ?>" value="<?php echo $setting->value; ?>" class="form-control" /></td>
								<td><a onclick="deleteSettingPrompt(<?php echo $setting->id; ?>);" ondblclick="deleteSetting(<?php echo $setting->id; ?>);" class="btn btn-default" id="setting_delete_<?php echo $setting->id; ?>">Delete</a></td>
							</tr>
							<?php
						}
						?>
						
						
					</tbody>
				</table>
				<div class="col-sm-8 col-sm-offset-1">
					<button type="submit" class="btn btn-default">Save</button>
				</div>
			</form>
		</div>
	</div>

</div>

<?php
}
else if($action->view == SettingsAdministrationAction::$NewSettingForm)
{
?>
<div class="container">
	<div class="col-sm-8">
		<h4>New Setting</h4>
		<form method="post" role="form" action="settings.php?action=add_setting">
			<div class="form-group col-sm-8">
				<label for="setting_name">Name</label>
				<input type="text" placeholder="Name" name="setting_name" id="setting_name" class="form-control" />
			</div>
			<div class="form-group col-sm-8">
				<label for="setting_value">Value</label>
				<input type="text" placeholder="Value" name="setting_value" id="setting_value" class="form-control" />
			</div>
			<div class="form-group col-sm-8 col-sm-offset-1">
				<button type="submit" class="btn btn-default">Add</button>
			</div>
		</form>
	</div>
</div>
<?php
}
?>

<?php
	require_once("footer.php");
