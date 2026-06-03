<?php
include '/var/www/html/config.php';
include '/var/www/html/modules/functions.php';

global $db_link;
session_start();
$db_link= mysqli_connect(db_host, db_user, db_password, db_base, db_port);