var buttonFS = document.getElementById('idFSbutton');
var buttonSound = document.getElementById('idSoundbutton');
var buttonMic = document.getElementById('idMicbutton');
var buttonVol = document.getElementById('idVolbutton');

var isSound = true;
var isMic = false;
var isVol = false;
var isPass = false;
var isMassRadio = false;
var isSysMsg = false;
var isGlobalMsg = false;
var isEventLog = false;
var isAllUnitMsg = false;
var isActiveUnit = false;
var isTargetList = false;
var isGlobWay = false;
var countMsgList = 10;
var globTargetId = 0;
var globOrderId = 0;
var globWayUserId = 0;
var nameInterface = "oper";

function toggleFullScreen() {
    if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement) {
        onFullScreen();
    } else {
        offFullScreen();
    }
}

function onFullScreen() {
    if (document.documentElement.requestFullscreen) {
        document.documentElement.requestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
        document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
        document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
    }
    buttonFS.innerHTML = '<i class="fa fa-compress" aria-hidden="true"></i>';
    buttonFS.title = locale.compress;
}

function offFullScreen() {
    if (document.cancelFullScreen) {
        document.cancelFullScreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.webkitCancelFullScreen) {
        document.webkitCancelFullScreen();
    }
    buttonFS.innerHTML = '<i class="fa fa-expand" aria-hidden="true"></i>';
    buttonFS.title = locale.expand;
}

function soundClick() {
    if (isSound) {
        var audio = new Audio();
        audio.src = 'sound/click.mp3';
        audio.autoplay = true;
    }
}

function toggleSound() {
    if (isSound) {
        isSound = false;
        buttonSound.innerHTML = '<i class="fa fa-volume-off" aria-hidden="true"></i>';
        buttonSound.title = locale.on_sound;
    }
    else {
        isSound = true;
        buttonSound.innerHTML = '<i class="fa fa-volume-up" aria-hidden="true"></i>';
        buttonSound.title = locale.mute;
        soundClick();
    }
}

function toggleMic() {
    if (isMic) {
        offMic();
    }
    else {
        onMic();
    }
}

function onMic() {
    isMic = true;
    buttonMic.innerHTML = '<i class="fa fa-toggle-on" aria-hidden="true"></i>';
    buttonMic.title = locale.off_microphone;
}

function offMic() {
    isMic = false;
    buttonMic.innerHTML = '<i class="fa fa-toggle-off" aria-hidden="true"></i>';
    buttonMic.title = locale.on_microphone;
}

function toggleVolume() {
    if (isVol) {
        offVolume();
    }
    else {
        onVolume();
    }
}

function onVolume() {
    isVol = true;
    buttonVol.innerHTML = '<i class="fa fa-toggle-on" aria-hidden="true"></i>';
    buttonVol.title = locale.mute;
}

function offVolume() {
    isVol = false;
    buttonVol.innerHTML = '<i class="fa fa-toggle-off" aria-hidden="true"></i>';
    buttonVol.title = locale.on_sound;
}

function toggleMassRadio() {
    if (isMassRadio) {
        offMassRadio();
    }
    else {
        onMassRadio();
    }
}

function onMassRadio() {
    soundClick();
    isMassRadio = true;
    $("#idMassRadio").show(1000);
}

function offMassRadio() {
    soundClick();
    offMic();
    offVolume();
    isMassRadio = false;
    $("#idMassRadio").hide(1000);
}

function toggleSysMsg() {
    soundClick();
    if (isSysMsg) {
        onHomeMenu();
        isSysMsg = false;
        $("#idSysMsg").hide(1000);
    }
    else {
        offAllMenu();
        offHomeMenu();
        isSysMsg = true;
        $("#idSysMsg").show(1000);
    }
}

function toggleGlobalMsg() {
    soundClick();
    if (isGlobalMsg) {
        onHomeMenu();
        isGlobalMsg = false;
        $("#idGlobalMsg").hide(1000);
    }
    else {
        offAllMenu();
        offHomeMenu();
        isGlobalMsg = true;
        $("#idGlobalMsg").show(1000);
    }
}

function onHomeMenu() {
    $("#idHome").show(1000);
}

function offHomeMenu() {
    $("#idHome").hide(1000);
}

function toggleEventLog() {
    soundClick();
    countMsgList = 10;
    if (isEventLog) {
        onHomeMenu();
        isEventLog = false;
        $("#idEventLog").hide(1000);
    }
    else {
        offAllMenu();
        offHomeMenu();
        isEventLog = true;
        str1 = "oper=load_tasks_oper&value="+countMsgList;
        $("#idEventLogBody").load("app/ajax.php", str1);
        $("#idEventLog").show(1000);
    }
}

function moreEventLog() {
    soundClick();
    countMsgList = countMsgList + 10;
    $("#idEventLogBody").html("");
    str1 = "oper=load_tasks_oper&value="+countMsgList;
    $("#idEventLogBody").load("app/ajax.php", str1);
    $("#idEventLog").show(1000);
}

function toggleAllUnitMsg() {
    soundClick();
    if (isAllUnitMsg) {
        onHomeMenu();
        isAllUnitMsg = false;
        $("#idAllUnitMsg").hide(1000);
    }
    else {
        offAllMenu();
        offHomeMenu();
        isAllUnitMsg = true;
        countMsgList = 10;
        loadUnitMsgOper();
    }
}

