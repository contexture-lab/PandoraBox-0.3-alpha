function include(url) {
    var script = document.createElement('script');
    script.src = url;
    document.getElementsByTagName('head')[0].appendChild(script);
}

function pboper() {
    include("js/bootstrap.js");
    include("js/osmmap.js");
    include("js/oper.js");
}

function pbunit() {
    include("js/bootstrap.js");
    include("js/osmmap.js");
    include("js/unit.js");
}

function pbauth() {
    include("js/bootstrap.js");
    include("js/auth.js");
}
