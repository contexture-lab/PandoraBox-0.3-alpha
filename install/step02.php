<?php

if ($is_complite) {
	try {
		$cmd = 'mysqldump -h '.$dbhost.' -u '.$dblogin.' -p'.$dbpassword.' '.$dbname.' > ./backup/pb-db_'.time().'.sql';
		shell_exec($cmd);
		echo '<li><i class="fa-li fa fa-check" style="color: green;"></i>The database has been backed up.</li>';
	} catch (Exception $e) {
		$is_complite = false;
		echo '<li><i class="fa-li fa fa-ban" style="color: red;"></i>Unable to perform a database backup.</li>';
	}
} else echo '<li><i class="fa-li fa fa-ban" style="color: red;"></i>The operation can not be performed because the previous operation was not performed.</li>';

?>
