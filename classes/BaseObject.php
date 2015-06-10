<?php

class BaseObject {
	public $id;
	
	public function __construct()
	{
		$this->id = 0;
	}
	
	public function isNull()
	{
		return $this->id == 0;
	}
}
