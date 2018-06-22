<?php
    clearLog();
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
    <link href="css/leaflet.css" rel="stylesheet">
    <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v0.45.0/mapbox-gl.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
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
            <div class="col-lg-12 col-md-12 col-sm-12" id="mapbox">
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
                    <div id="idMassRadio" class="panel panel-default" hidden>
                        <div class="panel-heading">
                            <strong>
                                <i class="fa fa-bullhorn" aria-hidden="true"></i> <?=$locale['radio_set'];?>
                            </strong>
                            <button onclick="offMassRadio()" class="btn btn-default2 pull-right"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </div>
                        <div class="panel-body text-center">
                            <i class="fa fa-microphone fa-2x" aria-hidden="true"></i>
                            <button id="idMicbutton" onclick="soundClick(); toggleMic();" class="btn btn-default2" title="<?=$locale['on_microphone'];?>"><i class="fa fa-toggle-off" aria-hidden="true"></i></button>
                            &nbsp; &nbsp; &nbsp;
                            <i class="fa fa-volume-up fa-2x" aria-hidden="true"></i>
                            <button id="idVolbutton" onclick="soundClick(); toggleVolume();" class="btn btn-default2" title="<?=$locale['on_sound'];?>"><i class="fa fa-toggle-off" aria-hidden="true"></i></button>
                        </div>
                    </div>

                    <div id="idHome" class="panel panel-default">
                       <div class="panel-heading">
                            <strong>
                                <i class="fa fa-home" aria-hidden="true"></i> <?=$locale['main_menu'];?>.
                            </strong>
                        </div>
                        <div class="panel-body">
                            <?=$locale['use_the_menu_commands_to_control_the_system_and_the_performers'];?>
                        </div>
                    </div>

                    <div id="idSysMsg" class="panel panel-msg" hidden>
                        <div class="panel-heading">
                            <strong>
                                <i class="fa fa-exclamation" aria-hidden="true"></i> <?=$locale['system_message'];?>
                            </strong>
                        </div>
                        <div class="panel-body">
                        </div>
                    </div>

                    <div id="idGlobalMsg" class="panel panel-msg" hidden>
                        <div class="panel-heading">
                            <strong>
                                <i class="fa fa-globe" aria-hidden="true"></i> <?=$locale['global_message'];?>
                            </strong>
                            <button onclick="soundClick();" class="btn btn-default2 pull-right" title="<?=$locale['more'];?>"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
                        </div>
                        <div class="panel-body">
                        </div>
                        <div class="panel-footer">
                            <div class="form-group input-group">
                                <input type="text" class="form-control" placeholder="<?=$locale['message'];?>">
                                <span class="input-group-btn">
                                    <button onclick="soundClick();" class="btn btn-default3" title="<?=$locale['send'];?>"><i class="fa fa-comment-o fa-2x"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div id="idEventLog" class="panel panel-msg" hidden>
                        <div class="panel-heading">
                            <strong>
                                <i class="fa fa-crosshairs" aria-hidden="true"></i> <?=$locale['tasks'];?>
                            </strong>

                            <button onclick="moreEventLog()" class="btn btn-default2 pull-right" title="<?=$locale['more'];?>"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
                        </div>
                        <div class="panel-body" id="idEventLogBody">
                        </div>
                    </div>

                    <div id="idAllUnitMsg" class="panel panel-msg" hidden>
                        <div class="panel-heading">
                            <strong>
                                <i class="fa fa-envelope-o" aria-hidden="true"></i> <?=$locale['all_messages'];?>
                            </strong>
                            <button onclick="moreUnitMsgOper()" class="btn btn-default2 pull-right" title="<?=$locale['more'];?>"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
                        </div>
                        <div class="panel-body" id="idAllUnitMsgBody">
                        </div>
                        <div class="panel-footer" id="idAllUnitMsgFooter">
                        </div>
                    </div>

                    <div id="idActiveUnit" class="panel panel-msg" hidden>
                        <div class="panel-heading">
                            <strong>
                                <i class="fa fa-users" aria-hidden="true"></i> <?=$locale['active_users'];?>
                            </strong>
                            <button onclick="moreActiveUnit()" class="btn btn-default2 pull-right" title="<?=$locale['more'];?>"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
                        </div>
                        <div class="panel-body" id="idActiveUnitBody">
                        </div>
                    </div>

                    <div id="idTargetList" class="panel panel-msg" hidden>
                        <div class="panel-heading">
                            <strong>
                                <i class="fa fa-flag-checkered" aria-hidden="true"></i> <?=$locale['targets'];?>
                            </strong>

                            <button onclick="moreTargetList()" class="btn btn-default2 pull-right" title="<?=$locale['more'];?>"><i class="fa fa-paperclip" aria-hidden="true"></i></button>
                        </div>
                        <div class="panel-body" id="idTargetListBody">
                        </div>
                    </div>

                    <div id="idOneUnitMsg" class="panel panel-msg" hidden>
                        <div class="panel-heading" id="idOneUnitMsgHeader">
                        </div>
                        <div class="panel-body" id="idOneUnitMsgBody">
                        </div>
                        <div class="panel-footer" id="idOneUnitMsgFooter">
                        </div>
                    </div>

                </div> <!-- #menubox -->

                <div class="pb-user">
                    <strong>
                        <i class="fa fa-user-circle" aria-hidden="true"></i> <?= $user->name; ?>
                    </strong>
                    <button onclick="soundClick(); $('#signOutModal').modal('show');" class="btn btn-default2 pull-right" title="<?=$locale['sign_out'];?>"><i class="fa fa-sign-out" aria-hidden="true"></i></button>
                    <button onclick="loadOperSetings()" class="btn btn-default2 pull-right" title="<?=$locale['profile'];?>"><i class="fa fa-sliders" aria-hidden="true"></i></button>
                </div>

                <div class="pb-sys text-center">
                    <button onclick="toggleSysMsg()" class="btn btn-default2 disabled" title="<?=$locale['system_message'];?>"><i class="fa fa-exclamation" aria-hidden="true"></i></button>
                    <button onclick="toggleGlobalMsg()" class="btn btn-default2 disabled" title="<?=$locale['global_message'];?>"><i class="fa fa-user-secret" aria-hidden="true"></i></button>

                    <button onclick="pbLoadAllUsersManage();" class="btn btn-default2" title="<?=$locale['users'];?>"><i class="fa fa-id-card-o" aria-hidden="true"></i></button>
                    <button onclick="pbOpenFolderOper()" class="btn btn-default2" title="<?=$locale['documents'];?>"><i class="fa fa-folder-open-o" aria-hidden="true"></i></button>
                    <button onclick="soundClick(); $('#accauntModal').modal('show');" class="btn btn-default2 disabled" title="<?=$locale['accounting'];?>"><i class="fa fa-bar-chart" aria-hidden="true"></i></button>
                    <button onclick="pbLoadSysSettings()" class="btn btn-default2 <?php if (bestRole($user->role) > 1) echo 'disabled'; ?>" title="<?=$locale['settings'];?>"><i class="fa fa-cog" aria-hidden="true"></i></button>
                    <button onclick="soundClick(); $('#helpModal').modal('show');" class="btn btn-default2" title="<?=$locale['help'];?>"><i class="fa fa-question" aria-hidden="true"></i></button>
                </div>

                <div class="pb-tact text-center">
                    <button onclick="toggleEventLog()" class="btn btn-default2" title="<?=$locale['tasks'];?>"><i class="fa fa-crosshairs fa-2x" aria-hidden="true"></i></button>
                    <button onclick="toggleAllUnitMsg()" class="btn btn-default2" title="<?=$locale['messages'];?>"><i class="fa fa-envelope-o fa-2x" aria-hidden="true"></i></button>
                    <button onclick="toggleActiveUnit()" class="btn btn-default2" title="<?=$locale['users'];?>"><i class="fa fa-users fa-2x" aria-hidden="true"></i></button>

                    <button onclick="toggleTargetList()" class="btn btn-default2" title="<?=$locale['targets'];?>"><i class="fa fa-flag-checkered fa-2x" aria-hidden="true"></i></button>
                    <button onclick="pbOpenOrderDialog()" class="btn btn-default2" title="<?=$locale['orders'];?>"><i class="fa fa-anchor fa-2x" aria-hidden="true"></i></button>
                </div>

                <div class="pb-set">
                    <button onclick="soundClick(); offAllMenu()" class="btn btn-default2" title="<?=$locale['main_menu'];?>"><i class="fa fa-home" aria-hidden="true"></i></button>
                    &nbsp;
                    <button id="idFSbutton" onclick="soundClick(); toggleFullScreen();" class="btn btn-default2 pull-right" title="<?=$locale['expand'];?>"><i class="fa fa-expand" aria-hidden="true"></i></button>
                    <button id="idSoundbutton" onclick="toggleSound()" class="btn btn-default2 pull-right" title="<?=$locale['mute'];?>"><i class="fa fa-volume-up" aria-hidden="true"></i></button>
                </div>

            </div> <!-- mapbox -->

        </div>
    </div>

    <div id="selfEditModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-sliders" aria-hidden="true"></i>
                        <?=$locale['profile'];?>.
                    </h4>
                </div>
                <div class="modal-body" id="selfEditContent">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                    <button onclick="saveOperSetings()" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
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
                    <?=$locale['do_you_want_to_exit_the_management_interface'];?>
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['cancel'];?></button>
                    <button onclick="window.location.pathname = '/app/logout.php';" class="btn btn-primary"><i class="fa fa-sign-out" aria-hidden="true"></i><?=$locale['sign_out'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="idCardModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        <i class="fa fa-id-card-o" aria-hidden="true"></i>
                        <?=$locale['user_management'];?>.
                        <button onclick="pbEditUser(0)" class="btn btn-default2"><i class="fa fa-user-plus" aria-hidden="true"></i></button>
                    </h4>
                </div>
                <div class="modal-body" id="idCardModalContent">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="userEditModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" id="userEditHeader">
                </div>
                <div class="modal-body" id="userEditContent">
                </div>
                <div class="modal-footer" id="userEditFooter">
                </div>
            </div>
        </div>
    </div>

    <div id="docsListModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        <i class="fa fa-folder-open" aria-hidden="true"></i>
                        <?=$locale['information'];?>.
                        <button onclick="pbCreateInfo()" class="btn btn-default2"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </h4>
                </div>
                <div class="modal-body" id="docsListContent">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="docViewModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="docViewHeader">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <?=$locale['file_name'];?>.
                        &nbsp; <button onclick="soundClick(); $('#docEditModal').modal('show');" class="btn btn-default2"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                    </h4>
                </div>
                <div class="modal-body" id="docViewContent">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="docEditModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="docEditHeader">
                </div>
                <div class="modal-body" id="docEditContent">
                </div>
                <div class="modal-footer" id="docEditFooter">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                    <button onclick="soundClick()" class="btn btn-success" title="<?=$locale['save'];?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div id="accauntModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                        <?=$locale['accounting'];?>.
                    </h4>
                </div>
                <div class="modal-body" id="accauntContent">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="helpModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-question" aria-hidden="true"></i>
                        <?=$locale['help'];?>.
                    </h4>
                </div>
                <div class="modal-body" id="helpContent">
                    <?=$localehelp;?>
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="settingsModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-cogs" aria-hidden="true"></i>
                        <?=$locale['settings'];?>.
                    </h4>
                </div>
                <div class="modal-body" id="settingsContent">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                    <button onclick="saveSysSettings()" class="btn btn-success" title="<?=$locale['save'];?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div id="cmdMonitorModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-anchor" aria-hidden="true"></i>
                        <?=$locale['orders'];?>.
                        <button onclick="pbEditOrderDialog(0)" class="btn btn-default2" title="<?=$locale['add_order'];?>"><i class="fa fa-plus" aria-hidden="true"><sub><i class="fa fa-tasks" aria-hidden="true"></i></sub></i></button>
                        <button onclick="pbEditChainOrderDialog(0)" class="btn btn-default2" title="<?=$locale['add_a_chain_of_orders'];?>"><i class="fa fa-plus" aria-hidden="true"><sub><i class="fa fa-link" aria-hidden="true"></i></sub></i></button>
                    </h4>
                </div>
                <div class="modal-body" id="cmdMonitorContent">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['close'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="cmdEditModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-anchor" aria-hidden="true"></i> <i class="fa fa-pencil" aria-hidden="true"></i>
                        <?=$locale['editing_an_order'];?>.
                    </h4>
                </div>
                <div class="modal-body" id="cmdEditContent">
                </div>
                <div class="modal-footer" id="cmdEditFooter">
                </div>
            </div>
        </div>
    </div>

    <div id="cmdChainEditModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-anchor" aria-hidden="true"></i> <i class="fa fa-pencil" aria-hidden="true"></i>
                        <?=$locale['editing_the_chain_of_orders'];?>.
                    </h4>
                </div>
                <div class="modal-body" id="cmdChainEditContent">
                </div>
                <div class="modal-footer" id="cmdChainEditFooter">
                </div>
            </div>
        </div>
    </div>

    <div id="targetEditModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-flag" aria-hidden="true"></i>
                        <?=$locale['editing_a_goal'];?>.
                    </h4>
                </div>
                <div class="modal-body">
                    <input id="idTargetEditInput" type="text" class="form-control" value="" placeholder="<?=$locale['target_name'];?>">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['cancel'];?></button>
                    <button onclick="saveTargetName()" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>

     <div id="targetDelModal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-flag" aria-hidden="true"></i>
                        <?=$locale['remove_target'];?>
                    </h4>
                </div>
                <div class="modal-body" id="targetDelConfirmBody">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['cancel'];?></button>
                    <button onclick="deleteOneTarget(globTargetId)" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> <?=$locale['remove'];?> </button>
                </div>
            </div>
        </div>
    </div>

    <div id="orderDelModal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-anchor" aria-hidden="true"></i>
                        <?=$locale['delete_the_order'];?>
                    </h4>
                </div>
                <div class="modal-body" id="orderDelConfirmBody">
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['cancel'];?></button>
                    <button onclick="deleteOneOrder(globOrderId)" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> <?=$locale['remove'];?> </button>
                </div>
            </div>
        </div>
    </div>

    <div id="cmdCancelModal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-stop-circle" aria-hidden="true"></i>
                        <?=$locale['canceling_an_order'];?>.
                    </h4>
                </div>
                <div class="modal-body" id="cmdCancelBody">
                    <?=$locale['do_you_want_to_cancel_all_orders_of_this_type_that_are_currently_being_performed'];?>
                </div>
                <div class="modal-footer">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['no'];?></button>
                    <button onclick="stopAllTasksOrder(globOrderId)" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i><?=$locale['yes'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="cmdOneCancelModal" class="modal fade">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-crosshairs" aria-hidden="true"></i>
                        <?=$locale['edit_task'];?>.
                    </h4>
                </div>
                <div class="modal-body" id="cmdOneCancelBody">
                </div>
                <div class="modal-footer" id="cmdOneCancelFooter">
                    <button onclick="soundClick()" class="btn btn-default" data-dismiss="modal"><?=$locale['no'];?></button>
                    <button onclick="stopAllTasksOrder(globOrderId)" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i><?=$locale['yes'];?></button>
                </div>
            </div>
        </div>
    </div>

    <div id="cmdStartModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-tasks" aria-hidden="true"></i>
                        <?=$locale['set_an_order'];?>.
                    </h4>
                </div>
                <div class="modal-body" id="cmdStartContent">
                </div>
                <div class="modal-footer" id="cmdStartFooter">
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

    <div id="msgConfirmTarget" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                    <h4 class="modal-title">
                        &nbsp;
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                        <?=$locale['confirmation'];?>.
                    </h4>
                </div>
                <div class="modal-body">
                    <?=$locale['change_the_position_of_the_goal'];?>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal"><?=$locale['cancel'];?></button>
                    <button onclick="flChDrTarget = true;" class="btn btn-default" data-dismiss="modal"><?=$locale['yes'];?></button>
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
    <script src="js/include.js"></script><script>pboper();</script>
</body>

</html>
