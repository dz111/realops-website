<h1>My Flights</h1>
<h2>5 July 2014</h2>
<table class="table table-hover" id="realops-flight-schedule">
  <thead>
    <tr>
      <th>Flight</th>
      <th>Type</th>
      <th>Origin</th>
      <th>STD<br />(UTC)</th>
      <th>Destination</th>
      <th>STA<br />(UTC)</th>
    </tr>
  </thead>
  <tbody>
<?php
foreach ($flights as $flight) { ?>
    <tr data-url="<?=Route::link('schedule/view', array('flightid' => $flight->id))?>">
      <td><a href="<?=Route::link('schedule/view', array('flightid' => $flight->id))?>"><?=$flight->acid?></a></td>
      <td><?=$flight->type?></td>
      <td><?=$flight->adep?></td>
      <td><?=date('H:i', strtotime($flight->std))?></td>
      <td><?=$flight->ades?></td>
      <td><?=date('H:i', strtotime($flight->sta))?></td>
    </tr>
<?php
} ?>
  </tbody>
</table>
