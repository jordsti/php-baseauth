<?php

class Permission {

  public $id;
  public $name;
  public $value;
  public $description;
  
  public function __construct($data=array())
  {
    if(count($data) > 0)
    {
      $this->id = $data['permission_id'];
      $this->name = $data['permission_name'];
      $this->value = $data['permission_value'];
      $this->description = $data['permission_description'];
    }
    else
    {
      $this->id = 0;
      $this->name = "";
      $this->value = 0;
      $this->description = "";
    }
    
  }
  
  public function getPermissionInt()
  {
    return pow(2, $this->value);
  }
  
  public function testPermission($user_permissions)
  {
    $permission_int = pow(2, $this->value);
    $test = $permission_int & $user_permissions;
    return $test == $permission_int;
  }
  
  public function isNull()
  {
    return $this->id == 0;
  }
}