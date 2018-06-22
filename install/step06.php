<?php

if ($is_complite) {
	try {
		$securecode = sha1($_SERVER['HTTP_HOST'].time());
		$pbconfig = '<?php ';
		$pbconfig .= '$dbhost = '.'"'.$dbhost.'"; ';
		$pbconfig .= '$dbname = '.'"'.$dbname.'"; ';
		$pbconfig .= '$dblogin = '.'"'.$dblogin.'"; ';
		$pbconfig .= '$dbpassword = '.'"'.$dbpassword.'"; ';
		$pbconfig .= '$pblabel = '.'"'.'PandoraBox v.: 0.3 (alpha)'.'"; ';
		$pbconfig .= '$securecode = '.'"'.$securecode.'"; ';
		$pbconfig .= '$pbtitle = '.'"'.$locale['pbtitle'].'"; ';
		$pbconfig .= '$auhead = '.'"'.$locale['auhead'].'"; ';
		$pbconfig .= '$auinfo = '.'"'.$locale['auinfo'].'"; ';
		$pbconfig .= '$pbaccuracy = '.'300'.'; ';
		$pbconfig .= '$systimeout = '.'30'.'; ';
		$pbconfig .= '$pblanguage = "'.'en'.'"; ';
		$pbconfig .= '$pblat = '.'51.5057'.'; ';
		$pbconfig .= '$pblng = '.'-0.1235'.'; ';
		$pbconfig .= '$setflags = [ "pbtitle" => false, "auhead" => true, "auinfo" => true, "pbsignup" => true, "pbforgot" => true ];';

		file_put_contents('./config.php', $pbconfig);

		echo '<li><i class="fa-li fa fa-check" style="color: green;"></i>The configuration is recorded.</li>';
	} catch (Exception $e) {
		$is_complite = false;
		echo '<li><i class="fa-li fa fa-ban" style="color: red;"></i>Can not write configuration.</li>';
	}
} else echo '<li><i class="fa-li fa fa-ban" style="color: red;"></i>The operation can not be performed because the previous operation was not performed.</li>';

?>
