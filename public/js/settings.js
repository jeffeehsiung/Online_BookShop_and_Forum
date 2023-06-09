let coll = document.getElementsByClassName("settings_submenu");
let settings = document.getElementById("settings");
let avatarOverlay = document.getElementById("avatar_overlay");
let newPasswordInput = document.getElementById("new_password");
let confirmPasswordInput = document.getElementById("confirm_password");
let passwordMatchMessage = document.getElementById("password_match_message");
let btnSubmitPassword = document.getElementById("btn_submit_pw");

// Limit width to width of header
settings.style.width = document.getElementById("navbar").offsetWidth.toString() + "px";

for (let i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        this.classList.toggle("active");
        let content = this.nextElementSibling;
        if (content.style.display === "flex") {
            content.style.display = "none";
        } else {
            content.style.display = "flex";
        }
    });
}

avatarOverlay.addEventListener('click', function(e) {
    if(e.target.id === 'avatar_overlay') {
        avatarOverlay.style.display = 'none';
    }
})

function overlayOn() {
    avatarOverlay.style.display = 'flex';
}

function onBioChange() {
    document.getElementById("btn_save_bio").disabled = false;
}

function checkPasswordMatch() {
    if(newPasswordInput.value === confirmPasswordInput.value) {
        passwordMatchMessage.style.color = 'green';
        passwordMatchMessage.innerText = 'Passwords match';
        btnSubmitPassword.disabled = false;
    } else {
        passwordMatchMessage.style.color = 'red';
        passwordMatchMessage.innerText = 'Passwords do not match, try re-entering new password or confirm password fields';
        btnSubmitPassword.disabled = true;
    }
}

function closeMessage() {
    document.getElementById("password_message").remove();
}