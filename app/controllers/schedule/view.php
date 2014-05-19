<?php

function _view($flightid) {
  $data['menu'] = 'sked';
  $data['body'][] = '<h1>' . $flightid . '</h1>';
  View::output("", $data);
}

?>