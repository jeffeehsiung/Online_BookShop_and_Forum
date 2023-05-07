
let bookPage = document.getElementById("book-page");
let progressBar = document.getElementById("progress-bar");

// Limit width to width of header
bookPage.style.width = document.getElementById("navbar").offsetWidth.toString() + "px";

//progressBar.style.width = "50%";
function initialize(averageRating) {
    let likePercentage = Math.round((averageRating/5) * 100);
    progressBar.style.width = likePercentage + "%";
    //progressBar.innerHTML = likePercentage + "%";
    console.log(progressBar.style.width = likePercentage + "%");
}
function addLike() {
    //likeCount++;
    updateProgress();
}

function addDislike() {
    //dislikeCount++;
    updateProgress();
}

function updateProgress() {
    let likePercentage = Math.round((averageRating/5) * 100);
    progressBar.style.width = likePercentage + "%";
    //progressBar.innerHTML = likePercentage + "%";
    console.log(progressBar.style.width = likePercentage + "%");
}

