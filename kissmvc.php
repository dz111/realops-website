<?php
require('kissmvc_core.php');

//===============================================================
// Routing table
//===============================================================
final class Route {
  static protected $table = array();
  
  static function match($verbs, $route, $controller, $name='', $maint=false) {
    $request_uri_parts = $route ? explode('/', $route) : array();
    if (!is_array($verbs)) {
      $verbs = array($verbs);
    }
    foreach ($verbs as $verb) {
      static::$table[] = array('verb' => strtoupper($verb),
                             'uri_parts' => $request_uri_parts,
                             'controller' => $controller,
                             'name' => $name,
                             'route' => $route,
                             'maint' => $maint);
    }
  }
  
  static function get($route, $controller, $name='', $maint=false) {
    Route::match('GET', $route, $controller, $name, $maint);
  }
  
  static function post($route, $controller, $name='', $maint=false) {
    Route::match('POST', $route, $controller, $name, $maint);
  }
  
  static function put($route, $controller, $name='', $maint=false) {
    Route::match('PUT', $route, $controller, $name, $maint);
  }
  
  static function delete($route, $controller, $name='', $maint=false) {
    Route::match('DELETE', $route, $controller, $name, $maint);
  }
  
  static function any($route, $controller, $name='', $maint=false) {
    Route::match('*', $route, $controller, $name, $maint);
  }
  
  static function do_route($uri, $verb) {
    $uri = $uri ? explode('?', $uri)[0] : '';
    $request_uri_parts = $uri ? explode('/', $uri) : array();
    $params = array();
    foreach (static::$table as $row) { 
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
                   'controller' => $row['controller'],
                   'maint' => $row['maint']);
    }
    // Didn't match - 404!
    return false;
  }
  
  static function link($name, $params=array()) {
    foreach (static::$table as $row) {
      if ($row['name'] == $name) {
        $uri_parts = explode('/', $row['route']);
        foreach ($uri_parts as &$part) {
          if (substr($part, 0, 1) == '{' &&
              substr($part, -1, 1) == '}') {
            $param_name = substr($part, 1, -1);
            $part = $params[$param_name];
          }
        }
        return htmlspecialchars(WEB_FOLDER . implode('/', $uri_parts));
      }
    }
  }
}


//===============================================================
// Model/ORM
//===============================================================
abstract class Model {
  // Override these variables as appropriate
  protected $tablename = false;
  protected $createtime = false;
  protected $updatetime = false;
  protected $softdelete = false;
  protected $pkname = false;
  protected $dbhfnname = 'getdbh';
  protected $DB_TYPE = 'MYSQL';
  protected $COMPRESS_ARRAY = true;
  protected $query_builder = "QueryBuilder";
  
  protected $rs = array();
  protected $foreign = array();
  protected $exists = false;
  protected $dirty = array();
  
  /**
   * 'Model' class
   * Provides an object-relational model interface
   * __construct(mixed $arg)
   * @param  arg(array)  parameters to place into a new Model
   * @param  arg         index of Model to be loaded
   * @param  exists      true if is known that the Model exists
   */
  public function __construct($arg='', $exists=false) {
    // Infer table name if not defined
    if (!$this->tablename) {
      $this->tablename = strtolower(get_class($this)) . 's';
    }
    // Discover the table schema
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
    if (isset($arg) && $arg) {
      if (is_array($arg)) {
        // Set up a new Model if given array
        $this->load_from_array($arg, true);
      } else {
        // Retrieve row for given primary key
        $this->select_row($arg);
      }
    }
    if ($exists) {
      $this->exists = true;
      $this->dirty = array();
    }
  }
  
  /**
   * Getter
   * @param  key  name of parameter
   * @return  value of parameter or null
   */
  public function __get($key) {
    if (array_key_exists($key, $this->rs)) {
      return $this->rs[$key];
    } elseif (array_key_exists($key, $this->foreign)) {
      return $this->foreign[$key];
    } else {
      return null;
    }
  }
  
  /**
   * Setter
   * @param  key  name of parameter
   * @param  val  value of parameter
   * @return  this instance
   */
  public function __set($key, $val) {
    //if (isset($this->rs[$key])) {
    if (array_key_exists($key, $this->rs)) {
      $this->rs[$key] = $val;
      $this->dirty[] = $key;
    }
    return $this;
  }
  
  /**
   * Stringifier
   * @return  dump of internal array
   */
  public function __toString() {
    ob_start();
    echo get_class($this) . '(Model) - ';
    var_dump($this->rs);
    return ob_get_clean();
  }
  
