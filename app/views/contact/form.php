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
<form action="/contact" method="post" role="form">
  <div class="form-group">
    <label for="contact_addressee">Type of enquiry</label>
    <select name="contact_addressee" class="form-control">
      <option value="event"<?=selected("event")?>>Event and Media Enquiries</option>
      <option value="it"<?=selected("it")?>>Website Enquiries</option>
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
