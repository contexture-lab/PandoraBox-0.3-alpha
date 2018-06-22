<?php

$regok = false;
$changepas = $dataget['change'];

$res = R::findOne('users', 'chpassw = ?', array($changepas));
if ($res) {
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
    <link rel="Shortcut Icon" href="/favicon.ico" type="image/x-icon">
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

                <div id="regpanel" class="panel panel-default">
                    <div class="panel-heading">
                        <strong>
                            <i class="fa fa-wrench" aria-hidden="true"></i> <?=$locale['change_password'];?>:
                        </strong>
                    </div>
                    <div class="panel-body">
                        <form action="/" method="post" class="form-horizontal">
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-key fa-2x" title="Password" style="padding-left: 1px; padding-right: 1px;"></i>
                                </span>
                                <input name="login" type="hidden" value="<?= $res->name; ?>">
                                <input name="password" type="password" class="form-control" value="<?= @$password; ?>" placeholder="<?=$locale['password'];?>" required pattern="^[a-zA-Z0-9]+$">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-shield fa-2x" title="<?=$locale['password_retry'];?>" style="padding-left: 5px; padding-right: 5px;"></i>
                                </span>
                                <input name="password_retry" type="password" class="form-control" value="<?= @$password_retry; ?>" placeholder="<?=$locale['password_retry'];?>" required pattern="^[a-zA-Z0-9]+$">
                            </div>
                            <button name="changebut" type="submit" class="btn btn-primary btn-block"><i class="fa fa-pencil-square-o"></i> <?=$locale['change_password'];?></button>
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
} else {
	unset($_SESSION['logged_user']);
	unset($_SESSION['pb_intrface']);
	header("Location: ".$protocol.$_SERVER['HTTP_HOST']."/");
}
