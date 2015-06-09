<?php

require_once("database/DbConnection.php");
require_once("classes/Setting.php");
require_once("classes/SettingContainer.php");

class DbSetting
{
	public static function GetAll()
	{
		$settings = array();
		$con = new DbConnection();
		
		$query = "SELECT setting_id, setting_name, setting_value FROM settings";
		$st = $con->prepare($query);
		
		$st->bind_result($s_id, $s_name, $s_value);
		$st->execute();
		
		while($st->fetch())
		{
			$setting = new Setting();
			$setting->id = $s_id;
			$setting->name = $s_name;
			$setting->value = $s_value;
			
			$settings[] = $setting;
		}
			
		$con->close();
		return $settings;
	}
	
	public static function Delete($setting_id)
	{
		$con = new DbConnection();
		
		$query = "DELETE FROM settings WHERE setting_id = ?";
		
		$st = $con->prepare($query);
		$st->bind_param("i", $setting_id);
		$st->execute();
		
		$con->close();
	}
	
	public static function Add($name, $value)
	{
		$con = new DbConnection();
		
		$query = "INSERT INTO settings (setting_name, setting_value) VALUES (?, ?)";
		$st = $con->prepare($query);
		$st->bind_param("ss", $name, $value);
		$st->execute();
		$con->close();
	}
	
	public static function GetById($setting_id)
	{
		$setting = new Setting();
		$con = new DbConnection();
		$query = "SELECT setting_id, setting_name, setting_value FROM settings WHERE setting_id = ?";
		$st = $con->prepare($query);
		$st->bind_param("i", $setting_id);
		$st->bind_result($s_id, $s_name, $s_value);
		$st->execute();
		
		if($st->fetch())
		{
			$setting->id = $s_id;
			$setting->name = $s_name;
			$setting->value = $s_value;
		}
		
		$con->close();
		return $setting;
	}
	
	public static function Save($settings)
	{
		$current = new SettingContainer(DbSetting::GetAll());
		$inners = $settings->getSettings();
		
		foreach($inners as $setting)
		{
			$old = $current->getSetting($setting->name);
			
			if(!is_int($old))
			{
				if(strcmp($old->value, $setting->value) != 0)
				{
					DbSetting::Update($setting);
				}
			}
			else
			{
				DbSetting::Add($setting->name, $setting->value);
			}	
		}
	}
	
	public static function Update($setting)
	{
		if(!$setting->isNull())
		{
			$s_value = $setting->value;
			$s_id = $setting->id;
			
			$con = new DbConnection();
			$query = "UPDATE settings SET setting_value = ? WHERE setting_id = ?";
			
			$st = $con->prepare($query);
			$st->bind_param("si", $s_value, $s_id);
			$st->execute();
			$con->close();
		}
	}
}
