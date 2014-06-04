<?php
class Flight extends Model {
  static function user() {
    return static::belongsTo('User');
  }
}
