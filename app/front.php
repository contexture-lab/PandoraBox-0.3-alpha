<?php
$operIFFlag = false;
$unitIFFlag = false;

if (isset($_SESSION['pb_intrface'])) {
	if ($_SESSION['pb_intrface'] == 1) require 'officer.php';
	elseif ($_SESSION['pb_intrface'] == 2) require 'unit.php';
} else {
	$str1 = $user->role;
	$rolesArr = explode(";", $str1);
	if (!empty($rolesArr)) {
		if (count($rolesArr) > 2) {
			foreach ($rolesArr as $role) {
				if ($role == 'admin') $operIFFlag = true;
				elseif ($role == 'officer') $operIFFlag = true;
				elseif ($role == 'accaunter') $operIFFlag = true;
				elseif ($role == 'unit') $unitIFFlag = true;
			}

			?>
<!DOCTYPE html>
<html lang="<?=$pblanguage;?>">
<head>
    <meta charset="utf-8">
    <?php
        if ($setflags["pbtitle"]) {
            echo '<title>'.$pbtitle.' ('.$pblabel.')</title>';
        } else {
            echo '<title>'.$pblabel.'</title>';
        }
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link href="css/signin.css" rel="stylesheet">
    <link href="css/scroll.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="js/jquery-3.2.1.min.js"><\/script><script src="js/jquery-ui.min.js"><\/script>')
    </script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-3 col-md-6" style="height: 100vh;">
                <br>
                <?php

                if ($setflags["auhead"]) {
                    echo '<div class="panel panel-default"><div class="panel-body"><div id="idTitleAuth">'.$auhead.'</div></div></div>';
                }

                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>
                            <i class="fa fa-indent" aria-hidden="true"></i> <?=$locale['access_interface'];?>:
                        </strong>
                    </div>
                    <div class="panel-body">
                        <form action="/" method="post" class="form-horizontal">
                        <?php
                            if ($operIFFlag) {
                                ?>
                        <button name="efoper" type="submit" class="btn btn-primary btn-block"><i class="fa fa-user-secret"></i> <?=$locale['officer'];?></button>
                                <?php
                            }
                            if ($unitIFFlag) {
                                ?>
                        <button name="efunit" type="submit" class="btn btn-primary btn-block"><i class="fa fa-user"></i> <?=$locale['executor'];?></button>
                                <?php
                            }
                        ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/include.js"></script><script>pbauth();</script>
</body>
</html>
			<?php

		} elseif ($rolesArr[0] == 'admin') {
			$_SESSION['pb_intrface'] = 1;
			require 'officer.php';
		} elseif ($rolesArr[0] == 'officer') {
			$_SESSION['pb_intrface'] = 1;
			require 'officer.php';
		} elseif ($rolesArr[0] == 'accaunter') {
			$_SESSION['pb_intrface'] = 1;
			require 'officer.php';
		} elseif ($rolesArr[0] == 'unit') {
			$_SESSION['pb_intrface'] = 2;
			require 'unit.php';
		}
	} else {
		unset($_SESSION['logged_user']);
		unset($_SESSION['pb_intrface']);
 		unset($user);
 		require 'signin.php';
	}
}
