<?php
function _logout($return_url) {
  Auth::do_logout();
  util::redirect($return_url);
}