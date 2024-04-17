<?php
$ip_password='s';
  $encrypted_password = password_hash($ip_password, PASSWORD_DEFAULT);
  echo $encrypted_password ;
?>

