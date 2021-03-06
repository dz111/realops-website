<?php
function _doinsertmass() {
  $action = $_POST['action'];
  $fname = $_FILES["insertfile"]["tmp_name"];
  $flights = read_flights($fname);
  if (strtoupper($action) == "SAVE") {
    mapflights($flights, "save_new_flight");
  } elseif (strtoupper($action) == "CHECK") {
    mapflights($flights, "check_flight");
  } else {
    util::redirect(util::current_url());
  }
}

function save_new_flight($flight) {
  $ok = fix_flight_times($flight);
  if (!$ok) return 'STA is earlier than STD and neither ADEP or ADES are YSSY';
  $flight["user_id"] = null;
  $fm = new Flight($flight);
  $fm->save();
  if ($fm && $fm->exists()) {
    return '';
  } else {
    return 'Failed to save';
  }
}
