<?php
require('kissmvc_core.php');

//===============================================================
// Routing table
//===============================================================
class Route {
  static protected $table = array();
  
  static function match($verbs, $route, $controller, $name='') {
    $request_uri_parts = $route ? explode('/', $route) : array();
    if (!is_array($verbs)) {
      $verbs = array($verbs);
    }
    foreach ($verbs as $verb) {
      self::$table[] = array('verb' => strtoupper($verb),
                             'uri_parts' => $request_uri_parts,
                             'controller' => $controller,
                             'name' => $name,
                             'route' => $route);
    }
  }
  
  static function get($route, $controller, $name='') {
    Route::match('GET', $route, $controller, $name);
  }
  
  static function post($route, $controller, $name='') {
    Route::match('POST', $route, $controller, $name);
  }
  
  static function any($route, $controller, $name='') {
    Route::match('*', $route, $controller, $name);
  }
  
  static function do_route($uri, $verb) {
    $uri = $uri ? explode('?', $uri)[0] : '';
    $request_uri_parts = $uri ? explode('/', $uri) : array();
    $params = array();
    foreach (self::$table as $row) {
      if ($row['verb'] != $verb && $row['verb'] != '*') continue;
      if (count($row['uri_parts']) != count($request_uri_parts)) continue;
      for ($i = 0, $count = count($row['uri_parts']); $i < $count; ++$i) {
        $table_part = $row['uri_parts'][$i];
        $req_part = $request_uri_parts[$i];
        // Check if this is a parameter
        if (substr($table_part, 0, 1) == '{' &&
            substr($table_part, -1, 1) == '}') {
          $params[substr($table_part, 1, -1)] = urldecode($req_part);
          continue;
        }
        // Otherwise this part must match exactly
        if ($table_part != $req_part) continue 2;
      }
      // If we've made it this far, then it's time to return
      return array('uri' => $uri,
                   'verb' => $verb,
                   'params' => $params,
                   'controller' => $row['controller']);
    }
    // Didn't match - 404!
    return false;
  }
  
  static function link($name) {
    foreach (self::$table as $row) {
      if ($row['name'] == $name) {
        return WEB_FOLDER . $row['route'];
      }
    }
  }
}


//===============================================================
// Model/ORM
//===============================================================
define('MYSQL_TIME_FORMAT', 'Y-m-d H:i:s');
class Model extends KISS_Model  {
  protected $tablename = false;
  protected $createtime = false;
  protected $updatetime = false;
  protected $softdelete = false;
  
  protected $dbhfnname = 'getdbh';
  
  function __construct($id=false) {
    // Infer table name if not defined
    if ($this->tablename === false) {
      $this->tablename = strtolower(get_class($this)) . 's';
    }
    // Ask the database about the structure of this table
    $dbh = $this->getdbh();
    $sql = 'SHOW COLUMNS IN ' . $this->enquote($this->tablename);
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    while ($rs = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $name = $rs['Field'];
      $this->rs[$name] = '';
      if ($rs['Key'] == 'PRI') {
        $this->pkname = $name;
      }
    }
    // Retrieve a row if the primary key is given
    if ($id !== false) {
      $this->retrieve($id);
    }
  }
  
  function create() {
    if ($this->createtime) $this->rs['created_at'] = date(MYSQL_TIME_FORMAT);
    $id = $this->rs[$this->pkname];
    parent::create();
    if ($this->rs[$this->pkname] < 1) $this->rs[$this->pkname] = $id;
  }
  
  function update() {
    if ($this->updatetime) $this->rs['updated_at'] = date(MYSQL_TIME_FORMAT);
    parent::update();
  }
  
  function delete() {
    if ($this->softdelete) {
      $this->rs['deleted_at'] = date(MYSQL_TIME_FORMAT);
      parent::update();
    } else {
      $this->force_delete();
    }
  }
  
  function restore() {
    if ($this->softdelete) {
      $this->rs['deleted_at'] = 0;
      parent::update();
    }
  }
  
  function force_delete() {
    parent::delete();
    $this->rs[$this->pkname] = 0;
  }
  
  function exists($checkdb=true) {
    return (bool)parent::exists($checkdb);
  }
  
  function trashed($checkdb=true) {
    if (!$this->softdelete) return false;
    if (!$this->exists(false)) return false;
    if ($checkdb) {
      $dbh = $this->getdbh();
      $sql = "SELECT `deleted_at` FROM " . $this->enquote($this->tablename) . " WHERE " . $this->enquote($this->pkname) . "=?";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(1, $this->rs[$this->pkname]);
    }
    return (isset($this->rs['deleted_at']) && $this->rs['deleted_at'] > 0);
  }
}

//===============================================================
// Controller
//===============================================================
class Controller extends KISS_Controller {
  function __construct($controller_path, $web_folder) {
    $this->controller_path = $controller_path;
    $this->web_folder = $web_folder;
    $this->match_route_table()->add_cgi_params()->route_request();
  }
  
  function match_route_table() {
    $uri = $_SERVER['REQUEST_URI'];
    $verb = strtoupper($_SERVER['REQUEST_METHOD']);
    $match = Route::do_route($uri, $verb);
    if (!$match) $this->request_not_found();
    $this->params = $match['params'];
    // $match['action'] actually holds the controller name too
    $GLOBALS['controller'] = $match['controller'];
    $controller_parts = explode('/', $match['controller']);
    $this->controller = $controller_parts[0];
    $this->action = $controller_parts[1];
    return $this;
  }
  
  function add_cgi_params() {
    $this->params = array_merge($this->params, $_REQUEST);
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
  
  static function auto($data) {
    self::output($GLOBALS['controller'], $data);
  }
}