<?php
if (isset($contact_addressee)) {
  $GLOBALS['contact_addressee'] = $contact_addressee;
}

function selected($item) {
  global $contact_addressee;
  if (!isset($contact_addressee)) return;
  if ($item == $contact_addressee) return 'selected="selected"';
}
?>
<h1>Contact Us</h1>
<p>You can direct your enquiries to the Event Organiser, David Zhong at <a href="mailto:dzhong111+realops@gmail.com">dzhong111+realops@gmail.com</a>.</p>
<?php /*
if (Auth::check()) {
  $user = Auth::user(); 
  if (isset($error)) { ?>
<p class="bg-danger realops-message">Error: <?=$error?></p>
<?php
  } ?>
<h1>Contact Us</h1>
<form action="/contact" method="post" role="form">
  <div class="form-group">
    <label for="contact_addressee">Type of enquiry</label>
    <select name="contact_addressee" class="form-control">
      <option value="event"<?=selected("event")?>>Event and Media Enquiries</option>
      <option value="it"<?=selected("it")?>>Website Enquiries</option>
      <option value="atc"<?=selected("atc")?>>ATC Operations</option>
    </select>
  </div>
  <div class="form-group">
    <label for="contact_subject">Subject</label>
    <input type="text" name="contact_subject" value="<?=htmlspecialchars($contact_subject)?>" class="form-control"/>
  </div>
  <div class="form-group">
    <label for="contact_message">Message</label>
    <textarea rows="10" cols="32" name="contact_message" class="form-control"><?=htmlspecialchars($contact_message)?></textarea>
  </div>
  <button type="submit" class="btn btn-default">Send</button>
</form>
<?php
} else { ?>
<p>Please <a href="<?=Route::link('login')?>?return_url=<?=util::current_url_enc()?>" data-toggle="modal" data-target="#realops-login-info" >login</a> to send us a message. If you are having trouble logging in, please visit the <a href="http://vatpac.org/forums/" target="_blank">VATPAC Forums</a>.</p>
<?php
} */ ?>
