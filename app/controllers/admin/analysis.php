<?php
function _analysis() {
  $data = array("counts" => array("Sydney" => countairport('YSSY'),
                                  "Brisbane" => countairport('YBBN'),
                                  "Melbourne" => countairport('YMML')));
  View::auto($data);
}

function countairport($port) {
  $deps = Flight::where('user_id', 'IS NOT NULL', '')->where('adep', '=', $port)->all();
  $arrs = Flight::where('user_id', 'IS NOT NULL', '')->where('ades', '=', $port)->all();
  $depcount = countflights($deps, 'std');
  $arrcount = countflights($arrs, 'sta');
  $count = pivot(array('dep' => $depcount,
                       'arr' => $arrcount));
  ksort($count);
  $count['Total'] = column_sum($count);
  return $count;
}

function countflights($flights, $column) {
  $output = array();
  foreach ($flights as $flight) {
    $hour = date('H', strtotime($flight->{$column})) . ":00";
    if (isset($output[$hour])) {
      $output[$hour]++;
    } else {
      $output[$hour] = 1;
    }
  }
  return $output;
}

function pivot($tables) {
  $pivot = array();
  foreach ($tables as $name => $table) {
    foreach ($table as $k => $v) {
      $pivot[$k][$name] = $v;
    }
  }
  foreach ($pivot as $key => $row) {
    $pivot[$key]['total'] = array_sum($row);
  }
  return $pivot;
}

function column_sum($arr) {
  $out = array();
  foreach ($arr as $row) {
    foreach ($row as $col => $v) {
      if (isset($out[$col])) {
        $out[$col] += $v;
      } else {
        $out[$col] = $v;
      }
    }
  }
  return $out;
}
