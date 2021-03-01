<?php
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_probability', 1);
session_start();
define("USER", "root");
define("DB_NAME", "postmaker");
define("HOST", "localhost");
define("DS", DIRECTORY_SEPARATOR);
// $db = new mysqli(HOST,USER,$password,DB_NAME);
if (mysqli_connect_errno()) {
   echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
   echo 'Database connection failed with following errors: ' . mysqli_connect_error();
   die();
}
$dir = $dir ?? '';

require_once __DIR__ . '/vendor/autoload.php';

use Controller\UsersController as user;

$unique_id = user::get_unique_id();
