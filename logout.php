<?php

require('config.php');

session_destroy();

var_dump($_SESSION);
exit;

header('Location: login.php');

?>
