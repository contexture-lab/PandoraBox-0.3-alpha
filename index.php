<?php
extract($_POST, EXTR_SKIP);
$dataget = $_GET;
require 'app/rb.php';
session_start();

require_once "config.php";
require 'app/func.php';

require 'lang/arrlangs.php';
$langreq = "lang/".$pblanguage.".php";
include $langreq;

$strhelp = "help/".$pblanguage.".php";
include $strhelp;

$dsn = 'mysql:host='.$dbhost.';dbname='.$dbname;
R::setup( $dsn, $dblogin, $dbpassword );
R::freeze(true);
$isConnected = R::testConnection();
if ($isConnected) {
	if (isset($dataget['confirm'])) {
		require 'app/confirm.php';
	} elseif (isset($dataget['change'])) {
		require 'app/change.php';
	} else {
		require 'app/auth.php';
	}

} else {
	echo $locale['unable_to_connect_to_the_database'];
}
