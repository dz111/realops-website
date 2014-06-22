<?php
function _form() {
  $data['menu'] = 'atc';
  if (Auth::check() && check_dupe()) View::output('atc/info', $data);
  else View::auto($data);
}
