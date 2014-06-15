<?php
final class Auth {
  static private $user;
  
  static public function check() {
    if (!isset(static::$user)) static::init_user();
    return (bool)static::$user;
  }
  
  static public function user() {
    if (!isset(static::$user)) static::init_user();
    return static::$user;
  }
  
  static public function init_auth_array() {
    if (!isset($_SESSION['auth'])) {
      $_SESSION['auth'] = array();
    }
  }
  
  static public function save_oauth_token($token) {
    static::init_auth_array();
    $_SESSION['auth']['req_token'] = $token->token->oauth_token;
    $_SESSION['auth']['req_token_secret'] = $token->token->oauth_token_secret;
  }
  
  static public function check_oauth_token() {
    static::init_auth_array();
    return (isset($_SESSION['auth']['req_token']) &&
      isset($_SESSION['auth']['req_token_secret']));
  }
  
  static public function get_oauth_token() {
    static::init_auth_array();
    if (!static::check_oauth_token()) {
      return false;
    }
    $token = array("token" => $_SESSION['auth']['req_token'],
                   "secret" => $_SESSION['auth']['req_token_secret']);
    return $token;
  }
  
  static public function clear_oauth_token() {
    static::init_auth_array();
    if (!static::check_oauth_token()) {
      return;
    }
    unset($_SESSION['auth']['req_token']);
    unset($_SESSION['auth']['req_token_secret']);
  }
  
  static public function save_return_url($return_url) {
    static::init_auth_array();
    $_SESSION['auth']['return_url'] = $return_url;
  }
  
  static public function getclear_return_url() {
    static::init_auth_array();
    if (isset($_SESSION['auth']['return_url'])) {
      $return_url = $_SESSION['auth']['return_url'];
      unset($_SESSION['auth']['return_url']);
      return $return_url;
    } else {
      return Route::link('index');
    }
  }
  
  static public function do_login($data) {
    static::init_auth_array();
    $user = User::find($data['id']);
    if (!$user->exists()) {
      $user = User::create($data);
      $user->save();
    }
    $_SESSION['auth']['id'] = $user->id;
    util::redirect(static::getclear_return_url());
  }
  
  static public function do_logout() {
    unset($_SESSION['auth']);
  }
  
  static private function init_user() {
    if (isset($_SESSION['auth']) && isset($_SESSION['auth']['id'])) {
      static::$user = User::find($_SESSION['auth']['id']);
      if (!static::$user->exists()) {
        unset($_SESSION['auth']['id']);
        static::$user = false;
      }
    } else {
      static::$user = false;
    }
  }
}
