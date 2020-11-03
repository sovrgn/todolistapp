<?php

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$db = 'todolist';

$dsn = "mysql:host=$dbhost; dbname=$db";

$connect = new PDO($dsn, $dbuser, $dbpass);

if (!isset($_SESSION)) {session_start();}
?>
