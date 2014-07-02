<h1><?=$flight->acid?></h1>
<?php
if (isset($error)) { ?>
<p class="bg-danger realops-message">Error: <?=$error?></p>
<?php
}
if (isset($success)) { ?>
<p class="bg-success realops-message"><?=$success?></p>
<?php
} ?>
<table class="table" id="realops-flight-view">
  <tbody>
    <tr>
      <td colspan="2"><strong>Aircraft type:</strong> <?=$flight->type?></td>
    </tr>
    <tr>
      <td><strong>Departing</strong><br />
            <?=$flight->adep?><br />
            <?=date('H:i', strtotime($flight->std))?> UTC
        </td>
        <td><strong>Arriving</strong><br />
            <?=$flight->ades?><br />
            <?=date('H:i', strtotime($flight->sta))?> UTC
        </td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2"><strong>Route: </strong><?=$flight->route?></td>
    </tr>
    <tr>
      <td colspan="2"><strong>Information:</strong> <?=$flight->info?></td>
    </tr>
  </tfoot>
</table>
<div class="form-group">
<?php

if ($flight->user) {
  if (Auth::check() && $flight->user->id == Auth::user()->id) { ?>
  <form role="form" method="POST">
    <input type="hidden" name="_method" value="DELETE" />
    <p><button class="btn btn-danger">Cancel this booking</button></p>
  </form>
<?php
  } else { ?>
  <p>This flight has been booked by <strong><?=$flight->user->name?></strong></p>
<?php
    if (Auth::check() && Auth::user()->admin) { ?>
  <p>User email address: <?=$flight->user->email?></p>
<?php
    }
  }
} else {
  if (Auth::check()) { ?>
  <form role="form" method="POST">
    <p><button class="btn btn-primary">Book this flight</button></p>
  </form>
<?php
  } else { ?>
  <p><a href="<?=Route::link('login')?>?return_url=<?=util::current_url_enc()?>" data-toggle="modal" data-target="#realops-login-info" class="btn btn-primary">Login to book this flight</a></p>
<?php
  }
} ?>
</div>
