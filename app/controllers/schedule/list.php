<?php
function _list($sort='acid', $order="ASC", $filter=false, $show=false) {
  // Validation
  $order = strtoupper($order);
  $filter = strtolower($filter);
  $show = strtolower($show);
  if ($order != "ASC" && $order != "DESC") $order = "ASC";
  // Query
  $qb = Flight::with('user')->orderBy($sort, $order);
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
  // Output
  $data['menu'] = 'sked';
  $data['flights'] = $flights;
  $data['sked_params'] = array("sort", "order", "filter", "show");
  View::auto($data);
}

?>