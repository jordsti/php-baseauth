<?php

require_once("classes/SettingContainer.php");
require_once("classes/Setting.php");

class SettingFile
{
	private $settings;
	private $filename;
	
	public function __construct($filename='settings.conf')
	{
		$this->filename = $filename;
		$this->settings = array();
	}
	
	public function readFile()
	{
		$fp = fopen($this->filename, 'r');
		
		$line = fgets($fp);
		while(strlen($line) > 0)
		{
			$this->parseLine($line);
			$line = fgets($fp);
		}
		
		fclose($fp);
	}
	
	protected function parseLine($line)
	{
		$data = explode('=', $line, 2);
		if(count($data) == 2)
		{
			$setting_name = $data[0];
			$setting_value = $data[1];
			
			$setting = new Setting();
			
			$setting->name = $setting_name;
			$setting->value = $setting_value;
			
			$this->settings[] = $setting;
		}
	}
	
	public function saveFile()
	{
		$fp = fopen($this->filename, 'w');
		
		foreach($this->settings as $setting)
		{
			$line = $setting->name."=".$setting->value."\n";
			fwrite($fp, $line);
		}
		
		fclose($fp);
		
	}
	public function getContainer()
	{
		return new SettingContainer($this->settings);
	}
	
	public function getSettings()
	{
		return $this->settings;
	}
	
	public function getFilename()
	{
		return $this->filename;
	}
	
	public function add($setting)
	{
		$this->settings[] = $setting;
	}
}
