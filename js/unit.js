var buttonSound = document.getElementById('idSoundbutton');
var buttonMic = document.getElementById('idMicbutton');
var buttonVol = document.getElementById('idVolbutton');

var isSound = true;
var isMic = false;
var isVol = false;
var isPass = false;
var isMassRadio = false;

var countMsgList = 10;
var nameInterface = "unit";

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

function panToUnit(idUnit, eff = true) {
    soundClick();
    if (eff == true) {
        hideAllModalDiv();
        offAllMenu();
        offHomeMenu();
        loadOneUnitMsgOper(idUnit);
    }
    $.post("app/ajax.php", { oper: "unit_coord", value: idUnit }, function(result) {
        unitCircle.removeFrom(map);
        eval(result);
    } );
}

function panToTarget(idTarget) {
    soundClick();
    hideAllModalDiv();
    $.post("app/ajax.php", { oper: "target_coord", value: idTarget }, function(result) {eval(result);} );
}

function pbAlert(pbMsg, sd = false) {
    $('#idalert').html(pbMsg);
    if (sd == true) {
        soundClick();
    }
    $('#msgAlert').modal('show');
}

function pbOpenFolder() {
    soundClick();
    $("#idfolder").html("");
    $('#openFolder').modal('show');
    str1 = "unit=list_docs";
    $("#idfolder").load("app/ajax.php", str1);
}

function pbOpenInfo(infoNumber) {
    soundClick();
    $('#openInfo').modal('show');
    str1 = "unit=load_doc_name&value="+infoNumber;
    $("#idinfoname").load("app/ajax.php", str1);
    str1 = "unit=load_doc_body&value="+infoNumber;
    $("#idinfobody").load("app/ajax.php", str1);
}

function loadUnitMsg() {
    soundClick();
    $("#idOutMsgUnit").html("");
    str1 = "unit=load_msgs&value="+countMsgList;
    $("#idOutMsgUnit").load("app/ajax.php", str1);
    $('#msgModal').modal('show');
}

function sendUnitMsg() {
    strin = $("#inputUnitMsg").val();
    str1 = "unit=send_msg&textmsg="+strin;
    $("#idOutMsgUnit").load("app/ajax.php", str1);
    $("#inputUnitMsg").val("");
    loadUnitMsg();
}

function moreUnitMsg() {
    countMsgList = countMsgList + 10;
    loadUnitMsg();
}

function loadUnitTask() {
    soundClick();
    $("#idOutTask").html("");
    $('#idTasks').modal('show');
    $.post("app/ajax.php", { unit: "load_tasks_json" }, function(result) {
        eval("var tasksJSON = "+result);
        var resStr = ' ';
        var targStr;
        if (tasksJSON.result == true) {
            var index;
            for (index = 0; index < tasksJSON.tasks.length; ++index) {
                targStr = ' ';
                if (tasksJSON.tasks[index].target == true) {
                    targStr = ' <i class="fa fa-flag-checkered" aria-hidden="true" title="'+tasksJSON.tasks[index].targetname+'"></i> ';
                }
                if (tasksJSON.tasks[index].taskstatus > 1) {
                    resStr += '<div class="eventmsgok">'+tasksJSON.tasks[index].msgstart+targStr;
                    resStr += ' <div class="unitmsg"> <i class="fa fa-check" aria-hidden="true"></i> '+tasksJSON.tasks[index].msgfinish+'</div>';
                } else {
                    resStr += '<div class="eventmsg">'+tasksJSON.tasks[index].msgstart+targStr;
                    if (tasksJSON.uaccu <= sysAccuracy) {
                        if (tasksJSON.tasks[index].target == true) {
                            var distanceTarget = computeDistance({lat: Number(tasksJSON.ulat), lng: Number(tasksJSON.ulng)}, {lat: Number(tasksJSON.tasks[index].targetlat), lng: Number(tasksJSON.tasks[index].targetlng)});
                            if (distanceTarget <= sysAccuracy) {
                                resStr += ' <button onclick="pbReportTask('+Number(tasksJSON.tasks[index].taskid)+')" class="btn btn-default2"><i class="fa fa-check" aria-hidden="true"></i></button> ';
                            }
                        } else {
                            resStr += ' <button onclick="pbReportTask('+Number(tasksJSON.tasks[index].taskid)+')" class="btn btn-default2"><i class="fa fa-check" aria-hidden="true"></i></button> ';
                        }
                    } else {
                        resStr += '<div class="cmdmsg"> <b>'+locale.attention+':</b> '+locale.poor_accuracy_in_determining_your_coordinates+' </div>';
                    }
                }
                resStr += '</div>';
            }
        } else {
            resStr += locale.no_tasks;
        }
        $("#idOutTask").html(resStr);
    } );
}

function loadUnitSetings() {
    soundClick();
    $("#unitSetContent").html("");
    $('#unitSetModal').modal('show');
    str1 = "unit=load_sets";
    $("#unitSetContent").load("app/ajax.php", str1);
}

function saveUnitSetings() {
    soundClick();
    fidpassword = $("#idpassword").val();
    fidemail = $("#idemail").val();
    fidphone = $("#idphone").val();
    fidfname = $("#idfname").val();
    fidaddress = $("#idaddress").val();
    fidcomment = $("#idcomment").val();
    $("#idalert").html("");
    $('#unitSetModal').modal('hide');
    str1 = "unit=save_sets&fidpassword="+fidpassword+"&fidemail="+fidemail+"&fidphone="+fidphone+"&fidfname="+fidfname+"&fidaddress="+fidaddress+"&fidcomment="+fidcomment;
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

function pbReportTask(idTask) {
    soundClick();
    $('#idTasks').modal('hide');
    $('#idReport').modal('show');
    str1 = '<button onclick="soundClick()" class="btn btn-default" data-dismiss="modal">'+locale.close+'</button> <button onclick="sendReportTask('+idTask+')" class="btn btn-success" title="'+locale.send+'"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>';
    $("#footerReport").html(str1);
}

function sendReportTask(idTask) {
    soundClick();
    var strReport = $("#msgReport").val();
    $('#idReport').modal('hide');
    str1 = "unit=save_report&value="+idTask+"&msgreport="+strReport;
    $("#idalert").load("app/ajax.php", str1);
    $('#msgAlert').modal('show');
    $("#msgReport").val('');
    $.post("app/ajax.php", { unit: "my_flags" }, function(result2) {
        eval(result2);
    } );
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
    $('#msgLocationError').on('hidden.bs.modal', function() {
        soundClick();
        map.locate({setView: false, maxZoom: 18});
    });
});
