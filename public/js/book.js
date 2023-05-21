let bookPage = document.getElementById("book-page");
let progressBar = document.getElementById("progress-bar");
let likeCount;
let dislikeCount;

function initialize(likes, dislikes) {

    // Limit width to width of header
    bookPage.style.width = document.getElementById("navbar").offsetWidth.toString() + "px";

    likeCount = likes;
    dislikeCount = dislikes;
    let likePercentage = Math.round((likeCount/(likeCount+dislikeCount)) * 100);
    progressBar.style.width = likePercentage + "%";
    console.log(progressBar.style.width = likePercentage + "%");
}