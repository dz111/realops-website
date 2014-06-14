<?php
function _unbook($flightid) {
  if (!Auth::check()) util::redirect(util::current_url());
  $flight = get_flight($flightid);
  $result = do_book($flight, Auth::user());
  if ($result === 0) {
    $data['success'] = "Flight unbooked!";
  } else {
    $data['error'] = $result;
  }
  $data['flight'] = get_flight($flightid);
  $data['menu'] = 'sked';
  View::output('schedule/view', $data);
}

function do_book($flight, $user) {
  if (!$flight->user || $flight->user->id != $user->id) {
    return "Cannot remove unbooked flight! No action taken...";
  }
  $flight->user_id = NULL;
  $flight->save();
  return 0;
}
