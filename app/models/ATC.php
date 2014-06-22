<?php
class ATC extends Model {
  private $arrs = array("prefs", "shifts");
  
  static function user() {
    return static::belongsTo('User');
  }
  
  function __get($key) {
    $val = parent::__get($key);
    if (in_array($key, $this->arrs)) {
      $val = unserialize($val);
    }
    return $val;
  }
  
  function __set($key, $val) {
    if (in_array($key, $this->arrs)) {
      $val = serialize($val);
    }
    parent::__set($key, $val);
  }
}
