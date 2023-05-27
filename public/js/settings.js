let coll = document.getElementsByClassName("settings-submenu");
let settings = document.getElementById("settings");
let avatarOverlay = document.getElementById("avatar-overlay");

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