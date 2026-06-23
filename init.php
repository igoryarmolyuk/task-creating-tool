<?php
include '/var/www/html/config.php';
include '/var/www/html/modules/functions.php';

global $db_link;
global $routes, $validators, $middleWares;
$routes = [];
$validators = [];
$middleWares = [];

addValidator('string', function ($value) {
    return is_string($value);
});

addValidator('required', function ($value) {
    return !empty($value);
});
addValidator('email', function ($value) {
    return !empty($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
});
addValidator('min', function ($value, $count) {
    return strlen(trim($value)) >= (int)$count;
});
addValidator('max', function ($value, $count) {
    return strlen($value) <= (int)$count;
});
addValidator('nullable', function ($value) {
    return true;
});

addMiddleware('auth', function () {
    if (empty($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
});

ini_set('session.save_path',  dirname(__DIR__) . '/html/storage/sessions');

session_start();
$db_link= mysqli_connect(db_host, db_user, db_password, db_base, db_port);