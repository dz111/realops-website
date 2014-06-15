<?php
function _doinsertmass() {
  $action = $_POST['action'];
  $fname = $_FILES["insertfile"]["tmp_name"];
  $flights = read_flights($fname);
  if (strtoupper($action) == "SAVE") {
    //
  } elseif (strtoupper($action) == "CHECK") {
    $results = array_map("check_flight", $flights);
    if (count($results) != count($flights)) {
      die("Error with check_flight function. ". count($flights) .
          " flights, " . count($results) . " results.");
    }
    $result_map = array_combine(array_column($flights, "acid"), $results);
    $data = array("results" => $result_map,
                  "flights" => count($flights),
                  "passes" => array_count_values($results)['']);
    View::output("admin/inputcheck", $data);
  } else {
    util::redirect(util::current_url());
  }
}
