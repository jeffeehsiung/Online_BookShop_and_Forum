let coll = document.getElementsByClassName("settings-submenu");
let settings = document.getElementById("settings");
let avatarOverlay = document.getElementById("avatar-overlay");
let newPasswordInput = document.getElementById("new-password");
let confirmPasswordInput = document.getElementById("confirm-password");
let passwordMatchMessage = document.getElementById("passwordMatchMessage");
let btnSubmitPassword = document.getElementById("btn-submit-pw");

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
/*
document.getElementById("btn-open-avatar-overlay").addEventListener('click', function(){
    avatarOverlay.style.display = 'flex';
})
*/

avatarOverlay.addEventListener('click', function(e) {
    if(e.target.id === 'avatar-overlay') {
        avatarOverlay.style.display = 'none';
    }
})

function overlayOn() {
    avatarOverlay.style.display = 'flex';
}

function onBioChange() {
    document.getElementById("btn-save-bio").disabled = false;
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
    document.getElementById("password-message").remove();
}