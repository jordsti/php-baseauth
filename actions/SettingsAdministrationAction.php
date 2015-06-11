<?php

require_once("actions/BaseAction.php");

class SettingsAdministrationAction extends BaseAction
{
	public static $BrowseSettings = 1;
	public static $NewSettingForm = 2;

	public $view;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->mustHavePermission('manage_settings');
		$this->title = "Settings Administration";
	}
	
	public function getSettings()
	{
		return $this->settings;
	}
	
	public function execute()
	{
		$action = 'browse';
		if(isset($_GET['action']))
		{
			$action = $_GET['action'];
		}
		
		if(strcmp($action, 'browse') == 0)
		{
			$this->view = SettingsAdministrationAction::$BrowseSettings;
		}
		else if(strcmp($action, 'new_setting') == 0)
		{
			$this->view = SettingsAdministrationAction::$NewSettingForm;
		}
		else if(strcmp($action, 'add_setting') == 0)
		{
			if(isset($_POST['setting_name']) && isset($_POST['setting_value']))
			{
				DbSetting::Add($_POST['setting_name'], $_POST['setting_value']);
				$this->addAlert(Alert::CreateSuccess('Success', 'Setting added.'));
				$this->reloadSettings();
			}
			$this->reexecute(array('action' => 'browse'));
		}
		else if(strcmp($action, 'delete_setting') == 0)
		{
			if(isset($_GET['setting_id']))
			{
				DbSetting::Delete($_GET['setting_id']);
				$this->addAlert(Alert::CreateSuccess('Success', 'Setting deleted.'));
				$this->reloadSettings();
			}
			
			$this->reexecute(array('action' => 'browse'));
		}
		else if(strcmp($action, 'save_settings') == 0)
		{
			$settings = DbSetting::GetAll();
			
			foreach($settings as $setting)
			{
				if(isset($_POST['setting_'.$setting->id]))
				{
					$setting->value = $_POST['setting_'.$setting->id];
				}
			}
			
			
			$container = new SettingContainer($settings);
			DbSetting::Save($container);
			$this->addAlert(Alert::CreateSuccess('Success', 'Settings saved.'));
			$this->reloadSettings();
			$this->reexecute(array('action' => 'browse'));
		}
	}
}
