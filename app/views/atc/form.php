<?php
$ordinal = array("First", "Second", "Third");
?>
<h1>ATC Registration</h1>
<?php
if (Auth::check()) {
  $user = Auth::user(); 
  if (isset($error)) { ?>
<p class="bg-danger realops-message">Error: <?=$error?></p>
<?php
  } ?>
<form method="POST">
  <p class="help-block">Your name, email address and ATC rating have been pre-populated with information from VATSIM. If any of this information is incorrect, please submit your application and <a href="<?=Route::link("contact")?>">contact us with the correct details</a>.</p>
  <div class="form-group">
    <label for="realops-atc-name">Name</label>
    <input type="text" name="name" value="<?=$user->name?>" readonly id="realops-atc-name" class="form-control">
  </div>
  <div class="form-group">
    <label for="realops-atc-email">Email</label>
    <input type="email" name="email" value="<?=$user->email?>" readonly id="realops-atc-email" class="form-control">
  </div>
  <div class="form-group">
    <label for="realops-atc-rating">ATC Rating</label>
    <input type="rating" name="text" value="<?=atcstuff::$ratings[$user->rating]?>" readonly id="realops-atc-rating" class="form-control">
  </div>
<?php
  for ($i = 0; $i < 3; $i++) { ?>
  <div class="form-group">
    <label for="realops-atc-pref<?=$i?>"><?=$ordinal[$i]?> Preference</label>
    <select name="prefs[]" id="realops-atc-pref<?=$i?>" class="form-control">
<?php
      for ($j = 0; $j < count(atcstuff::$positions); $j++) { ?>
      <option value="<?=$j?>"<?=(isset($_POST["prefs"]) && $_POST["prefs"][$i]==$j) ? " selected" : ""?>><?=atcstuff::$positions[$j]?></option>
<?php
      } ?>
    </select>
  </div>
<?php
  } ?>
  <div class="form-group">
    <label>Shift</label>
<?php
    foreach (atcstuff::$shifts as $i => $shift) { ?>
    <div class="checkbox">
      <label><input type="checkbox" name="shifts[]" value="<?=$i?>"<?=(isset($_POST["shifts"]) && in_array($i, $_POST["shifts"])) ? " checked" : ""?>><?=$shift?></label>
    </div>
<?php
    } ?>
    <p class="help-block">Approximate Sydney shift times only. These may vary by up to 20 minutes to allow for shift changes.</p>
  </div>
  <div class="form-group">
    <label for="realops-atc-comments">Extra Comments</label>
    <p class="help-block">If you have selected "En route" or "Brisbane/Melbourne" as one of your preferences, please elaborate on your availability.</p>
    <textarea name="comments" rows="4" id="realops-atc-comments" class="form-control"><?=isset($_POST["comments"]) ? htmlspecialchars($_POST["comments"]) : ""?></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Register</button>
</form>
<?php
} else { ?>
<p>Please <a href="<?=Route::link('login')?>?return_url=<?=util::current_url_enc()?>" data-toggle="modal" data-target="#realops-login-info" >login</a> to register.</p>
<?php
} ?>
