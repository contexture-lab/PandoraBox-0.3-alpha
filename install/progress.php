<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PandoraBox - Install</title>
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
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div>
                            Installing PandoraBox.
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="progress">
                            <div id="idprocprog" class="progress-bar progress-bar-success progress-bar-striped active" style="width: 100%;">100 %</div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <ul class="fa-ul">
                        <?php
                            $is_complite = true;
                            require 'step01.php';
                            require 'step02.php';
                            require 'step03.php';
                            require 'step04.php';
                            require 'step05.php';
                            require 'step06.php';
                        ?>
                        </ul>
                        <?php
                            if ($is_complite) {
                        ?>
                                <br>
                                Now you can delete the <b>"install.php"</b> file and the <b>"install"</b> folder.<br>
                                Installing PandoraBox on your hosting is complete!
                        <?php
                            } else {
                        ?>
                                <br>
                                The installation was not successful.<br>
                                Eliminate the problems with your hosting and try again.
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/include.js"></script><script>pbauth();</script>
</body>
</html>
