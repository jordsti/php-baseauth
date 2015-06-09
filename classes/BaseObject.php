<?php

class BaseObject {
	public $id;
	
	
	public function isNull()
	{
		return $this->id == 0;
	}
}
