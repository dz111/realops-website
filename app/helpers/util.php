<?php
class util {
  static public function redirect($url) {
    header('HTTP/1.1 302 Found');
    header('Location: ' . $url);
    die();
  }
  
  static public function not_found($error='') {
    $data['error'] = $error;
    View::output("errors/not-found", $data);
    die();
  }
  
  static public function server_error($error='') {
    $data['error'] = $error;
    View::output("errors/server-error", $data);
    die();
  }
  
  static public function service_unavailable() {
    View::output("errors/service-unavailable");
    die();
  }
  
  static public function current_url() {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) ?
                  'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = $_SERVER['REQUEST_URI'];
    $qs = $_SERVER['QUERY_STRING'];
    $url = $protocol . '://' . $host . $path;
    if ($qs) $url .= '?' . $qs;
    return $url;
  }
  
  static public function current_url_enc() {
    return urlencode(static::current_url());
  }
}