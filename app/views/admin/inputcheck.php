<h1>Input check</h1>
<p><strong>Result: <?=$passes?> / <?=$flights?></strong></p>
<table class="table table-condensed">
  <thead>
    <tr>
      <th>ACID</th>
      <th>Result</th>
    </tr>
  </thead>
  <tbody>
<?php
foreach ($results as $acid => $result) { ?>
    <tr class="<?=$result ? "warning" : "success" ?>">
      <td><?=$acid?></td>
      <td><?=$result ? $result : 'Pass'?></td>
    </tr>
<?php
} ?>
  </tbody>
</table>
<p><a href="<?=util::current_url()?>" class="btn btn-success">Return</a></p>
