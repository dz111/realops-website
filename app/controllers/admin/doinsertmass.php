<?php
function _doinsertmass() {
  $action = $_POST['action'];
  $fname = $_FILES["insertfile"]["tmp_name"];
  $flights = read_flights($fname);
  if (strtoupper($action) == "SAVE") {
    mapflights($flights, "save_flight");
  } elseif (strtoupper($action) == "CHECK") {
    mapflights($flights, "check_flight");
  } else {
    util::redirect(util::current_url());
  }
}

function mapflights($flights, $function) {
  $results = array_map($function, $flights);
  $result_map = array_combine(array_column($flights, "acid"), $results);
  $count = array_count_values($results);
  $npasses = isset($count['']) ? $count[''] : 0;
  $data = array("action" => $function,
                "results" => $result_map,
                "flights" => count($flights),
                "passes" => $npasses);
  View::output("admin/results", $data);
}
