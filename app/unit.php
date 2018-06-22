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
    <link href="css/leaflet.css" rel="stylesheet">
    <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v0.45.0/mapbox-gl.css" rel="stylesheet">
    <link href="css/unit.css" rel="stylesheet">
    <link href="css/scroll.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="js/leaflet.js"></script>
    <script src="https://api.tiles.mapbox.com/mapbox-gl-js/v0.45.0/mapbox-gl.js"></script>
    <script src="js/leaflet-mapbox-gl.js"></script>
    <script>
        window.jQuery || document.write('<script src="js/jquery-3.2.1.min.js"><\/script><script src="js/jquery-ui.min.js"><\/script>')
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12" id="unitbox">
                <div id="style-selector-control" class="map-control">
                    &nbsp;<strong><i class="fa fa-map-signs" aria-hidden="true" title="<?=$locale['type_of_map'];?>"></i>:</strong>&nbsp;
                    <button onclick="toggleTypeMap('roadmap')" class="btn btn-default3" title="<?=$locale['road_map'];?>"><i class="fa fa-map" aria-hidden="true"></i></button>
                    <button onclick="toggleTypeMap('satellite')" class="btn btn-default3" title="<?=$locale['satellite'];?>"><i class="fa fa-globe" aria-hidden="true"></i></button>
                    <button onclick="toggleTypeMap('hybrid')" class="btn btn-default3" title="<?=$locale['hybrid'];?>"><i class="fa fa-plane" aria-hidden="true"></i></button>
                    &nbsp;
                    <button onclick="toggleTypeMap('dark')" class="btn btn-default3"><i class="fa fa-image" aria-hidden="true"></i></button>
                    <button onclick="toggleTypeMap('decimal')" class="btn btn-default3"><i class="fa fa-road" aria-hidden="true"></i></button>
                    <button onclick="toggleTypeMap('b3d')" class="btn btn-default3"><i class="fa fa-cube" aria-hidden="true"></i></button>
                </div>
                <div id="map"></div>
                <div id="menubox">
                    <div class="text-center" style="margin-top: 3px;">
                        <button onclick="countMsgList = 10; loadUnitMsg()" class="btn btn-default2" title="<?=$locale['messages'];?>"><i class="fa fa-envelope-o fa-2x" aria-hidden="true"></i></button>
                        <button onclick="loadUnitTask()" class="btn btn-default2" title="<?=$locale['tasks'];?>"><i class="fa fa-crosshairs fa-2x" aria-hidden="true"></i></button>
                        <button onclick="pbOpenFolder()" class="btn btn-default2" title="<?=$locale['information'];?>"><i class="fa fa-folder-open-o fa-2x" aria-hidden="true"></i></button>
                        <button onclick="loadUnitSetings()" class="btn btn-default2" title="<?=$locale['settings'];?>"><i class="fa fa-cogs fa-2x" aria-hidden="true"></i></button>
                        <button onclick="soundClick(); $('#signOutModal').modal('show');" class="btn btn-default2" title="<?=$locale['sign_out'];?>"><i class="fa fa-home fa-2x" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div> <!-- unitbox -->
        </div>
    </div>

    <div id="msgModal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                        <?=$locale['messages'];?>.
                        <button onclick="moreUnitMsg()" class="btn btn-default2" title="<?=$locale['more'];?>"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
                    </h4>
                </div>
                <div class="modal-body" id="idOutMsgUnit">
                </div>
                <div class="modal-footer">
                    <div class="form-group input-group dropup">
                        <input id="inputUnitMsg" type="text" class="form-control" placeholder="<?=$locale['message'];?>">
                        <span class="input-group-btn">
                            <button onclick="sendUnitMsg();" class="btn btn-default3" title="<?=$locale['send'];?>"><i class="fa fa-comment-o fa-2x"></i></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="idTasks" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-crosshairs" aria-hidden="true"></i>
                        <?=$locale['tasks'];?>.
                    </h4>
                </div>
                <div id="idOutTask" class="modal-body">
                </div>
                <div class="modal-footer">
                        <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="idReport" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        <?=$locale['report'];?>.
                    </h4>
                </div>
                <div class="modal-body">
                    <textarea id="msgReport" class="form-control" rows="10"></textarea>
                </div>
                <div id="footerReport" class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <div id="signOutModal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                        <?=$locale['sign_out'];?>.
                    </h4>
                </div>
                <div class="modal-body">
                    <?=$locale['you_are_authorized_as_user'];?>: <font color="yellow"><?= $user->name; ?></font>.<br><br>
                    <?=$locale['do_you_want_to_exit_the_interaction_interface'];?>
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['cancel'];?></button>
                    <button onclick="window.location.pathname = '/app/logout.php';" class="btn btn-primary"><i class="fa fa-sign-out" aria-hidden="true"></i><?=$locale['sign_out'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="unitSetModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-sliders" aria-hidden="true"></i>
                        <?=$locale['settings'];?>.
                        <button id="idSoundbutton" onclick="toggleSound()" class="btn btn-default2" title="<?=$locale['mute'];?>"><i class="fa fa-volume-up" aria-hidden="true"></i></button>
                    </h4>
                </div>
                <div class="modal-body" id="unitSetContent">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                    <button onclick="saveUnitSetings()" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div id="openFolder" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-folder-open-o" aria-hidden="true"></i>
                        <?=$locale['information'];?>.
                    </h4>
                </div>
                <div id="idfolder" class="modal-body">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="openInfo" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="idinfoname" class="modal-header">
                </div>
                <div id="idinfobody" class="modal-body">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="msgAlert" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                        <?=$locale['attention'];?>.
                    </h4>
                </div>
                <div id="idalert" class="modal-body">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="msgLocationError" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                        <?=$locale['attention'];?>.
                    </h4>
                </div>
                <div class="modal-body">
                    <?=$locale['enable_location_services_on_your_device_and_let_this_site_determine_your_location'];?>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal"><?=$locale['ok'];?></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var startLat = <?=$pblat;?>;
        var startLng = <?=$pblng;?>;
        var sysAccuracy = <?=$pbaccuracy;?>;
        var sysTimeout = <?=($systimeout*1000);?>;
        var sysLanguage = '<?=$pblanguage;?>';
        <?php
            echo 'var locale = { ';
            foreach ($locale as $keyloc => $valueloc) {
                echo $keyloc.': "'.$valueloc.'", ';
            }
            echo 'langlocale: "'.$pblanguage.'" }; ';
        ?>
    </script>
    <script src="js/include.js"></script><script>pbunit();</script>
</body>
</html>