function toggleActiveUnit() {
    soundClick();
    countMsgList = 10;
    if (isActiveUnit) {
        onHomeMenu();
        isActiveUnit = false;
        $("#idActiveUnit").hide(1000);
    }
    else {
        offAllMenu();
        offHomeMenu();
        isActiveUnit = true;
        str1 = "oper=load_active_unit&value="+countMsgList;
        $("#idActiveUnitBody").load("app/ajax.php", str1);
        $("#idActiveUnit").show(1000);
    }
}

function toggleTargetList() {
    soundClick();
    countMsgList = 10;
    globTargetId = 0;
    if (isTargetList) {
        onHomeMenu();
        isTargetList = false;
        $("#idTargetList").hide(1000);
    }
    else {
        $("#idTargetListBody").html("");
        offAllMenu();
        offHomeMenu();
        isTargetList = true;
        str1 = "oper=load_all_target_body&value="+countMsgList;
        $("#idTargetListBody").load("app/ajax.php", str1);
        $("#idTargetList").show(1000);
        addManyTargets();
    }
}

function moreTargetList() {
    soundClick();
    countMsgList = countMsgList + 10;
    $("#idTargetListBody").html("");
    str1 = "oper=load_all_target_body&value="+countMsgList;
    $("#idTargetListBody").load("app/ajax.php", str1);
    $("#idTargetList").show(1000);
}

function moreActiveUnit() {
    soundClick();
    countMsgList = countMsgList + 10;
    $("#idActiveUnitBody").html("");
    str1 = "oper=load_active_unit&value="+countMsgList;
    $("#idActiveUnitBody").load("app/ajax.php", str1);
    $("#idActiveUnit").show(1000);
}

function panToUnit(idUnit, eff = true) {
    soundClick();
    if (eff == true) {
        hideAllModalDiv();
        offAllMenu();
        offHomeMenu();
        loadOneUnitMsgOper(idUnit);
    } else {
        if (isGlobWay == true) {
            hideWay();
        }
    }
    $.post("app/ajax.php", { oper: "unit_coord", value: idUnit }, function(result) {
        unitCircle.removeFrom(map);
        ipathline.removeFrom(map);
        eval(result);
    } );
}

function panToTarget(idTarget) {
    soundClick();
    hideAllModalDiv();
    $.post("app/ajax.php", { oper: "target_coord", value: idTarget }, function(result) {eval(result);} );
}

function showWayUnit(idUnit) {
    soundClick();
    globWayUserId = idUnit;
    isGlobWay = true;
    showWayGlID();
}

function showWayGlID() {
    deleteAllTargets();
    deleteAllUnits();
    unitCircle.removeFrom(map);
    ipathline.removeFrom(map);
    $.post("app/ajax.php", { oper: "show_way", value: globWayUserId }, function(result) {
        eval(result);
        var wayPlanCoordinates = [];
        for (var s in resway) {
            dotdata = resway[s];
            var waymarker = L.marker({lat: Number(dotdata.lat), lng: Number(dotdata.lng)}, {
                icon: histPoMarker,
                title: dotdata.time,
            }).addTo(map);
            wayUnit.push(waymarker);
            wayPlanCoordinates.push({lat: Number(dotdata.lat), lng: Number(dotdata.lng)});
        }
        wayPath = L.polyline(wayPlanCoordinates, {
            geodesic: true,
            color: '#AA00FF',
            opacity: 1.0,
            weight: 3
        }).addTo(map);;
    } );
}

function hideWay() {
    isGlobWay = false;
    globWayUserId = 0;
    for (var i = 0; i < wayUnit.length; i++) {
        wayUnit[i].removeFrom(map);
    }
    wayUnit = [];
    wayPath.removeFrom(map);
    addManyUnits();
    addManyTargets();
}

function deleteOneTargetConfirm(idTarget, nameTarget) {
    soundClick();
    globTargetId = idTarget;
    $('#targetDelConfirmBody').html(nameTarget);
    $('#targetDelModal').modal('show');
}

function deleteOneTarget(idTarget) {
    soundClick();
    $("#idTargetListBody").html("");
    $('#targetDelModal').modal('hide');
    $.post("app/ajax.php", { oper: "del_one_target", value: idTarget }, function(result) {
        eval(result);
        str1 = "oper=load_all_target_body&value="+countMsgList;
        $("#idTargetListBody").load("app/ajax.php", str1);
        $("#idTargetList").show(1000);
        addManyTargets();
    } );
}

function editTarget(idTarget, nameTarget) {
    soundClick();
    globTargetId = idTarget;
    $('#idTargetEditInput').val(nameTarget);
    $('#targetEditModal').modal('show');
}

function saveTargetName() {
    soundClick();
    nameTargetNew = $('#idTargetEditInput').val();
    $('#targetEditModal').modal('hide');
    $.post("app/ajax.php", { oper: "save_name_target", value: globTargetId, namenew: nameTargetNew }, function(result) {
        eval(result);
        str1 = "oper=load_all_target_body&value="+countMsgList;
        $("#idTargetListBody").load("app/ajax.php", str1);
        $("#idTargetList").show(1000);
        addManyTargets();
    } );
}

