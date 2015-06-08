<?php

class Alert
{
	public static $Success = 1;
	public static $Info = 2;
	public static $Warning = 3;
	public static $Danger = 4;
	
	public $prefix;
	public $message;
	public $type;
	public $stamp;
	
	
	public function __construct($prefix='', $message='', $type=1)
	{
		$this->prefix = $prefix;
		$this->message = $message;
		$this->type = $type;
		$this->stamp = time();
	}
	
	public static function CreateSuccess($prefix, $message)
	{
		return new Alert($prefix, $message, Alert::$Success);
	}
	
	public static function CreateInfo($prefix, $message)
	{
		return new Alert($prefix, $message, Alert::$Info);
	}
	
	public static function CreateWarning($prefix, $message)
	{
		return new Alert($prefix, $message, Alert::$Warning);
	}
	
	public static function CreateDanger($prefix, $message)
	{
		return new Alert($prefix, $message, Alert::$Danger);
	}
}

class AlertRenderer 
{
	public function __construct()
	{
		
	}
	
	public function render($alert)
	{
		$alert_class = 'default';
		
		if($alert->type == Alert::$Success)
		{
			$alert_class = "success";
		}
		else if($alert->type == Alert::$Info)
		{
			$alert_class = "info";
		}
		else if($alert->type == Alert::$Warning)
		{
			$alert_class = "warning";
		}
		else if($alert->type == Alert::$Danger)
		{
			$alert_class = "danger";
		}
		
		$html = '<div class="alert alert-'.$alert_class.' alert-dismissible" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		 <strong>'.$alert->prefix.'</strong> '.htmlentities($alert->message).'</div>';
		
		return $html;
	}
}
