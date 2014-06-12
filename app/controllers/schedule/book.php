<?php
require("common.php");

function _book($flightid) {
  if (!Auth::check()) util::redirect(util::current_url());
  $flight = get_flight($flightid);
  $result = do_book($flight, Auth::user());
  if ($result === 0) {
    $data['success'] = "Flight successfully booked!";
  } else {
    $data['error'] = $result;
  }
  $data['flight'] = get_flight($flightid);
  $data['menu'] = 'sked';
  View::output('schedule/view', $data);
}

function do_book($flight, $user) {
  if ($flight->user) {
    return "Flight already booked! No action taken...";
  }
  $flight->user_id = $user->id;
  $flight->save();
  return 0;
}
