document.getElementById("login_submit").disabled=true;
document.getElementById("register_submit").disabled=true;


function LoginFilledIn(){
    if (document.getElementById("login_username").value!=="" && document.getElementById("login_password").value!==""){
        document.getElementById("login_submit").disabled=false;
        document.getElementById("login_submit").style.opacity="1";
        document.getElementById("login_submit").style.cursor="pointer";
    }
    else
    {
        document.getElementById("login_submit").disabled=true;
        document.getElementById("login_submit").style.opacity="0.6";
        document.getElementById("login_submit").style.cursor="not-allowed";
    }
}

function RegisterFilledIn(){
    if (document.getElementById("register_username").value!=="" && document.getElementById("register_password").value!==""
        && document.getElementById("email").value!==""){
        document.getElementById("register_submit").disabled=false;
        document.getElementById("register_submit").style.opacity="1";
        document.getElementById("register_submit").style.cursor="pointer";
    }
    else
    {
        document.getElementById("register_submit").disabled=true;
        document.getElementById("register_submit").style.opacity="0.6";
        document.getElementById("register_submit").style.cursor="not-allowed";
    }
}

function loginOn() {
    //toggleForm(true);
    document.getElementById("Login").style.display = "flex";
}
/**
let togAni;
function toggleForm(enable) {
    if (togAni) togAni.pause();
    if (enable) {
        $("#formDarkness").show();
        $("#Login").show();
        togAni = anime({
            targets: "#Login",
            top: $("#Login").attr("distance"),
            duration: 200,
            easing: "easeInOutSine"
        });
    } else {
        $("#formDarkness").hide();
        togAni = anime({
            targets: "#Login",
            top: -window.innerHeight + "px",
            duration: 200,
            easing: "easeInOutSine",
            complete: () => {
                $("#Login").css("top", "-100%");
                $("#Login").hide();
            }
        });
    }
} **/
function loginOff() {
    document.getElementById("Login").style.display = "none";
}
function registerOn() {
    document.getElementById("Register").style.display = "flex";
}

function registerOff() {
    document.getElementById("Register").style.display = "none";
}
