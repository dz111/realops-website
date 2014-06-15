<?php
function _view($flightid) {
  $flight = $data['flight'] = get_flight($flightid);
  $data['menu'] = 'sked';
  View::auto($data);
}
