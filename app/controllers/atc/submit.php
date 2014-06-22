<?php
function _submit() {
  if (!Auth::check()) util::redirect(util::current_url());
  if (check_dupe()) util::redirect(util::current_url());
  $data = array();
  $data['menu'] = 'atc';
  $data['error'] = validation();
  if (!$data['error']) $data['error'] = do_register();
  if ($data['error']) {
    View::output('atc/form', $data);
  } else {
    $data['success'] = "Registration successful &ndash; ATC Roster will be available here closer to the event";
    View::output('atc/info', $data);
  }
}

function validation() {
  $req_fields = array("prefs" => "Please choose your preferences",
                      "shifts" => "Please choose your available shifts");
  $error = "";
  foreach ($req_fields as $field => $msg) {
    if (!isset($_POST[$field]) || !$_POST[$field] ||
        !is_array($_POST[$field])) {
      $error .= $msg . " ";
    }
  }
  return $error;
}

function do_register() {
  $user = Auth::user();
  $data = array("user_id" => $user->id,
                "prefs" => $_POST["prefs"],
                "shifts" => $_POST["shifts"],
                "comments" => isset($_POST["comments"]) ?
                   $_POST["comments"] : "");
  $atc = ATC::create($data);
  if (!$atc) return "Error occured when saving application. Please try again";
  return "";
}
