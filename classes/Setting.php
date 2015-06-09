<?php

require_once("classes/BaseObject.php");

class Setting extends BaseObject {
	
	public $name;
	public $value;
	
	public function __construct($data=array())
	{
		if(count($data) > 0)
		{
			$this->id = $data['setting_id'];
			$this->name = $data['setting_name'];
			$this->value = $data['setting_value'];
		}
		else
		{
			$this->id = 0;
			$this->name = "";
			$this->value = "";
		}
	}
	
	public function getInt()
	{
		return intval($this->value);
	}
	
	public function getBool()
	{
		return booval($this->value);
	}
	
	public function getFloat()
	{
		return floatval($this->value);
	}
}
