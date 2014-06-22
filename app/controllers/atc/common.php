<?php
function check_dupe() {
  $user = Auth::user();
  $results = ATC::where("user_id", "=", $user->id)->all();
  if ($results) return true;
  else return false;
}