  /**
   * Creates a new Model based on provided data and saves to database
   * @param  arr  associative array with Model data
   * @return  new Model instance with data
   */
  static public function create(array $arr) {
    $model = new static();
    $model->load_from_array($arr);
    return $model->save();
  }
  
  /**
   * Find a model by index
   * @param  id  index of the model to be retrieved
   * @return  new Model instance with data
   */
  static public function find($id) {
    return new static($id);
  }

  /**
   * Magic function to pass unknown static calls to the query builder
   */
  static public function __callStatic($method, $args) {
    $static = new static();
    $qb = new $static->query_builder(get_called_class());
    return call_user_func_array(array($qb, $method), $args);
  }
  
  /**
   * Write Model data to database
   * @param  arr  (optional) associative array with data to be written
   * @return  this instance
   */
  public function save(array $arr = array()) {
    if (empty($arr)) $arr = $this->rs;
    if ($this->exists) {
      if ($this->updatetime) {
        $this->rs['updated_at'] = $this->makedatetime();
      }
      return $this->update_row();
    } else {
      if ($this->createtime) {
        $this->rs['created_at'] = $this->makedatetime();
      }
      return $this->insert_row();
    }
  }
  
  /**
   * Delete Model
   * If $this->softdelete == true, this function sets the Model to soft-deleted
   * @return  true on success
   */
  public function delete() {
    if ($this->softdelete) {
      $this->rs['deleted_at'] = $this->makedatetime();
      $this->save();
    } else {
      $this->force_delete();
    }
  }
  
  /**
   * Force delete Model
   * Overrides $this->softdelete
   * @return  true on success
   */
  public function force_delete() {
    if ($this->delete_row()) {
      $this->exists = false;
      return true;
    }
    return false;
  }
  
  /**
   * Restore a soft-deleted Model
   * @return  this instance
   */
  public function restore() {
    if ($this->softdelete && $this->trashed()) {
      $this->rs['deleted_at'] = '';
      $this->save();
    }
    return $this;
  }
  
  /**
   * Checks whether the Model is in the database
   * @return  true if the Model is in the database
   */
  public function exists() {
    return $this->exists;
  }
  
  /**
   * Checks whether the Model has changed from the database state
   * @return  true if the Model has been changed
   */
  public function dirty() {
    return !empty($this->dirty);
  }
  
  /**
   * Checks whether Model has been soft-deleted
   * @return  true if the Model has been soft-deleted
   */
  public function trashed() {
    return ($this->softdelete && $this->rs['deleted_at']);
  }
  
  static public function pkname() {
    $static = new static();
    return $static->pkname;
  }
  
  protected function getdbh() {
    return call_user_func($this->dbhfnname);
  }
  
  protected function enquote($name) {
    if ($this->DB_TYPE == 'MYSQL') {
      return '`' . $name . '`';
    } else {
      // Put future databases here
      return '"' . $name . '"';
    }
  }
  
  protected function makedatetime() {
    if ($this->DB_TYPE == 'future') {
      // Put future databases here
      return 'future';
    } else {
      // MySQL format
      return date('Y-m-d H:i:s');
    }
  }
  
  protected function load_from_array($arr, $verbatim=false) {
    foreach ($arr as $k => $v) {
      if ($verbatim) {
        $this->rs[$k] = $v;
      } else {
        $this->{$k} = $v;
      }
    }
    return $this;
  }
  
  static public function execute_query($query) {
    $static = new static();
    $dbh = $static->getdbh();
    // Build query string
    $sql = 'SELECT * FROM ' . $static->enquote($static->tablename);
    // Apply where conditions
    if (count($query->conditions)) {
      $sql .= ' WHERE ';
      $first = true;
      foreach ($query->conditions as $condition) {
        if ($first) {
          $first = false;
        } else {
          $sql .= ' ' . $condition['and_or'] . ' ';
        }
        $sql .= $static->enquote($condition['column']) . ' ' .
                $condition['operator'];
        if (is_array($condition['value'])) {
          $s = str_repeat(', ?', count($condition['value']));
          $s = substr($s, 2);  // Remove leading comma and space
          $sql .= ' (' . $s . ')';
        } elseif ($condition['value']) {
          $sql .= ' ?';
        }
      }
    }
    // Apply order by
    if (count($query->order)) {
      $sql .= ' ORDER BY';
      $s = '';
      foreach ($query->order as $order) {
        $s .= ', ' . $static->enquote($order['column']) . ' ' . $order['dir'];
      }
      $sql .= substr($s, 1); // Remove leading comma
    }
    // Apply limit on result length
    if ($query->limit) {
      $sql .= ' LIMIT ' . $query->limit;
    }
    // Prepare statement and bind values
    $stmt = $dbh->prepare($sql);
    if (count($query->conditions)) {
      $i = 0;
      foreach ($query->conditions as $condition) {
        $v = $condition['value'];
        if (!$v) continue;
        if (is_array($v)) {
          foreach ($v as $value) {
            $stmt->bindValue(++$i, $value);
          }
        } else {
          $stmt->bindValue(++$i, $v);
        }
      }
    }
    // Get results
    $stmt->execute();
    $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results = array();
    foreach ($rs as $row) {
      $results[] = new static($row, true);
    }
    // Get relationships
    /**
     * NOTE: This only implements belongsTo()
     */
    foreach ($query->foreign as $relationship) {
      $model = $relationship['foreign_model'];
      $local_index = $relationship['local_index'];
      $foreign_pkname = $model::pkname();
      $foreign = $model::where($foreign_pkname , 'IN',
                               model_column($results, $local_index));
      foreach ($relationship['child'] as $child) {
        $foreign->with($child);
      }
      $foreign = $foreign->all();
      foreach ($results as $rrow) {
        foreach ($foreign as $frow) {
          if ($frow->$foreign_pkname  == $rrow->{$local_index}) {
            $rrow->add_foreign($relationship['name'], $frow);
            continue 2;
          }
        }
        $rrow->add_foreign($relationship['name'], null);
      }
    }
    // Return
    return $results;
  }
  