function hideAllModalDiv() {
    $("#selfEditModal").modal('hide');
    $("#signOutModal").modal('hide');
    $("#idCardModal").modal('hide');
    $("#userEditModal").modal('hide');
    $("#docsListModal").modal('hide');
    $("#docViewModal").modal('hide');
    $("#docEditModal").modal('hide');
    $("#accauntModal").modal('hide');
    $("#helpModal").modal('hide');
    $("#settingsModal").modal('hide');
    $("#cmdMonitorModal").modal('hide');
    $("#cmdEditModal").modal('hide');
    $("#cmdCancelModal").modal('hide');
    $("#cmdStartModal").modal('hide');
}

function offAllMenu() {
    isSysMsg = false;
    $("#idSysMsg").hide(1000);
    isGlobalMsg = false;
    $("#idGlobalMsg").hide(1000);
    isEventLog = false;
    $("#idEventLog").hide(1000);
    isAllUnitMsg = false;
    $("#idAllUnitMsg").hide(1000);
    isActiveUnit = false;
    $("#idActiveUnit").hide(1000);
    isTargetList = false;
    $("#idTargetList").hide(1000);
    $("#idOneUnitMsg").hide(1000);
    unitCircle.removeFrom(map);
    ipathline.removeFrom(map);
    onHomeMenu();
    if (isGlobWay == true) {
        hideWay();
    }
}

function pbAlert(pbMsg, sd = false) {
    $('#idalert').html(pbMsg);
    if (sd == true) {
        soundClick();
    }
    $('#msgAlert').modal('show');
}

function pbConfirmTarget() {
    soundClick();
    $('#msgConfirmTarget').modal('show');
}

function viewOrderMsgs(idOrder) {
    $('#idalert').html("");
    soundClick();
    str1 = "oper=view_order_msgs&value="+idOrder;
    $("#idalert").load("app/ajax.php", str1);
    $('#msgAlert').modal('show');
}

function loadOperSetings() {
    soundClick();
    $("#selfEditContent").html("");
    $('#selfEditModal').modal('show');
    str1 = "oper=load_sets";
    $("#selfEditContent").load("app/ajax.php", str1);
}

function saveOperSetings() {
    soundClick();
    fidpassword = $("#idpassword").val();
    fidemail = $("#idemail").val();
    fidphone = $("#idphone").val();
    fidfname = $("#idfname").val();
    fidaddress = $("#idaddress").val();
    fidcomment = $("#idcomment").val();
    $("#idalert").html("");
    $('#selfEditModal').modal('hide');
    str1 = "oper=save_sets&fidpassword="+fidpassword+"&fidemail="+fidemail+"&fidphone="+fidphone+"&fidfname="+fidfname+"&fidaddress="+fidaddress+"&fidcomment="+fidcomment;
    $("#idalert").load("app/ajax.php", str1);
    $('#msgAlert').modal('show');
}

function togglePassEdit() {
    soundClick();
    if (isPass) {
        isPass = false;
        $("#idPassbutton").html('<i class="fa fa-pencil fa-2x" aria-hidden="true"></i>');
        $('#idpassword').prop('disabled',true);
    } else {
        isPass = true;
        $("#idPassbutton").html('<i class="fa fa-ban fa-2x" aria-hidden="true"></i>');
        $('#idpassword').prop('disabled',false);
    }
}

function pbOpenFolderOper() {
    soundClick();
    $("#docsListContent").html("");
    $('#docsListModal').modal('show');
    str1 = "oper=list_docs";
    $("#docsListContent").load("app/ajax.php", str1);
}

function pbOpenInfoOper(infoNumber) {
    soundClick();
    $("#docViewContent").html("");
    $('#docViewModal').modal('show');
    str1 = "oper=load_doc_name&value="+infoNumber;
    $("#docViewHeader").load("app/ajax.php", str1);
    str1 = "oper=load_doc_body&value="+infoNumber;
    $("#docViewContent").load("app/ajax.php", str1);
}

function pbEditInfoOper(infoNumber) {
    soundClick();
    $("#docEditHeader").html("");
    $("#docEditContent").html("");
    $("#docEditFooter").html("");
    $('#docEditModal').modal('show');
    str1 = "oper=load_doc_name_edit&value="+infoNumber;
    $("#docEditHeader").load("app/ajax.php", str1);
    str1 = "oper=load_doc_body_edit&value="+infoNumber;
    $("#docEditContent").load("app/ajax.php", str1);
    str2 = '<button onclick="soundClick()" class="btn btn-default" data-dismiss="modal">'+locale.close+'</button> <button onclick="pbSaveInfo('+infoNumber+')" class="btn btn-success" title="'+locale.save+'"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>';
    $("#docEditFooter").html(str2);
}

function pbTrashInfo(infoNumber) {
    soundClick();
    $("#docsListContent").html("");
    str1 = "oper=trash_doc&value="+infoNumber;
    $("#docsListContent").load("app/ajax.php", str1);
}

