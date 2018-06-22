<?php
require 'rb.php';
session_start();

require_once "../config.php";
require 'func.php';

$user = $_SESSION['logged_user'];

$dsn = 'mysql:host='.$dbhost.';dbname='.$dbname;
R::setup( $dsn, $dblogin, $dbpassword );
R::freeze(true);
$isConnected = R::testConnection();

if ($isConnected) {
	writeLog("signout", $user->id, 0, "", "");
}

unset($user);
unset($_SESSION['logged_user']);
unset($_SESSION['pb_intrface']);

header("Location: ".$protocol.$_SERVER['HTTP_HOST']."/");