  protected function select_row($id) {
    $dbh = $this->getdbh();
    $sql = 'SELECT * FROM ' . $this->enquote($this->tablename) . ' WHERE ' .
           $this->enquote($this->pkname) . '=?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, (int)$id);
    $stmt->execute();
    $rs = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($rs) {
      foreach ($rs as $key => $val) {
        if (isset($this->rs[$key])) {
          $this->rs[$key] = $val;
        }
      }
      $this->exists = true;
      $this->dirty = array();
    }
    return $this;
  }
  
  protected function insert_row() {
    $dbh = $this->getdbh();
    $pkname = $this->pkname;
    $s1 = $s2 = '';
    foreach ($this->rs as $k => $v) {
      if ($k != $pkname || $v) {
        $s1 .= ',' . $this->enquote($k);
        $s2 .= ',?';
      }
    }
    $s1 = substr($s1, 1); // Remove leading comma
    $s2 = substr($s2, 1); // Remove leading comma
    $sql = 'INSERT INTO ' . $this->enquote($this->tablename) . ' (' . $s1 .
           ') VALUES (' . $s2 . ')';
    $stmt = $dbh->prepare($sql);
    $i = 0;
    foreach ($this->rs as $k => $v) {
      if ($k != $pkname || $v) {
        $stmt->bindValue(++$i, $v);
      }
    }
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
      return false;
    }
    $id = $this->rs[$pkname] ? $this->rs[$pkname] : $dbh->lastInsertId();
    $this->select_row($id);
    return $this;
  }
  
  protected function update_row() {
    $dbh = $this->getdbh();
    $s = '';
    foreach ($this->rs as $k => $v) {
      $s .= ',' . $this->enquote($k) . '=?';
    }
    $s = substr($s, 1); // Remove leading comma
    $sql = 'UPDATE ' . $this->enquote($this->tablename) . ' SET ' . $s . 
           ' WHERE ' . $this->enquote($this->pkname) . '=?';
    $stmt = $dbh->prepare($sql);
    $i = 0;
    foreach ($this->rs as $k => $v) {
      $stmt->bindValue(++$i, $v);
    }
    $stmt->bindValue(++$i, $this->rs[$this->pkname]);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $this->exists = true;
      $this->dirty = array();
    }
    return $this;
  }
  
  protected function delete_row() {
    $dbh = $this->getdbh();
    $sql = 'DELETE FROM ' . $this->enquote($this->tablename) . ' WHERE ' .
           $this->enquote($this->pkname) . '=?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1,$this->rs[$this->pkname]);
    return $stmt->execute();
  }
  
  protected function add_foreign($name, $foreign) {
    $this->foreign[$name] = $foreign;
  }
  
  static protected function belongsTo($model) {
    return array("type" => "belongsTo",
                 "foreign_model" => $model,
                 "local_index" => strtolower($model) . '_id');
  }
}

class QueryBuilder {
  public $model;
  public $conditions = array();
  public $order = array();
  public $foreign = array();
  public $limit = false;
  
  function __construct($model) {
    $this->model = $model;
  }
  
  function where($column, $operator, $value=false, $and_or="AND") {
    $this->conditions[] = compact("column", "operator", "value", "and_or");
    return $this;
  }
  
  function orWhere($column, $operator, $value=false) {
    return $this->where($column, $operator, $value, "OR");
  }
  
