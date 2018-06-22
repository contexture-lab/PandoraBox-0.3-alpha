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
                    <div class="panel-body">
                        Create a database, a user to access it, and give it a password.<br>
                        Enter the database name, user name and password in the fields below.<br>
                        If the database contains information, then make a backup copy of the database before installing the PandoraBox.
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>
                            <i class="fa fa-cog fa-spin"></i> Installation:
                        </strong>
                    </div>
                    <div class="panel-body">
                        <form action="install.php" method="post" class="form-horizontal">
                           <br>
                           <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-server fa-2x" title="Host" style="padding-left: 3px; padding-right: 3px;"></i>
                                </span>
                                <input name="dbhost" type="text" class="form-control" value="localhost" placeholder="Host" required>
                            </div>
                            <br>
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-database fa-2x" title="Name DB" style="padding-left: 3px; padding-right: 3px;"></i>
                                </span>
                                <input name="dbname" type="text" class="form-control" placeholder="Name DB" required pattern="^[a-zA-Z0-9_-]+$">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user fa-2x" title="Login DB" style="padding-left: 5px; padding-right: 5px;"></i>
                                </span>
                                <input name="dblogin" type="text" class="form-control" placeholder="Login DB" value="root" required pattern="^[a-zA-Z0-9_-]+$">
                            </div>
                            <div class="form-group input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-key fa-2x" title="Password DB" style="padding-left: 1px; padding-right: 1px;"></i>
                                </span>
                                <input name="dbpassword" type="password" class="form-control" placeholder="Password DB" required pattern="^[a-zA-Z0-9]+$">
                            </div>
                            <br>
                            <button name="setup" type="submit" class="btn btn-primary btn-block"><i class="fa fa-check"></i> Setup PandoraBox</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/include.js"></script><script>pbauth();</script>
</body>
</html>
