<?php

if ($sys == "i_am_active") {
	$upduser = R::load('users', $user->id);
	if ( ($upduser->latitude != $lat) or ($upduser->longitude != $lng) or ($upduser->accuracy != $accu) ) {
		$upduser->latitude = $lat;
		$upduser->longitude = $lng;
		$upduser->accuracy = $accu;
		$upduser->lastactive = time();
		R::store($upduser);
		writeLog("updlla", 0, $user->id, "users", $upduser);
		$_SESSION['logged_user'] = $upduser;
	} else {
		$upduser->lastactive = time();
		R::store($upduser);
		$_SESSION['logged_user'] = $upduser;
	}
} else {
	echo $locale['unknown_command'];
}
