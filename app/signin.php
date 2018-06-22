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
                <?php
                if ($setflags["auhead"]) {
                    echo '<div class="panel panel-default"><div class="panel-body"><div id="idTitleAuth">'.$auhead.'</div></div></div>';
                }

                if ($setflags["auinfo"]) {
                    echo '<div class="panel panel-default"><div id="idiInfoAuth" class="panel-body">'.$auinfo.'</div></div>';
                }

                if (!empty($errors)) {
                    ?>
                    <div class="panel panel-default">
                        <div id="idErrors" class="panel-body" style="color: red;">
                            <?= $errors[0]; ?>
                        </div>
                    </div>
                    <?php
                }

                if ($regok == false) {
                ?>

                <div id="authpanel" class="panel panel-default">
                    <div class="panel-heading">
                        <strong>
                            <i class="fa fa-unlock" aria-hidden="true"></i> <?=$locale['sign_in'];?>:
                        </strong>
                        <?php
                            if ($setflags["pbsignup"]) {
                                echo '<button onclick="soundClick(); document.getElementById(\'authpanel\').hidden = true; document.getElementById(\'regpanel\').hidden = false;" class="btn btn-default2 pull-right" title="'.$locale['sign_up'].'"><i class="fa fa-user-plus" aria-hidden="true"></i></button>';
                            }
                            if ($setflags["pbforgot"]) {
                                echo '<button onclick="soundClick(); document.getElementById(\'authpanel\').hidden = true; document.getElementById(\'forgotpanel\').hidden = false;" class="btn btn-default2 pull-right" title="'.$locale['forgot_password'].'"><i class="fa fa-question-circle-o" aria-hidden="true"></i></button>';
                            }
                        ?>
                    </div>
                    <div class="panel-body">
                        <form action="/" method="post" class="form-horizontal">
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user fa-2x" title="<?=$locale['login'];?>" style="padding-left: 5px; padding-right: 5px;"></i>
                                </span>
                                <input name="login" type="text" class="form-control" value="<?= @$login; ?>" placeholder="<?=$locale['login'];?>" required pattern="^[a-zA-Z0-9_-]+$">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-key fa-2x" title="<?=$locale['password'];?>" style="padding-left: 1px; padding-right: 1px;"></i>
                                </span>
                                <input name="password" type="password" class="form-control" value="<?= @$password; ?>" placeholder="<?=$locale['password'];?>" required pattern="^[a-zA-Z0-9]+$">
                            </div>
                            <button name="signin" type="submit" class="btn btn-primary btn-block"><i class="fa fa-sign-in"></i> <?=$locale['sign_in'];?></button>
                        </form>
                    </div>
                </div>

                <div id="forgotpanel" class="panel panel-default" hidden>
                    <div class="panel-heading">
                        <strong>
                            <i class="fa fa-id-badge" aria-hidden="true"></i> <?=$locale['password_reminder'];?>:
                        </strong>
                        <?php
                            if ($setflags["pbsignup"]) {
                                echo '<button onclick="soundClick(); document.getElementById(\'forgotpanel\').hidden = true; document.getElementById(\'regpanel\').hidden = false;" class="btn btn-default2 pull-right" title="'.$locale['sign_up'].'"><i class="fa fa-user-plus" aria-hidden="true"></i></button>';
                            }
                        ?>
                        <button onclick="soundClick(); document.getElementById('forgotpanel').hidden = true; document.getElementById('authpanel').hidden = false;" class="btn btn-default2 pull-right" title="<?=$locale['sign_in'];?>"><i class="fa fa-unlock" aria-hidden="true"></i></button>
                    </div>
                    <div class="panel-body">
                        <form action="/" method="post" class="form-horizontal">
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user fa-2x" title="<?=$locale['login'];?>" style="padding-left: 5px; padding-right: 5px;"></i>
                                </span>
                                <input name="login" type="text" class="form-control" value="<?= @$login; ?>" placeholder="<?=$locale['login'];?>" pattern="^[a-zA-Z0-9_-]+$">
                            </div>
                            &nbsp; <?=$locale['or_or'];?>
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-at fa-2x" title="<?=$locale['email'];?>" style="padding-left: 3px; padding-right: 3px;"></i>
                                </span>
                                <input name="email" type="email" class="form-control" value="<?= @$email; ?>" placeholder="<?=$locale['email'];?>">
                            </div>
                            <button name="forgot" type="submit" class="btn btn-primary btn-block"><i class="fa fa-question"></i> <?=$locale['password_reminder'];?></button>
                        </form>
                    </div>
                </div>

                <div id="regpanel" class="panel panel-default" hidden>
                    <div class="panel-heading">
                        <strong>
                            <i class="fa fa-registered" aria-hidden="true"></i> <?=$locale['sign_up'];?>:
                        </strong>
                        <button onclick="soundClick(); document.getElementById('regpanel').hidden = true; document.getElementById('authpanel').hidden = false;" class="btn btn-default2 pull-right" title="<?=$locale['sign_in'];?>"><i class="fa fa-unlock" aria-hidden="true"></i></button>
                        <?php
                            if ($setflags["pbforgot"]) {
                                echo '<button onclick="soundClick(); document.getElementById(\'regpanel\').hidden = true; document.getElementById(\'forgotpanel\').hidden = false;" class="btn btn-default2 pull-right" title="'.$locale['forgot_password'].'"><i class="fa fa-question-circle-o" aria-hidden="true"></i></button>';
                            }
                        ?>
                    </div>
                    <div class="panel-body">
                        <form action="/" method="post" class="form-horizontal">
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user fa-2x" title="<?=$locale['login'];?>" style="padding-left: 5px; padding-right: 5px;"></i>
                                </span>
                                <input name="login" type="text" class="form-control" value="<?= @$login; ?>" placeholder="<?=$locale['login'];?>" required pattern="^[a-zA-Z0-9_-]+$">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-key fa-2x" title="<?=$locale['password'];?>" style="padding-left: 1px; padding-right: 1px;"></i>
                                </span>
                                <input name="password" type="password" class="form-control" value="<?= @$password; ?>" placeholder="<?=$locale['password'];?>" required pattern="^[a-zA-Z0-9]+$">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-shield fa-2x" title="<?=$locale['password_retry'];?>" style="padding-left: 5px; padding-right: 5px;"></i>
                                </span>
                                <input name="password_retry" type="password" class="form-control" value="<?= @$password_retry; ?>" placeholder="<?=$locale['password_retry'];?>" required pattern="^[a-zA-Z0-9]+$">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-at fa-2x" title="<?=$locale['email'];?>" style="padding-left: 3px; padding-right: 3px;"></i>
                                </span>
                                <input name="email" type="email" class="form-control" value="<?= @$email; ?>" placeholder="<?=$locale['email'];?>" required>
                            </div>
                            <?=$locale['optional_data'];?>:<br>
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-phone fa-2x" title="<?=$locale['phone'];?>" style="padding-left: 4px; padding-right: 4px;"></i>
                                </span>
                                <input name="phone" type="text" class="form-control" value="<?= @$phone; ?>" placeholder="<?=$locale['phone'];?>">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-address-book fa-2x" title="<?=$locale['full_name'];?>" style="padding-left: 2px; padding-right: 2px;"></i>
                                </span>
                                <input name="fname" type="text" class="form-control" value="<?= @$fname; ?>" placeholder="<?=$locale['full_name'];?>">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-road fa-2x" title="<?=$locale['address'];?>"></i>
                                </span>
                                <input name="address" type="text" class="form-control" value="<?= @$address; ?>" placeholder="<?=$locale['address'];?>">
                            </div>
                            <button name="signup" type="submit" class="btn btn-primary btn-block"><i class="fa fa-user-circle"></i> <?=$locale['sign_up'];?></button>
                        </form>
                    </div>
                </div>

                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <script src="js/include.js"></script><script>pbauth();</script>
</body>
</html>
