<?php
$dir = "img/";
$images = scandir($dir);
$img = '';
while (substr($img, 0, 10) != 'background') {
  $img = $images[array_rand($images)];
}
header("Location: " . $dir . $img);
