<?php

if ($is_complite) {
	require 'app/rb.php';
	$dsn = 'mysql:host='.$dbhost.';dbname='.$dbname;
	R::setup( $dsn, $dblogin, $dbpassword );

	$isConnected = R::testConnection();
	if ($isConnected) {
		echo '<li><i class="fa-li fa fa-check" style="color: green;"></i>The connection to the database has been completed.</li>';
	} else {
		$is_complite = false;
		echo '<li><i class="fa-li fa fa-ban" style="color: red;"></i>'.$locale['unable_to_connect_to_the_database'].'</li>';
	}
} else echo '<li><i class="fa-li fa fa-ban" style="color: red;"></i>Can not perform the operation.</li>';

?>
