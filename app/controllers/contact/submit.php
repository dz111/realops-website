<?php
function _submit($contact_addressee, $contact_subject, $contact_message) {
  $data['menu'] = 'contact';
  $data['result'] = true;
  $data['contact_addressee'] = $contact_addressee;
  $data['contact_subject'] = $contact_subject;
  $data['contact_message'] = $contact_message;
  View::output("contact/form", $data);
}