<?php

ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_probability', 1);
session_start();
define("USER", "root");
define("DB_NAME", "postmaker");
define("HOST", "localhost");
define("DS", DIRECTORY_SEPARATOR);
$base_url = $_SERVER['HTTP_HOST'] == 'localhost' ? 'postmaker/':'';
define('RENDERURL', $base_url.'assets/images/render/');
