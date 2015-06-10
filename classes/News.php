<?php
require_once("classes/BaseObject.php");
require_once("classes/User.php");
require_once("classes/TimeStamp.php");
class News extends BaseObject
{
	public $title;
	public $content;
	public $postedOn;
	public $userId;
	
	public $user;
	
	public function __construct()
	{
		parent::__construct();
		$this->id = 0;
		$this->user = new User();
	}
	
	public function timeStamp()
	{
		return new TimeStamp($this->postedOn);
	}
	
}
