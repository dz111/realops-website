<?php
function _list($sort='', $order="ASC", $filter=false, $show=false) {
  // Validation
  $order = strtoupper($order);
  $filter = strtolower($filter);
  $show = strtolower($show);
  if ($order != "ASC" && $order != "DESC") $order = "ASC";
  // Query
  $qb = Flight::with('user');
  if ($sort) {
    $qb->orderBy($sort, $order);
  }
  if ($filter == 'dep') {
    $qb->where('adep', '=', 'YSSY');
  } elseif ($filter == 'arr') {
    $qb->where('ades', '=', 'YSSY');
  }
  if ($show == 'booked') {
    $qb->where('user_id', 'IS NOT NULL', '');
  } elseif ($show == 'available') {
    $qb->where('user_id', 'IS NULL', '');
  }
  $flights = $qb->all();
  if (!$sort) {
    usort($flights, "runway_order");
  }
  // Output
  $data['menu'] = 'sked';
  $data['flights'] = $flights;
  $data['sked_params'] = array("sort", "order", "filter", "show");
  View::auto($data);
}

function runway_order($a, $b) {
  $ta = runway_time($a);
  $tb = runway_time($b);
  if ($ta == $tb) return 0;
  return ($ta < $tb) ? -1 : 1;
}

function runway_time($flight) {
  if ($flight->ades == "YSSY") {
    return strtotime($flight->sta);
  } else {
    return strtotime($flight->std);
  }
}

?>