function pbCreateInfo() {
    soundClick();
    $("#docEditHeader").html("");
    $("#docEditContent").html("");
    $("#docEditFooter").html("");
    str1 = '<button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button> <h4 class="modal-title"> &nbsp; <i class="fa fa-pencil" aria-hidden="true"></i> &nbsp;<input type="text" id="idDocTextHeader" class="form-control" style="display: inline; width:90%;" value="" placeholder="'+locale.file_name+'"> </h4>';
    $("#docEditHeader").html(str1);
    str2 = '<div class="form-group input-group"> <span class="input-group-addon"> <i class="fa fa-file-text-o fa-2x"></i> </span> <textarea id="idDocTextBody" class="form-control" rows="23"></textarea> </div>';
    $("#docEditContent").html(str2);
    str3 = '<button onclick="soundClick()" class="btn btn-default" data-dismiss="modal">'+locale.close+'</button> <button onclick="pbSaveInfo(0)" class="btn btn-success" title="'+locale.save+'"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>';
    $("#docEditFooter").html(str3);
    $('#docEditModal').modal('show');
}

function pbSaveInfo(infoNumber) {
    soundClick();
    fidtextheader = $("#idDocTextHeader").val();
    fidtextbody = $("#idDocTextBody").val();
    $("#idalert").html("");
    $('#docEditModal').modal('hide');
    $.post("app/ajax.php", { oper: "save_doc", value: infoNumber, fidtextheader: fidtextheader, fidtextbody: fidtextbody }, function(result) {$("#idalert").html(result);} );
    $('#msgAlert').modal('show');
    str1 = "oper=load_doc_name&value="+infoNumber;
    $("#docViewHeader").load("app/ajax.php", str1);
    str1 = "oper=load_doc_body&value="+infoNumber;
    $("#docViewContent").load("app/ajax.php", str1);
    str1 = "oper=list_docs";
    $("#docsListContent").load("app/ajax.php", str1);
}

function pbLoadSysSettings() {
    soundClick();
    $("#settingsContent").html("");
    $('#settingsModal').modal('show');
    str1 = "oper=load_sysset";
    $("#settingsContent").load("app/ajax.php", str1);
}

function toggleSysSet(tog) {
    soundClick();
    if (tog == 1) {
        if ($("#chpbtitle").prop("checked")) {
            $("#idsyspbtitle").prop("disabled", false);
        } else {
            $("#idsyspbtitle").prop("disabled", true);
        }
    }
    if (tog == 2) {
        if ($("#chauhead").prop("checked")) {
            $("#idsysauhead").prop("disabled", false);
        } else {
            $("#idsysauhead").prop("disabled", true);
        }
    }
    if (tog == 3) {
        if ($("#chauinfo").prop("checked")) {
            $("#idsysauinfo").prop("disabled", false);
        } else {
            $("#idsysauinfo").prop("disabled", true);
        }
    }
}

function saveSysSettings() {
    soundClick();
    cpbtitle = 'false';
    fpbtitle = $("#idsyspbtitle").val();
    cauhead = 'false';
    fauhead = $("#idsysauhead").val();
    cauinfo = 'false';
    fauinfo = $("#idsysauinfo").val();
    cpbsignup = 'false';
    cpbforgot = 'false';
    fpbaccuracy = 0;
    fpblanguage = 'eng';
    fpblat = 0;
    fpblng = 0;
    if ($("#chpbtitle").prop("checked")) {
        cpbtitle = 'true';
        fpbtitle = $("#idsyspbtitle").val();
    }
    if ($("#chauhead").prop("checked")) {
        cauhead = 'true';
        fauhead = $("#idsysauhead").val();
    }
    if ($("#chauinfo").prop("checked")) {
        cauinfo = 'true';
        fauinfo = $("#idsysauinfo").val();
    }
    if ($("#chpbsignup").prop("checked")) {
        cpbsignup = 'true';
    }
    if ($("#chpbforgot").prop("checked")) {
        cpbforgot = 'true';
    }
    fpbaccuracy = $("#idsysaccuracy").val();
    fsystimeout = $("#idsystimeout").val();
    fpblanguage = $("#idsyslanguage").val();
    fpblat = $("#idsyslat").val();
    fpblng = $("#idsyslng").val();
    $('#settingsModal').modal('hide');
    $.post("app/ajax.php", { oper: "save_sysset", cpbtitle: cpbtitle, fpbtitle: fpbtitle, cauhead: cauhead, fauhead: fauhead, cauinfo: cauinfo, fauinfo: fauinfo, cpbsignup: cpbsignup, cpbforgot: cpbforgot, fpbaccuracy: fpbaccuracy, fsystimeout: fsystimeout, fpblanguage: fpblanguage, fpblat: fpblat, fpblng: fpblng }, function(result) {
        eval(result);
    } );
}

function loadUnitMsgOper() {
    $("#idAllUnitMsgBody").html("");
    $("#idAllUnitMsgFooter").html("");
    str1 = "oper=load_all_msgs&value="+countMsgList;
    $("#idAllUnitMsgBody").load("app/ajax.php", str1);
    str2 = "oper=load_footer_fam";
    $("#idAllUnitMsgFooter").load("app/ajax.php", str2);
    $("#idAllUnitMsg").show(1000);
}

function moreUnitMsgOper() {
    soundClick();
    countMsgList = countMsgList + 10;
    loadUnitMsgOper();
}

