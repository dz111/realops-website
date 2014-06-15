<?php
class User extends Model {
  protected $createtime = true;
  
  function flights() {
    return $this->hasMany('Flights');
  }
}
