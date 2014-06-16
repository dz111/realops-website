<?php
if (!Auth::check() || !Auth::user()->admin) {
  util::redirect(Route::link('index'));
}

define('EVENTDATE', '2014-07-05');
$GLOBALS['flight_columns'] = array("acid", "type", "adep", "ades", "std", "sta", "route", "info");

function read_flights($fname) {
  global $flight_columns;
  $fh = fopen($fname, 'r');
  $flights = array();
  while ($data = fgetcsv($fh)) {
    while (count($data) != count($flight_columns)) {
      $data[] = '';  // Pad the array - something bad has happened :(
    }
    $flight = array_combine($flight_columns, $data);
    $flight["std"] = strtotime(EVENTDATE . ' ' . $flight["std"]);
    $flight["sta"] = strtotime(EVENTDATE . ' ' . $flight["sta"]);
    $flights[] = $flight;
  }
  fclose($fh);
  return $flights;
}

function check_flight($flight) {
  global $flight_columns;
  $result = '';
  foreach ($flight_columns as $column) {
    if (!isset($flight[$column])) {
      $result .= "'$column' column is missing. ";
    }
    if (!$flight[$column] && $column != "info") {
      $result .= "'$column' is empty. ";
    }
  }
  if ($flight["adep"] == $flight["ades"]) {
    $result .= "ADEP and ADES are the same. ";
  }
  if ($flight["adep"] != "YSSY" && $flight["ades"] != "YSSY") {
    $result .= "Neither ADEP or ADES are YSSY. ";
  }
  if ($flight["sta"] < $flight["std"]) {
    $result .= "STA is earlier than STD";
  }
  return $result;
}

function save_flight($flight) {
  if ($flight["sta"] < $flight["std"]) {
    if ($flight["adep"] == "YSSY") {
      $flight["sta"] += 24 * 60 * 60;  // Add 24 hours to sta
    } elseif ($flight["ades"] == "YSSY") {
      $flight["std"] -= 24 * 60 * 60;  // Subtract 24 hours from std
    } else {
      return 'STA is earlier than STD and neither ADEP or ADES are YSSY';
    }
  }
  $flight["std"] = date('Y-m-d H:i:s', $flight["std"]);
  $flight["sta"] = date('Y-m-d H:i:s', $flight["sta"]);
  $flight["user_id"] = null;
  $fm = new Flight($flight);
  $fm->save();
  if ($fm && $fm->exists()) {
    return '';
  } else {
    return 'Failed to save';
  }
}
