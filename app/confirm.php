<?php
$regok = false;
$confirmreg = $dataget['confirm'];

$res = R::findOne('users', 'chpassw = ?', array($confirmreg));
if ($res) {
	$res->status = '1';
	R::store($res);
	writeLog("updstr", 0, 0, "users", $res);
	$regok = true;
	$errors[] = $locale['registration_is_confirmed'].' <a href="'.$protocol.$_SERVER['HTTP_HOST'].'/"> '.$locale['sign_in'].' </a> ';
	require 'signin.php';
}