function sendOperMsgAll() {
    soundClick();
    strin = $("#inputOperMsgAll").val();
    str1 = "oper=send_msg_all&textmsg="+strin;
    $("#idAllUnitMsgBody").load("app/ajax.php", str1);
    $("#inputOperMsgAll").val("");
    loadUnitMsgOper();
}

function startOrder(idUnit, idCmd, sRef = false) {
    soundClick();
    isActiveUnit = false;
    $("#idActiveUnit").hide(1000);
    str1 = "oper=load_one_cmd"+"&unitid="+idUnit+"&orderid="+idCmd;
    $("#cmdStartContent").load("app/ajax.php", str1);
    str2 = '<button onclick="soundClick()" class="btn btn-default" data-dismiss="modal">'+locale.cancel+'</button> <button onclick="startOrderOK('+idUnit+', '+idCmd+', '+sRef+')" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> '+locale.ok+'</button>';
    $("#cmdStartFooter").html(str2);
    $('#cmdStartModal').modal('show');
}

function startOrderOK(idUnit, idCmd, sRef) {
    soundClick();
    fseltarget = $("#idSelectTarget").val();
    if (fseltarget == undefined) {
        fseltarget = 0;
    }
    $.post("app/ajax.php", { oper: "save_task", unitid: idUnit, cmdid: idCmd, fseltarget: fseltarget }, function(result) {
        if (sRef == true) {
            str1 = "oper=load_tasks_oper&value="+countMsgList;
            $("#idEventLogBody").load("app/ajax.php", str1);
        } else {
            if (idUnit == 0) {
                loadUnitMsgOper();
            } else {
                loadOneUnitMsgOper(idUnit);
            }
        }
    } );
    $('#cmdStartModal').modal('hide');
}

function loadOneUnitMsgOper(idUnit) {
    $("#idOneUnitMsgHeader").html("");
    $("#idOneUnitMsgBody").html("");
    $("#idOneUnitMsgFooter").html("");
    str1 = "oper=load_unit_msgs_header"+"&unitid="+idUnit;
    $("#idOneUnitMsgHeader").load("app/ajax.php", str1);
    str2 = "oper=load_unit_msgs_body&value="+countMsgList+"&unitid="+idUnit;
    $("#idOneUnitMsgBody").load("app/ajax.php", str2);
    str3 = "oper=load_unit_msgs_footer"+"&unitid="+idUnit;
    $("#idOneUnitMsgFooter").load("app/ajax.php", str3);
    $("#idOneUnitMsg").show(1000);
}

function moreOneUnitMsgOper(idUnit) {
    soundClick();
    countMsgList = countMsgList + 10;
    loadOneUnitMsgOper(idUnit);
}

function sendOperMsgOne(idUnit) {
    soundClick();
    strin = $("#inputOperMsgOne").val();
    str1 = "oper=send_msg_one"+"&unitid="+idUnit+"&textmsg="+strin;
    $("#idOneUnitMsgBody").load("app/ajax.php", str1);
    $("#inputOperMsgOne").val("");
    loadOneUnitMsgOper(idUnit);
}

function pbOpenOrderDialog() {
    soundClick();
    $("#cmdMonitorContent").html("");
    str1 = "oper=load_orders_set";
    $("#cmdMonitorContent").load("app/ajax.php", str1);
    $('#cmdMonitorModal').modal('show');
}

function deleteOneOrderConfirm(idOrder, nameOrder) {
    soundClick();
    globOrderId = idOrder;
    $('#orderDelConfirmBody').html(nameOrder);
    $('#orderDelModal').modal('show');
}

function deleteOneOrder(idOrder) {
    soundClick();
    $("#cmdMonitorContent").html("");
    $('#orderDelModal').modal('hide');
    $.post("app/ajax.php", { oper: "del_one_order", value: idOrder }, function(result) {
        str1 = "oper=load_orders_set";
        $("#cmdMonitorContent").load("app/ajax.php", str1);
        $('#cmdMonitorModal').modal('show');
    } );
}

function stopAllTasksOrderConfirm(idOrder, nameOrder) {
    soundClick();
    globOrderId = idOrder;
    $('#cmdCancelModal').modal('show');
}

function stopAllTasksOrder(idOrder) {
    soundClick();
    $('#cmdCancelModal').modal('hide');
    $.post("app/ajax.php", { oper: "stop_tasks", value: idOrder }, function(result) {
        pbAlert(result);
    } );
}

function stopOneTaskConfirm(idTask, idSelector) {
    soundClick();
    if (idSelector == 2) {
        $('#cmdOneCancelBody').html(locale.confirm_the_task);
    }
    if (idSelector == 3) {
        $('#cmdOneCancelBody').html(locale.cancel_the_task);
    }
    if (idSelector == 4) {
        $('#cmdOneCancelBody').html(locale.mark_the_task_as_failed);
    }
    str1 = '<button onclick="soundClick()" class="btn btn-default" data-dismiss="modal">'+locale.no+'</button><button onclick="stopOneTask('+idTask+', '+idSelector+')" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i>'+locale.yes+'</button>';
    $('#cmdOneCancelFooter').html(str1);
    $('#cmdOneCancelModal').modal('show');
}

