<?php

include 'connect.php';

setcookie('admin_id', '', time() - 1, '/');

header('login.php');

?>