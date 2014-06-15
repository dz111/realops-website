<?php
function get_flight($flightid) {
  $flight = Flight::with("user")->where("id", "=", $flightid)->get();
  if (empty($flight)) util::redirect(Route::link('schedule'));
  return $flight[0];
}
