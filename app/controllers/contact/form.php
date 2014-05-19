<?php
function _form() {
  $data['menu'] = 'contact';
  $data['contact_address'] = "";
  $data['contact_subject'] = "";
  $data['contact_message'] = "";
  View::auto($data);
}