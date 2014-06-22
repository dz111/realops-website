<?php
function _login($return_url=false) {
  if (!$return_url) $return_url = Route::link('index');
  if (Auth::check()) {
    util::redirect($return_url);
  }
  send_user_to_auth_provider($return_url);
}

function send_user_to_auth_provider($return_url) {
  $callback = get_callback_url();
  $auth = new sso(AUTH_PROVIDER, AUTH_KEY, AUTH_SECRET, "HMAC");
  $token = $auth->requestToken($callback, false, false);
  if ($token) {
    Auth::save_oauth_token($token);
    Auth::save_return_url($return_url);
    $auth->sendToVatsim();
  } else {
    util::server_error('(via OAuth) ' . $auth->error()['message']);
  }
}

function get_callback_url() {
  $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) ?
                'https' : 'http';
  $host = $_SERVER['HTTP_HOST'];
  $path = Route::link('auth/callback');
  //if (DEBUG) return 'oob';
  return $protocol . '://' . $host . $path;
}
