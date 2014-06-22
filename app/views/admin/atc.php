<p>Registrations: <strong><?=count($atc)?></strong></p>
<table class="table">
  <thead>
    <tr>
      <th>CID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Rating</th>
      <th>Prefs</th>
      <th>Shifts</th>
      <th>Comments</th>
    </tr>
  </thead>
  <tbody>
<?php
foreach ($atc as $n) { ?>
    <tr>
      <td><?=$n->user->id?></td>
      <td><?=$n->user->name?></td>
      <td><a href="mailto:<?=$n->user->email?>"><?=$n->user->email?></a></td>
      <td><?=atcstuff::$ratings[$n->user->rating]?></td>
      <td>
<?php
  foreach ($n->prefs as $pref) {
    echo atcstuff::$positions[$pref] . ", ";
  } ?>
      </td>
      <td>
<?php
  foreach ($n->shifts as $shift) {
    echo atcstuff::$shifts[$shift] . ", ";
  } ?>
      </td>
      <td><?=htmlspecialchars($n->comments)?></td>
    </tr>
<?php
} ?>
  </tbody>
</table>
