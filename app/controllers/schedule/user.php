<?php
function _user() {
  if (!Auth::check()) {
    util::redirect('index');
  }
  $flights = Flight::orderBy('std')->all();
  foreach ($flights as $i => $flight) {
    if ($flight->user_id != Auth::user()->id) {
      unset($flights[$i]);
    }
  }
  $data = array("menu" => "sked",
                "flights" => $flights);
  View::auto($data);
}
