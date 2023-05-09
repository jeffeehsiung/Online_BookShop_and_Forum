let bookPage = document.getElementById("book-page");
let progressBar = document.getElementById("progress-bar");
let likeButton = document.getElementById("btn-like");
let dislikeButton = document.getElementById("btn-dislike");
// Limit width to width of header
bookPage.style.width = document.getElementById("navbar").offsetWidth.toString() + "px";

//progressBar.style.width = "50%";
function initialize(likes, dislikes) {
    let likePercentage = Math.round((likes/(likes+dislikes)) * 100);
    progressBar.style.width = likePercentage + "%";
    //progressBar.innerHTML = likePercentage + "%";
    console.log(progressBar.style.width = likePercentage + "%");
}
function like() {
    //likeCount++;

    // Fill button
    likeButton.firstElementChild.src="/images/thumbs-up-fill.png";

    // disable dislike button
    dislikeButton.disabled = true;
    // Change onclick action like->unlike


    updateProgress();
}

function unlike() {

}
function dislike() {
    //dislikeCount++;
    updateProgress();
}

function undislike() {

}

function updateProgress() {
    /*
    let likePercentage = Math.round((averageRating/5) * 100);
    progressBar.style.width = likePercentage + "%";
    //progressBar.innerHTML = likePercentage + "%";
    console.log(progressBar.style.width = likePercentage + "%");
     */
}

