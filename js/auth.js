var buttonSound = document.getElementById('idSoundbutton');
var isSound = true;

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
        buttonSound.title = 'Вкл. звук';
    }
    else {
        isSound = true;
        buttonSound.innerHTML = '<i class="fa fa-volume-up" aria-hidden="true"></i>';
        buttonSound.title = 'Выкл. звук';
        soundClick();
    }
}

function pbAlert(pbMsg, sd = false) {
    $('#idalert').html(pbMsg);
    if (sd == true) {
        soundClick();
    }
    $('#msgAlert').modal('show');
}
