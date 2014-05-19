<?php
require('kissmvc_core.php');

//===============================================================
// Routing table
//===============================================================
class Route {
  static protected $table = array();
  
  static function match($verbs, $route, $controller, $action=false) {
    $request_uri_parts = $route ? explode('/', $route) : array();
    if (!is_array($verbs)) {
      $verbs = array($verbs);
    }
    if (!$action) {
      $action = "_" . $controller;
    }
    foreach ($verbs as $verb) {
      self::$table[] = array('verb' => strtoupper($verb),
                             'uri_parts' => $request_uri_parts,
                             'controller' => $controller,
                             'action' => $action);
    }
  }
  
  static function get($route, $controller, $action=false) {
    Route::match('GET', $route, $controller, $action);
  }
  
  static function post($route, $controller, $action=false) {
    Route::match('POST', $route, $controller, $action);
  }
  
  static function any($route, $controller, $action=false) {
    Route::match('*', $route, $controller, $action);
  }
  
  static function do_route($uri, $verb) {
    $request_uri_parts = $uri ? explode('/', $uri) : array();
    $params = array();
    foreach (self::$table as $row) {
      if (!$row['verb'] == $verb) continue;
      if (count($row['uri_parts']) != count($request_uri_parts)) continue;
      for ($i = 0, $count = count($row['uri_parts']); $i < $count; ++$i) {
        $table_part = $row['uri_parts'][$i];
        $req_part = $request_uri_parts[$i];
        // Check if this is a parameter
        if (substr($table_part, 0, 1) == '{' &&
            substr($table_part, -1, 1) == '}') {
          $params[substr($table_part, 1, -1)] = $req_part;
          continue;
        }
        // Otherwise this part must match exactly
        if ($table_part != $req_part) continue 2;
      }
      // If we've made it this far, then it's time to return
      return array('uri' => $uri,
                   'verb' => $verb,
                   'params' => $params,
                   'controller' => $row['controller'],
                   'action' => $row['action']);
    }
    // Didn't match - 404!
    return false;
  }
}


//===============================================================
// Model/ORM
//===============================================================
class Model extends KISS_Model  {
}

//===============================================================
// Controller
//===============================================================
class Controller extends KISS_Controller {
  function __construct($controller_path, $web_folder) {
    $this->controller_path = $controller_path;
    $this->web_folder = $web_folder;
    $this->match_route_table()->route_request();
  }
  
  function match_route_table() {
    $uri = $_SERVER['REQUEST_URI'];
    $verb = strtoupper($_SERVER['REQUEST_METHOD']);
    $match = Route::do_route($uri, $verb);
    if (!$match) $this->request_not_found();
    $this->params = $match['params'];
    $this->controller = $match['controller'];
    $this->action = $match['action'];
    return $this;
  }
  
  function request_not_found($err='') {
    $data['err'] = $err;
    header("HTTP/1.1 404 Not found");
    View::output("main/404", $data);
    die();
  }
}

//===============================================================
// View
//===============================================================
class View extends KISS_View {
  static function output($view, $data) {
    $data['view'] = $view;
    if ($view) {
      $data['body'][] = View::do_fetch(APP_PATH . "views/" . $view . '.php', $data);
    }
    View::do_dump(APP_PATH . "views/layout.php", $data);
  }
}