<?php
function _index() {
  $data['menu'] = 'index';
  View::output('main/index.php', $data);
}