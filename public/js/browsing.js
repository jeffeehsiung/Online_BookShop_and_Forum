let genre_selectors = document.getElementsByClassName("genre-selector");
let book_name = document.getElementById("book_name");
let search_btn = document.getElementById("search-btn");
let genre_filters = document.getElementsByClassName("genre-filter");
let details_btn = document.getElementById("details-btn");

// add event listener to the search button
search_btn.addEventListener("click", search);

// declare a function to be called when the user clicks on search button
function search() {
    // get the book name value
    let book_title = book_name.value.trim();
    // if (book_title !== "") {
    //     window.location.href = "/browsing/" + book_title;
    // }
    fetch('{{path("browsing")}}/' + encodeURIComponent(book_title))
        .then(response => response.json())
        .then(data => {
            console.log(data);
        })
        .catch(error => console.log(error));

}
