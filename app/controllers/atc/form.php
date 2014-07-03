<?php
$bookings_open = false;

function _form() {
  global $bookings_open;
  $data['menu'] = 'atc';
  if (Auth::check() && check_dupe() || !$bookings_open) {
    View::output('atc/info', $data);
  }
  else View::auto($data);
}
