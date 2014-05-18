<?php
require('kissmvc_core.php');

//===============================================================
// Model/ORM
//===============================================================
class Model extends KISS_Model  {
}

//===============================================================
// Controller
//===============================================================
class Controller extends KISS_Controller {
  function request_not_found($err='') {
    $data['err'] = $err;
    header("HTTP/1.1 404 Not found");
    View::output("main/404.php", $data);
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
      $data['body'][] = View::do_fetch(APP_PATH . "views/" . $view, $data);
    }
    View::do_dump(APP_PATH . "views/layout.php", $data);
  }
}