<?php

function _view($flightid) {
  $data['menu'] = 'sked';
  $data['flight_id'] = $flightid;
  View::auto($data);
}

?>