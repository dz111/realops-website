<?php
function _list() {
  $data['menu'] = 'sked';
  $data['body'][] = "<h1>Schedule</h1><p>This is where the schedule goes...</p>";
  View::output("", $data);
}

?>