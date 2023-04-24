let likeCount = 0;
let dislikeCount = 0;
let bookPage = document.getElementById("book-page");
let progressBar = document.getElementById("progress-bar");

// Limit width to width of header
bookPage.style.width = document.getElementById("navbar").offsetWidth.toString() + "px";

progressBar.style.width = "50%";
function addLike() {
    likeCount++;
    updateProgress();
}

function addDislike() {
    dislikeCount++;
    updateProgress();
}

function updateProgress() {
    let total = likeCount + dislikeCount;
    let likePercentage = Math.round(likeCount / total * 100);
    let dislikePercentage = Math.round(dislikeCount / total * 100);

    progressBar.style.width = likePercentage + "%";
    /*progressBar.innerHTML = likePercentage + "%";*/
}