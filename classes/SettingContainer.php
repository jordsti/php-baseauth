<?php

class SettingContainer
{
	protected $settings;
	
	
	public function __construct($settings=array())
	{
		$this->settings = $settings;
	}
	
	public function add($setting)
	{
		$this->settings[] = $setting;
	}
	
	public function size()
	{
		return count($this->settings);
	}	

	public function getString($name, $default="")
	{
		$setting = $this->getSetting($name);
		if(!is_int($setting))
		{
			return $setting->value;
		}
		else
		{
			return $default;
		}
	}

	public function getInt($name, $default=0)
	{
		$setting = $this->getSetting($name);
		if(!is_int($setting))
		{
			return $setting->getInt();
		}
		else
		{
			return $default;
		}
	}
	
	public function getBool($name, $default=true)
	{
		$setting = $this->getSetting($name);
		if(!is_int($setting))
		{
			return $setting->getBool();
		}
		else
		{
			return $default;
		}
	}
	
	public function getFloat($name, $default=0.00)
	{
		$setting = $this->getSetting($name);
		if(!is_int($setting))
		{
			return $setting->getFloat();
		}
		else
		{
			return $default;
		}
	}
	
	public function getSetting($name)
	{
		foreach($this->settings as $setting)
		{
			if(strcmp($name, $setting->name) == 0)
			{
				return $setting;
			}
		}
		
		return 0;
	}
	
	public function getSettings()
	{
		return $this->settings;
	}
}
