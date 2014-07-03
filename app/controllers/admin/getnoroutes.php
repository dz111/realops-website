<?php
function _getnoroutes() {
  global $flight_columns;
  $flights = get_affected_flights();
  header("Content-type: text/csv");
  header("Content-disposition: attachment;filename=noroutes.csv");
  foreach ($flights as $flight) {
    echo flight_to_csv($flight, $flight_columns) . "\n";
  }
}

function get_affected_flights() {
  return Flight::where('route', "=''", '')->all();
}

function flight_to_csv($flight, $columns) {
  $first = true;
  $line = '';
  foreach ($columns as $column) {
    if ($first) $first = false;
    else $line .= ',';
    $v = $flight->{$column};
    if ($column == "std" || $column == "sta") {
      $v = date('H:i:s', strtotime($v));
    }
    $line .= $v;
  }
  return $line;
}
