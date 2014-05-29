<table class="table table-hover" id="realops-flight-schedule">
  <thead>
    <tr>
      <th>Flight</th>
      <th>Type</th>
      <th>Origin</th>
      <th>STD</th>
      <th>Destination</th>
      <th>STA</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
<?php
foreach ($flights as $flight) { ?>
    <tr>
      <td><?=$flight->acid?></td>
      <td><?=$flight->type?></td>
      <td><?=$flight->adep?></td>
      <td><?=date('H:i', strtotime($flight->std))?></td>
      <td><?=$flight->ades?></td>
      <td><?=date('H:i', strtotime($flight->sta))?></td>
      <td>Available<br/>Book</td>
    </tr>
<?php
} ?>
  </tbody>
</table>
