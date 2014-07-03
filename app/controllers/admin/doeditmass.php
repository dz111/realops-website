<?php
function _doeditmass() {
  $action = $_POST['action'];
  $fname = $_FILES["editfile"]["tmp_name"];
  $flights = read_flights($fname);
  if (strtoupper($action) == "SAVE") {
    mapflights($flights, "save_existing_flight");
  } elseif (strtoupper($action) == "CHECK") {
    mapflights($flights, "check_flight");
  } else {
    util::redirect(util::current_url());
  }
}

function save_existing_flight($flight) {
  $ok = fix_flight_times($flight);
  if (!$ok) return 'STA is earlier than STD and neither ADEP or ADES are YSSY';
  $fm = Flight::where('acid','=',$flight['acid'])->get();
  if (!$fm) {
    return "Flight doesn't exist";
  }
  $fm = $fm[0];
  foreach ($flight as $k => $v) {
    $fm->{$k} = $v;
  }
  $fm->save();
  if (!$fm->dirty()) {
    return '';
  } else {
    return 'Failed to save';
  }
}
