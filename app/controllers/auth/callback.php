<?php
function _callback($oauth_verifier=false, $oauth_token=false) {
  if (!$oauth_verifier) {
    util::server_error("(via OAuth) The authentication provider did not return a verification token");
  }
  if (!Auth::check_oauth_token()) {
    util::redirect(Route::link('index'));
  }
  $auth = new sso(AUTH_PROVIDER, AUTH_KEY, AUTH_SECRET, "HMAC");
  $token = Auth::get_oauth_token();
  Auth::clear_oauth_token();
  $user = $auth->checkLogin($token['token'], $token['secret'], $oauth_verifier);
  if ($user) {
    $data = array("id" => $user->user->id,
                  "name" => $user->user->name_first.' '.$user->user->name_last,
                  "email" => $user->user->email,
                  "rating" => $user->user->rating->id);
    Auth::do_login($data);
  } else {
    util::server_error("(via OAuth) An error occured during the authentication process");
  }
}

