var map;
var maptile;
var units = [];
var targets  = [];
var wayUnit  = [];
var iunit;
var itarget;
var ipathline;

var pblat = 0;
var pblng = 0;
var pbaccuracy = 0;

var unitCircle;
var wayPath;

var dragStopCoord;
var idDragTarget;
var flChDrTarget = false;

var isAddTarget = false;
var isUpdTarget = false;

var histFlMarker = L.icon({
        iconUrl: '../images/markers/hist-fl-marker.png',
        iconSize: [48, 48],
        iconAnchor: [3, 47]
    });
var histPoMarker = L.icon({
        iconUrl: '../images/markers/hist-point-marker.png',
        iconSize: [16, 16],
        iconAnchor: [8, 16]
    });
var userFlMarker = L.icon({
        iconUrl: '../images/markers/user-fl-marker.png',
        iconSize: [48, 48],
        iconAnchor: [3, 47]
    });
var goalFlMarker = L.icon({
        iconUrl: '../images/markers/goal-fl-marker.png',
        iconSize: [48, 48],
        iconAnchor: [24, 47]
    });

map = L.map('map', {
    attributionControl: false,
    zoomControl: false,
    center: [startLat, startLng],
    zoom: 13
})
.on('contextmenu', function(event) {
    var newLatLng = event.latlng;
    var strLat = event.latlng.lat;
    var strLng = event.latlng.lng;
    if (nameInterface == "oper") {
        $.post("app/ajax.php", { oper: "add_target", lat: strLat, lng: strLng }, function(result) {
            soundClick();
            eval(result);
            if (isAddTarget == true) {
                var newmarker = addTarget(newLatLng, map, nameTarget, idNewTarget);
                map.panTo(newLatLng);
                newmarker.bindTooltip(locale.new_goal_added+': '+nameTarget, {opacity: 0.7})
                .openTooltip();
                newmarker.on('click', function(e) {
                    map.panTo(e.latlng);
                });
                newmarker.on('dragend', function(e) {
                    idDragTarget = e.target.options.dbid;
                    dragStopCoord = e.target.getLatLng();
                    pbConfirmTarget();
                });
                targets.push(newmarker);
                isAddTarget = false;
                if (isTargetList) {
                    countMsgList = 10;
                    $("#idTargetListBody").html("");
                    str1 = "oper=load_all_target_body&value="+countMsgList;
                    $("#idTargetListBody").load("app/ajax.php", str1);
                }
            }
        });
    }
});

maptile = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    minZoom: 2,
    maxZoom: 18,
    keepBuffer: 100,
    opacity: 1
}).addTo(map);

unitCircle = L.circle([0, 0], {
    stroke: false,
    fillColor: '#00FF00',
    fillOpacity: 0.37,
    radius: 1
}).addTo(map);
unitCircle.removeFrom(map);

iunit = addUnit({lat: 0, lng: 0}, map, "_", 0);
iunit.removeFrom(map);
itarget = addTargetU({lat: 0, lng: 0}, map, "_", 0);
itarget.removeFrom(map);
ipathline = addLineUT({lat: 0, lng: 0}, {lat: 1, lng: 1}, map);
ipathline.removeFrom(map);
wayPath = L.polyline([{lat: 0, lng: 0}, {lat: 1, lng: 1}], {
    geodesic: true,
    color: '#FF00FF'
}).addTo(map);
wayPath.removeFrom(map);

map.on('locationfound', function(success) {
    pblat = success.latlng.lat;
    pblng = success.latlng.lng;
    pbaccuracy = success.accuracy / 2;
    var qstr = "sys=i_am_active&lat="+pblat+"&lng="+pblng+"&accu="+pbaccuracy;
    if (nameInterface == "oper") {
        $.ajax({url: "app/ajax.php",
            data: qstr,
            success: function(result){
                if (isGlobWay == true) {
                    for (var i = 0; i < wayUnit.length; i++) {
                        wayUnit[i].removeFrom(map);
                    }
                    wayUnit = [];
                    wayPath.removeFrom(map);
                    showWayGlID();
                } else {
                    addManyUnits();
                    addManyTargets();
                }
            }
        });
    }
    if (nameInterface == "unit") {
        $.ajax({url: "app/ajax.php",
            data: qstr,
            success: function(result){
                $.post("app/ajax.php", { unit: "my_flags" }, function(result2) {
                    eval(result2);
                } );
            }
        });
    }
});

map.on('locationerror', function(err) {
    $('#msgLocationError').modal('show');
});

function addLineUT(startCoords, stopCoords, map) {
    var flightPlanCoordinates = [
        startCoords,
        stopCoords
    ];
    var flightPath = L.polyline(flightPlanCoordinates, {
        geodesic: true,
        color: '#FF00FF'
    }).addTo(map);
    return flightPath;
}

function computeDistance(startCoords, stopCoords) {
    var ll01 = L.latLng(startCoords.lat, startCoords.lng);
    var ll02 = L.latLng(stopCoords.lat, stopCoords.lng);
    var distance = ll01.distanceTo(ll02);
    return distance;
}

function addUnit(latLng, map, texttitle, dbidunit) {
    var marker = L.marker(latLng, {
        icon: userFlMarker,
        title: texttitle,
        dbid: dbidunit
    }).on('click', function(e) {
        map.panTo(this.getLatLng())
    })
    .addTo(map);
    return marker;
}

