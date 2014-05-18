<?php
if (isset($_SESSION['id'])) {?>
<p>Welcome <?=$_SESSION['name']?></p>
<p><a href="/user/logout">Logout</a></p>
<?php
} else {
?>
<p><a href="#">Login with VATSIM</a></p>
<?php
}
?>