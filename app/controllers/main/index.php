<?php
function _index() {
  $data['body'][] = "Hi";
  View::output('', $data);
}