function addManyUnits() {
    deleteAllUnits();
    $.post("app/ajax.php", { oper: "count_units" }, function(result1) {
        eval(result1);
        if (countunits > 0) {
            for (var i1 = 0; i1 < countunits; i1++) {
                $.post("app/ajax.php", { oper: "load_unit", value: i1, timecheck: timecheck }, function(result2) {
                    eval(result2);
                    var newmarker = addUnit(newLatLng, map, nameUnit, idNewUnit);
                    newmarker.on('click', function(e) {
                        countMsgList = 10;
                        panToUnit(this.options.dbid);
                    });
                    units.push(newmarker);
                } );
            }
        }
    } );
}

function deleteAllUnits() {
    for (var i = 0; i < units.length; i++) {
        units[i].removeFrom(map);
    }
    units = [];
}

function addTarget(latLng, map, texttitle, dbidtarget) {
    var marker = L.marker(latLng, {
        icon: goalFlMarker,
        draggable: true,
        title: texttitle,
        dbid: dbidtarget
    }).addTo(map);
    return marker;
}

function addTargetU(latLng, map, texttitle, dbidtarget) {
    var marker = L.marker(latLng, {
        icon: goalFlMarker,
        draggable: false,
        title: texttitle,
        dbid: dbidtarget
    }).addTo(map);
    return marker;
}

function addManyTargets() {
    deleteAllTargets();
    $.post("app/ajax.php", { oper: "count_targets" }, function(result1) {
        eval(result1);
        if (counttargets > 0) {
            for (var i1 = 0; i1 < counttargets; i1++) {
                $.post("app/ajax.php", { oper: "load_target", value: i1 }, function(result2) {
                    eval(result2);
                    var newmarker = addTarget(newLatLng, map, nameTarget, idNewTarget);
                    newmarker.on('click', function(e) {
                        map.panTo(e.latlng);
                    });
                    newmarker.on('dragend', function(e) {
                        idDragTarget = e.target.options.dbid;
                        dragStopCoord = e.target.getLatLng();
                        pbConfirmTarget();
                    });
                    targets.push(newmarker);
                } );
            }
        }
    } );
}

function deleteAllTargets() {
    for (var i = 0; i < targets.length; i++) {
        targets[i].removeFrom(map);
    }
    targets = [];
}

function gragTargetYes(updflag = false) {
    if (updflag == true) {
        $.post("app/ajax.php", { oper: "update_target_coord", targid: idDragTarget, lat: dragStopCoord.lat, lng: dragStopCoord.lng }, function(res) {
            eval(res);
            if (isUpdTarget == true) {
                isUpdTarget = false;
                if (isTargetList) {
                    $("#idTargetListBody").html("");
                    str1 = "oper=load_all_target_body&value="+countMsgList;
                    $("#idTargetListBody").load("app/ajax.php", str1);
                }
                addManyTargets();
            }
        } );
    } else {
        addManyTargets();
    }
}

function toggleTypeMap(tMap) {
    soundClick();
    map.removeLayer(maptile);
    if (tMap == 'roadmap') {
        maptile = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            minZoom: 2,
            maxZoom: 18,
            keepBuffer: 100,
            opacity: 1
        }).addTo(map);
    }
    if (tMap == 'satellite') {
        maptile = L.mapboxGL({
            minZoom: 2,
            maxZoom: 18,
            accessToken: 'pk.eyJ1Ijoia3d5bnRvIiwiYSI6ImNqaGVoOWVmazAwMGUzNnBmaXB0NGE3amkifQ.s-UeGgZNnSWQw_ILVdFUGA',
            style: 'js/satellite/style.json'
        }).addTo(map);
    }
    if (tMap == 'hybrid') {
        maptile = L.mapboxGL({
            minZoom: 2,
            maxZoom: 18,
            accessToken: 'pk.eyJ1Ijoia3d5bnRvIiwiYSI6ImNqaGVoOWVmazAwMGUzNnBmaXB0NGE3amkifQ.s-UeGgZNnSWQw_ILVdFUGA',
            style: 'js/hybrid/style.json'
        }).addTo(map);
    }
    if (tMap == 'dark') {
        maptile = L.mapboxGL({
            minZoom: 2,
            maxZoom: 18,
            accessToken: 'pk.eyJ1Ijoia3d5bnRvIiwiYSI6ImNqaGVoOWVmazAwMGUzNnBmaXB0NGE3amkifQ.s-UeGgZNnSWQw_ILVdFUGA',
            style: 'js/dark/style.json'
        }).addTo(map);
    }
    if (tMap == 'decimal') {
        maptile = L.mapboxGL({
            minZoom: 2,
            maxZoom: 18,
            accessToken: 'pk.eyJ1Ijoia3d5bnRvIiwiYSI6ImNqaGVoOWVmazAwMGUzNnBmaXB0NGE3amkifQ.s-UeGgZNnSWQw_ILVdFUGA',
            style: 'js/decimal/style.json'
        }).addTo(map);
    }
    if (tMap == 'b3d') {
        maptile = L.mapboxGL({
            minZoom: 2,
            maxZoom: 18,
            pitch: 45,
            accessToken: 'pk.eyJ1Ijoia3d5bnRvIiwiYSI6ImNqaGVoOWVmazAwMGUzNnBmaXB0NGE3amkifQ.s-UeGgZNnSWQw_ILVdFUGA',
            style: 'https://openmaptiles.github.io/klokantech-3d-gl-style/style-cdn.json'
        }).addTo(map);
    }
}
