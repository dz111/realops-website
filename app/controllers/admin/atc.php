<?php
function _atc() {
  $data['atc'] = ATC::with('user')->all();
  View::auto($data);
}