  function orderBy($column, $dir="ASC") {
    $this->order[] = compact("column", "dir");
    return $this;
  }
  
  function orderByDesc($column) {
    return $this->orderBy($column, "DESC");
  }
  
  function orderByAsc($column) {
    return $this->orderBy($column, "ASC");
  }
  
  function with($name) {
    $parts = explode('.', $name);
    if (count($parts) == 1) {
      $relationship = call_user_func(array($this->model, $name));
      $relationship['name'] = $name;
      $relationship['child'] = array();
      $this->foreign[] = $relationship;
    } elseif (count($parts) > 1) {
      $parent = $parts[0];
      $child = implode('.', array_slice($parts, 1));
      foreach ($this->foreign as &$foreign) {
        if ($foreign['name'] == $parent) {
          $foreign['child'][] = $child;
        }
      }
    }
    return $this;
  }
  
  function all() {
    $this->limit = false;
    return static::execute_query($this);
  }
  
  function get($number=1) {
    $this->limit = $number;
    return static::execute_query($this);
  }
  
  static protected function execute_query($query) {
    $model = $query->model;
    return $model::execute_query($query);
  }
}

function model_column($arr, $column) {
  $results = array();
  foreach ($arr as $model) {
    $results[] = $model->$column;
  }
  return $results;
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
    $uri = $this->get_req_uri();
    if ($uri === false) $this->request_not_found("Outside application path");
    $verb = isset($_POST['_method']) ? strtoupper($_POST['_method']) :
              strtoupper($_SERVER['REQUEST_METHOD']);
    $match = Route::do_route($uri, $verb);
    if (!$match) $this->request_not_found($this->make_error_message($verb));
    if (MAINT_MODE && !$match['maint'] &&
         (!Auth::check() || !Auth::user()->admin)) {
      util::service_unavailable();
    }
    $this->params = $match['params'];
    $GLOBALS['controller'] = $match['controller'];
    $controller_parts = explode('/', $match['controller']);
    $this->controller = $controller_parts[0];
    $this->action = $controller_parts[1];
    return $this;
  }
  
  function add_cgi_params() {
    $this->params = array_merge($this->params, $_GET);
    return $this;
  }
  
  function route_request() {
    $this->load_controller();
    $this->load_common();
    $this->call_action();
    return $this;
  }
  
  function get_req_uri() {
    $uri = $_SERVER['REQUEST_URI'];
    if ($this->web_folder) {
      if (strpos($uri, $this->web_folder) === 0) {
        // Remove application root path
        $uri = substr($uri, strlen($this->web_folder));
      } else {
        return false;
      }
    }
    if (!$uri) {
      $uri = '/';  // Put root slash if string is empty
    }
    return $uri;
  }
  
  function load_controller() {
    $controllerfile = $this->controller_path . $this->controller . '/' .
                      $this->action . '.php';
    if (!preg_match('#^[A-Za-z0-9_-]+$#', $this->controller) ||
          !file_exists($controllerfile)) {
      $this->request_not_found('Controller file not found' . $controllerfile);
    }
    require($controllerfile);
  }
  
  function load_common() {
    $commonfile = $this->controller_path . $this->controller . '/common.php';
    if (file_exists($commonfile)) {
      require($commonfile);
    }
  }
  
  function call_action() {
    $function = '_' . $this->action;
    if (!preg_match('#^[A-Za-z_][A-Za-z0-9_-]*$#', $function) ||
          !function_exists($function)) {
      $this->request_not_found('Function not found: '.$function);
    }
    $reflect = new ReflectionFunction($function);
    $params = array();
    foreach ($reflect->getParameters() as $param) {
      if (isset($this->params[$param->getName()])) {
        $params[] = $this->params[$param->getName()];
      } elseif ($param->isDefaultValueAvailable()) {
        $params[] = $param->getDefaultValue();
      } else {
        $params[] = null;
      }
    }
    call_user_func_array($function, $params);
  }
  
  function make_error_message($verb) {
    $msg = 'No route found for ' . $this->get_req_uri();
    if ($this->web_folder) {
      $msg .= ' with root folder "' . $this->web_folder . '"';
    }
    $msg .= ' using ' . $verb . ' method.';
    return $msg;
  }
  
  function request_not_found($msg='') {
    util::not_found($msg);
  }
}

//===============================================================
// View
//===============================================================
class View extends KISS_View {
  static function output($view, $data=array()) {
    $data['view'] = $view;
    if ($view) {
      $data['body'][] = View::do_fetch(APP_PATH . "views/" . $view . '.php', $data);
    }
    View::do_dump(APP_PATH . "views/layout.php", $data);
  }
  
  static function auto($data=array()) {
    static::output($GLOBALS['controller'], $data);
  }
}