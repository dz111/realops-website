<?php
// Load config
require("config.php");
require_once("array_column.php");  // Compatibility with PHP < 5.5

// Debug
if (DEBUG) {
  ini_set('display_errors','On');
  error_reporting(E_ALL);
} else {
  ini_set('display_errors','Off');
  error_reporting(E_ALL);
}

// Website options
$GLOBALS['sitename']='RealOps Sydney 2014 - VATSIM Australia Pacific';

// Includes
require('kissmvc.php');
require(APP_PATH . 'route.php');
require(APP_PATH . 'auth.php');

// Session
$cookie_path = WEB_FOLDER ? WEB_FOLDER : '/';
session_set_cookie_params(0, $cookie_path, $_SERVER['HTTP_HOST'], false, true);
session_start();

// Database
function getdbh() {
  if (!isset($GLOBALS['dbh']))
    try {
      $GLOBALS['dbh'] = new PDO('mysql:host=' . SQL_HOST . ';dbname=' .
                                SQL_DATABASE, SQL_USER, SQL_PASSWORD);
    } catch (PDOException $e) {
      die('Connection failed: '.$e->getMessage());
    }
  return $GLOBALS['dbh'];
}

// Autoload business classes
// Assumes Model Classes start with capital letters and helpers start
// with lower case letters
function __autoload($classname) {
  $a=$classname[0];
  if ($a >= 'A' && $a <='Z')
    require_once(APP_PATH . 'models/' . $classname . '.php');
  else
    require_once(APP_PATH . 'helpers/' . $classname . '.php');  
}

// Start controller
$controller = new Controller(APP_PATH.'controllers/',WEB_FOLDER);
