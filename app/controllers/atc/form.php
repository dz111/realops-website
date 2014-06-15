<?php
function _form() {
  $user = new User(1027224);
  $data['menu'] = 'atc';
  $data['user'] = $user;
  View::auto($data);
}
