<?php
$GLOBALS['sked_params'] = $sked_params;

function setparam($key, $val) {
  return setparams(array($key => $val));
}

function setparams($params) {
  global $sked_params;
  $i = false;
  $stub = "?";
  foreach ($params as $k => $v) {
    if ($v) {
      if ($i) $stub .= "&";
      else $i = true;
      $stub .= $k . "=" . $v;
    }
  }
  foreach ($_GET as $k => $v) {
    if (!array_key_exists($k, $params) && in_array($k, $sked_params)) {
      if ($i) $stub .= "&";
      else $i = true;
      $stub .= $k . "=" . $v;
    }
  }
  return htmlspecialchars($stub);
}

function setsort($key) {
  if (isset($_GET['sort']) && $_GET['sort'] == $key) {
    if (isset($_GET['order']) && strtoupper($_GET['order']) == "DESC") {
      return setparam("order", "ASC");
    } else {
      return setparam("order", "DESC");
    }
  } else {
    return setparams(array("sort" => $key,
                           "order" => "ASC"));
  }
}

function sort_direction($key) {
  if (isset($_GET['sort']) && $_GET['sort'] == $key) {
    $order = isset($_GET['order']) ? strtoupper($_GET['order']) : "ASC";
    if ($order == "ASC") return 1;
    elseif ($order == "DESC") return -1;
  }
  return 0;
}

function sortind($key) {
  $dir = sort_direction($key);
  if ($dir > 0) {
    return ' class="realops-sort-asc"';
  } elseif ($dir < 0) {
    return ' class="realops-sort-desc"';
  } else {
    return '';
  }
}
?>
<h1>Flight Schedule</h1>
<h2>5 July 2014</h2>
<p>Filter: <a href="<?=setparam("filter", "dep")?>">Departures</a> |
           <a href="<?=setparam("filter", "arr")?>">Arrivals</a> |
           <a href="<?=setparam("filter", false)?>">All</a>
<br />Show: <a href="<?=setparam("show", "available")?>">Available</a> |
            <a href="<?=setparam("show", false)?>">All</a></p>
<?php
if (isset($_GET['show']) && $_GET['show'] == "booked") { ?>
<p>Flights booked: <strong><?=count($flights)?></strong></p>
<?php
} ?>
<table class="table table-hover" id="realops-flight-schedule">
  <thead>
    <tr>
      <th<?=sortind("acid")?>><a href="<?=setsort("acid")?>">Flight</a></th>
      <th>Type</th>
      <th<?=sortind("adep")?>><a href="<?=setsort("adep")?>">Origin</a></th>
      <th<?=sortind("std")?>><a href="<?=setsort("std")?>">STD</a><br />(UTC)</th>
      <th<?=sortind("ades")?>><a href="<?=setsort("ades")?>">Destination</a></th>
      <th<?=sortind("sta")?>><a href="<?=setsort("sta")?>">STA</a><br />(UTC)</th>
      <th>Booked by</th>
    </tr>
  </thead>
  <tbody>
<?php
foreach ($flights as $flight) { ?>
    <tr data-url="<?=Route::link('schedule/view', array('flightid' => $flight->id))?>">
      <td><?=$flight->acid?></td>
      <td><?=$flight->type?></td>
      <td><?=$flight->adep?></td>
      <td><?=date('H:i', strtotime($flight->std))?></td>
      <td><?=$flight->ades?></td>
      <td><?=date('H:i', strtotime($flight->sta))?></td>
      <td><a href="<?=Route::link('schedule/view', array('flightid' => $flight->id))?>">
<?php
  if ($flight->user) { ?>
        <?=$flight->user->name?>
<?php
  } else { ?>
        <button type="button" class="btn btn-primary btn-xs">Book</button>
<?php
  } ?></a>
      </td>
    </tr>
<?php
} ?>
  </tbody>
</table>
