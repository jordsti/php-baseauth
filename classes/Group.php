<?php

require_once("classes/PermissionContainer.php");

class Group
{
  public $id;
  public $name;
  public $permissions;
  
  public function __construct($data=array())
  {
    $this->permissions = new PermissionContainer();
  
    if(count($data) > 0)
    {
      $this->id = $data['group_id'];
      $this->name = $data['group_name'];
    }
    else
    {
      $this->id = 0;
      $this->name = "";
    }
  }
  
  public function isNull()
  {
    return $this->id == 0;
  }
  
}