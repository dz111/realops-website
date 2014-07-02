<h1>Hourly analysis</h1>
<?php
foreach ($counts as $port => $count) { ?>
<h3><?=$port?></h3>
<table class="table">
  <thead>
    <tr>
      <th>Hour</th>
      <th>Total</th>
      <th>Departures</th>
      <th>Arrivals</th>
    </tr>
  </thead>
  <tbody>
<?php
  foreach ($count as $period => $data) { ?>
    <tr>
      <td><?=$period?></td>
      <td><?=$data['total']?></td>
      <td><?=isset($data['dep']) ? $data['dep'] : 0?></td>
      <td><?=isset($data['arr']) ? $data['arr'] : 0?></td>
    </tr>
<?php
  } ?>
  </tbody>
</table>
<?php
} ?>
