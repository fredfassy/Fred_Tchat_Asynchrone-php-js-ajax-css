<?php //conexxion à la base de données.
$login = "root";
$password = "°°°°°°°°°";
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

$pdo = new PDO('mysql:dbname=tchat;host=localhost', $login,$password,$options);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);