function stopOneTask(idTask, idSelector) {
    soundClick();
    $("#cmdOneCancelBody").html("");
    $('#cmdOneCancelFooter').html("");
    $('#cmdOneCancelModal').modal('hide');
    $.post("app/ajax.php", { oper: "stop_onetask", idtask: idTask, idselector: idSelector }, function(result) {
        str1 = "oper=load_tasks_oper&value="+countMsgList;
        $("#idEventLogBody").load("app/ajax.php", str1);
        $("#idEventLog").show(1000);
        pbAlert(result);
    } );
}

function pbEditOrderDialog(idOrder) {
    soundClick();
    $("#cmdEditContent").html("");
    str1 = "oper=load_order_one&value="+idOrder;
    $("#cmdEditContent").load("app/ajax.php", str1);
    str2 = '<button onclick="soundClick()" class="btn btn-default" data-dismiss="modal">'+locale.close+'</button><button onclick="saveOrderOne('+idOrder+')" class="btn btn-success" title="'+locale.save+'"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>';
    $('#cmdEditFooter').html(str2);
    $('#cmdEditModal').modal('show');
}

function saveOrderOne(idOrder) {
    soundClick();
    var fidnameorder = $("#idNameOrder").val();
    var foptionstypecmd = $('input[name=optionsTypeCmd]:checked').val();
    var foptionsradiostarget = $('input[name=optionsRadiosTarget]:checked').val();
    var fidordermsgstart = $("#idOrderMsgStart").val();
    var fidordermsgfinish = $("#idOrderMsgFinish").val();
    $.post("app/ajax.php", { oper: "save_order_one", value: idOrder, fidnameorder: fidnameorder, foptionstypecmd: foptionstypecmd, foptionsradiostarget: foptionsradiostarget, fidordermsgstart: fidordermsgstart, fidordermsgfinish: fidordermsgfinish }, function(result) {
        $('#cmdEditModal').modal('hide');
        str1 = "oper=load_orders_set";
        $("#cmdMonitorContent").load("app/ajax.php", str1);
        $('#cmdMonitorModal').modal('show');
    } );
}

function pbEditChainOrderDialog(idOrder) {
    soundClick();
    $("#cmdChainEditContent").html("");
    $.post("app/ajax.php", { oper: "load_targets_all_json", value: idOrder }, function(result) {
        eval("restargets = "+result);
        $.post("app/ajax.php", { oper: "load_orderchain_one_json", value: idOrder }, function(result2) {
            eval("reschain = "+result2);
            $.post("app/ajax.php", { oper: "load_orders_all_json" }, function(result3) {
                eval("resorders = "+result3);
                paintChainEditDialog(idOrder);
            } );
        } );
    } );
    str2 = '<button onclick="soundClick()" class="btn btn-default" data-dismiss="modal">'+locale.close+'</button><button onclick="saveChainOrderOne('+idOrder+')" class="btn btn-success" title="'+locale.save+'"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>';
    $('#cmdChainEditFooter').html(str2);
    $('#cmdChainEditModal').modal('show');
}

