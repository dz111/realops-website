<?php
function _list() {
  $flights = Flight::with('user')->all();
  $data['menu'] = 'sked';
  $data['flights'] = $flights;
  View::auto($data);
}

?>