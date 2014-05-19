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
<form action="/contact" method="post">
 <fieldset>
 <legend>Send us a message</legend>
  <table id="contact">
   <tr>
    <td>To</td>
    <td>
     <select name="contact_addressee">
      <option value="event"<?=selected("event")?>>Event and Media Enquiries</option>
      <option value="it"<?=selected("it")?>>Website Enquiries</option>
     </select>
    </td>
   </tr>
   <tr>
    <td>Subject</td>
    <td><input type="text" size="32" name="contact_subject" value="<?=$contact_subject?>"/></td>
   </tr>
   <tr>
    <td>Message</td>
    <td><textarea rows="10" cols="32" name="contact_message"><?=$contact_message?></textarea></td>
   </tr>
   <tr>
    <td colspan="2"><input type="submit" name="contact_submit" value="Send" /></td>
   </tr>
  </table>
</fieldset>
</form>