function paintChainEditDialog(idOrder) {
    var echores = ' ';
    if (idOrder == 0) {
        echores += '<h4>'+locale.a_new_chain_of_orders+'</h4>';
    }
    echores += '<div class="form-group input-group"><span class="input-group-addon" style="padding-left: 7px; padding-right: 7px;"><i class="fa fa-link fa-2x" title="'+locale.the_name_of_the_chain_of_orders+'"></i></span><input type="text" id="idNameOrderChain" class="form-control" maxlength="40" value="'+reschain.namechain+'" placeholder="'+locale.the_name_of_the_chain_of_orders+'"></div><br>';
    echores += ' ';
    if (reschain.typechain == 'one') {
        echores += '<div class="form-group"><label>'+locale.type_of_orders_chain+': </label> <label class="radio-inline"> <input type="radio" name="optionsTypeCmdChain" value="one" checked> <i class="fa fa-chain-broken" aria-hidden="true"></i> '+locale.one_time+'</label><label class="radio-inline"><input type="radio" name="optionsTypeCmdChain" value="cyclic"> <i class="fa fa-exchange" aria-hidden="true"></i> '+locale.cyclic+'</label></div><br>';
    } else {
        echores += '<div class="form-group"><label>'+locale.type_of_orders_chain+': </label> <label class="radio-inline"> <input type="radio" name="optionsTypeCmdChain" value="one"> <i class="fa fa-chain-broken" aria-hidden="true"></i> '+locale.one_time+'</label><label class="radio-inline"><input type="radio" name="optionsTypeCmdChain" value="cyclic" checked> <i class="fa fa-exchange" aria-hidden="true"></i> '+locale.cyclic+'</label></div><br>';
    }
    echores += ' ';
    if (Array.isArray(reschain.chain)) {
        echores += '<div id="arrChainEdit"></div>';
        var temp_s = 0;
    } else {
        echores += '<div id="arrChainEdit">';
        var temp_s = 0;
        for (var s in reschain.chain) {
            echores += '<div class="panel panel-default"><div class="panel-body"><i class="fa fa-tasks" aria-hidden="true"></i> '+reschain.chain[s].name+'&nbsp;';
            if (reschain.chain[s].typeord == 'one') {
                echores += '<i class="fa fa-sticky-note-o" aria-hidden="true" title="'+locale.one_time_order+'"></i> ';
            } else {
                echores += '<i class="fa fa-clone" aria-hidden="true" title="'+locale.standing_order+'"></i> ';
            }
            if (reschain.chain[s].target == 'yes') {
                echores += '<i class="fa fa-flag-o" aria-hidden="true" title="'+locale.performed_with_target_designation+'"></i> ';
                echores += '<button onclick="viewOrderMsgs('+reschain.chain[s].id+')" class="btn btn-default2"><i class="fa fa-info" aria-hidden="true"></i></button>';
                echores += ' &nbsp; <i class="fa fa-flag-checkered" aria-hidden="true"></i> ';
                echores += '<select name="optionsTargetIn'+s+'" onchange="memSelectTargetOnChain('+s+')" style="color: black;">';
                for (var s2 in restargets) {
                    if (restargets[s2].id == reschain.chain[s].targetid) {
                        echores += '<option value="'+restargets[s2].id+'" selected>'+restargets[s2].name+'</option>';
                    } else {
                        echores += '<option value="'+restargets[s2].id+'">'+restargets[s2].name+'</option>';
                    }
                }
                echores += '</select>';
            } else {
                echores += '<i class="fa fa-circle-o" aria-hidden="true" title="'+locale.will_be_performed_without_target_designation+'"></i> ';
                echores += '<button onclick="viewOrderMsgs('+reschain.chain[s].id+')" class="btn btn-default2"><i class="fa fa-info" aria-hidden="true"></i></button>';
            }
            echores += ' ';
            echores += '<button onclick="minusOrderFromChain('+s+', '+reschain.chain[s].id+')" class="btn btn-default2 pull-right"><i class="fa fa-minus" aria-hidden="true"></i></button></div></div>';
            echores += ' ';
            temp_s = s;
        }
        echores += '</div>';
    }
    if (Array.isArray(resorders)) {
        echores += '<div class="form-group input-group"><span class="input-group-addon"><label><i class="fa fa-link" aria-hidden="true"></i> '+locale.there_are_no_orders_to_add_to_the_chain+'</label></span></div><br>';
    } else {
        echores += '<div class="form-group input-group"><span class="input-group-addon"><i class="fa fa-link" aria-hidden="true"></i></span><select name="optionsPlusOrderInChain" onchange="soundClick()" class="form-control">';
        for (var s1 in resorders) {
            echores += '    <option value="'+resorders[s1].id+'">'+resorders[s1].name+'</option>'
        }
        echores += '</select><span class="input-group-btn"><button onclick="plusOrderInChain('+temp_s+', '+idOrder+')" class="btn btn-default3"><i class="fa fa-plus" aria-hidden="true" style="margin-bottom: 8px; margin-top: 7px;"></i></button></span></div><br>';
    }
    echores += ' ';
    $("#cmdChainEditContent").html(echores);
}

function saveChainOrderOne(idOrder) {
    soundClick();
    reschain.namechain = $("#idNameOrderChain").val();
    reschain.typechain = $('input[name=optionsTypeCmdChain]:checked').val();
    var qryStr = JSON.stringify(reschain);
    $.post("app/ajax.php", { oper: "save_chain_one_json", value: idOrder, chainjson: qryStr }, function(result) {
        $('#cmdChainEditModal').modal('hide');
        str1 = "oper=load_orders_set";
        $("#cmdMonitorContent").load("app/ajax.php", str1);
        $('#cmdMonitorModal').modal('show');
    } );
}

function minusOrderFromChain(sLink, idOrder) {
    soundClick();
    reschain.namechain = $("#idNameOrderChain").val();
    reschain.typechain = $('input[name=optionsTypeCmdChain]:checked').val();
    delete reschain.chain[sLink];
    paintChainEditDialog(idOrder);
}

function plusOrderInChain(sLink, idOrder) {
    soundClick();
    reschain.namechain = $("#idNameOrderChain").val();
    reschain.typechain = $('input[name=optionsTypeCmdChain]:checked').val();
    var foptionsPlusOrderInChain = $('select[name=optionsPlusOrderInChain]').val();
    if (sLink == 0) {
        reschain.chain = {"1": { id: foptionsPlusOrderInChain, name: resorders[foptionsPlusOrderInChain].name, typeord: resorders[foptionsPlusOrderInChain].typeord, target: resorders[foptionsPlusOrderInChain].target, targetid: restargets[1].id}};
    } else {
        sLink = sLink + 1;
        reschain.chain[sLink] = { id: foptionsPlusOrderInChain, name: resorders[foptionsPlusOrderInChain].name, typeord: resorders[foptionsPlusOrderInChain].typeord, target: resorders[foptionsPlusOrderInChain].target, targetid: restargets[1].id};
    }
    paintChainEditDialog(idOrder);
}

function memSelectTargetOnChain(sLink) {
    soundClick();
    var swLink = 'select[name=optionsTargetIn'+sLink+']';
    var targid = $(swLink).val();
    reschain.chain[sLink].targetid = targid;
}

