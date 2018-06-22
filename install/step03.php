<?php

if ($is_complite) {
	try {
		$listOfTables = R::inspect();
		foreach ($listOfTables as $onetable) {
			$sql = 'DROP TABLE IF EXISTS '.$onetable.';';
			R::exec($sql);
		}
		echo '<li><i class="fa-li fa fa-check" style="color: green;"></i>The database has been cleaned.</li>';
	} catch (Exception $e) {
		$is_complite = false;
		echo '<li><i class="fa-li fa fa-ban" style="color: red;"></i>Unable to clean the database.</li>';
	}
} else echo '<li><i class="fa-li fa fa-ban" style="color: red;"></i>The operation can not be performed because the previous operation was not performed.</li>';

?>
