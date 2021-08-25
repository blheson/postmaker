<?php

$dir = $dir ?? '';

require_once __DIR__ . '/vendor/autoload.php';
include_once 'config.php';
use Controller\UsersController as user;

$unique_id = user::get_unique_id();