function pbLoadAllUsersManage() {
    soundClick();
    $("#idCardModalContent").html("");
    str1 = "oper=load_users_manage";
    $("#idCardModalContent").load("app/ajax.php", str1);
    $('#idCardModal').modal('show');
}

function pbEditUser(idUnit) {
    soundClick();
    if (idUnit == 0) {
        strHead = '<button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button><h4 class="modal-title"> &nbsp; <i class="fa fa-user" aria-hidden="true"></i> '+locale.new_user+'. </h4>';
        $("#userEditHeader").html(strHead);
    } else {
        strHead = '<button onclick="soundClick()" class="btn btn-default2 pull-right" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button><h4 class="modal-title"> &nbsp; <i class="fa fa-user" aria-hidden="true"></i> '+locale.edit_user+'. <button onclick="panToUnit('+idUnit+')" class="btn btn-default2"><i class="fa fa-eye" aria-hidden="true"></i></button> &nbsp; &nbsp;<button onclick="pbDelUser('+idUnit+')" class="btn btn-default2"><i class="fa fa-ban" aria-hidden="true"></i></button></h4>';
        $("#userEditHeader").html(strHead);
    }
    str1 = 'oper=load_user_data&value='+idUnit;
    $("#userEditContent").load("app/ajax.php", str1);
    strFooter = '<button onclick="soundClick()" class="btn btn-default" data-dismiss="modal">'+locale.close+'</button> <button onclick="saveUserSetings()" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>';
    $("#userEditFooter").html(strFooter);
    $('#userEditModal').modal('show');
}

function pbDelUser(idUnit) {
    soundClick();
    $.post("app/ajax.php", { oper: "delete_user", value: idUnit }, function(result) {
        $('#userEditModal').modal('hide');
        $("#idCardModalContent").html("");
        str1 = "oper=load_users_manage";
        $("#idCardModalContent").load("app/ajax.php", str1);
        pbAlert(result);
    });
}

function saveUserSetings() {
    soundClick();
    fuserroles = '';
    fiduserid = $("#iduserid").val();
    fiduserlogin = $("#iduserlogin").val();
    fiduserpassword = $("#iduserpassword").val();
    fiduseremail = $("#iduseremail").val();
    fiduserphone = $("#iduserphone").val();
    fiduserfname = $("#iduserfname").val();
    fiduseraddress = $("#iduseraddress").val();
    fidusercomment = $("#idusercomment").val();
    if($("#setUserRoleAdmin").prop("checked")) {
        fuserroles = fuserroles + 'admin;';
    }
    if($("#setUserRoleOfficer").prop("checked")) {
        fuserroles = fuserroles + 'officer;';
    }
    if($("#setUserRoleAccaunter").prop("checked")) {
        fuserroles = fuserroles + 'accaunter;';
    }
    if($("#setUserRoleUnit").prop("checked")) {
        fuserroles = fuserroles + 'unit;';
    }
    if($("#setUserRoleGhost").prop("checked")) {
        fuserroles = fuserroles + 'ghost;';
    }
    $("#idalert").html("");
    $('#userEditModal').modal('hide');
    str1 = "oper=save_user&fiduserid="+fiduserid+"&fiduserlogin="+fiduserlogin+"&fiduserpassword="+fiduserpassword+"&fiduseremail="+fiduseremail+"&fiduserphone="+fiduserphone+"&fiduserfname="+fiduserfname+"&fiduseraddress="+fiduseraddress+"&fidusercomment="+fidusercomment+"&fuserroles="+fuserroles;
    $("#idalert").load("app/ajax.php", str1);
    $('#msgAlert').modal('show');
    $("#idCardModalContent").html("");
    str1 = "oper=load_users_manage";
    $("#idCardModalContent").load("app/ajax.php", str1);
}

function funcRefresh() {
    window.location.pathname = "/app/refresh.php";
}

var timerActiveId = setTimeout(function tickActive() {
    map.locate({setView: false, maxZoom: 18});
    timerActiveId = setTimeout(tickActive, sysTimeout);
}, 3000);

$(document).ready(function(){
    $("#map").mousedown(function(event){
        if (event.which == 1) {
            $(".leaflet-container").removeClass("js-map-onup");
            $(".leaflet-container").addClass("js-map-ondown");
            $(".leaflet-interactive").removeClass("js-map-onup");
            $(".leaflet-interactive").addClass("js-map-ondown");
        }
    });
    $("#map").mouseup(function(){
        $(".leaflet-container").removeClass("js-map-ondown");
        $(".leaflet-container").addClass("js-map-onup");
        $(".leaflet-interactive").removeClass("js-map-ondown");
        $(".leaflet-interactive").addClass("js-map-onup");
    });
    $("#map").mouseover(function(){
        $(".leaflet-container").removeClass("js-map-ondown");
        $(".leaflet-container").addClass("js-map-onup");
        $(".leaflet-interactive").removeClass("js-map-ondown");
        $(".leaflet-interactive").addClass("js-map-onup");
    });
    $('#msgConfirmTarget').on('hidden.bs.modal', function() {
        soundClick();
        gragTargetYes(flChDrTarget);
        flChDrTarget = false;
    });
    $('#msgLocationError').on('hidden.bs.modal', function() {
        soundClick();
        map.locate({setView: false, maxZoom: 18});
    });
});
