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
    if (book_title !== "") {
        window.location.href = "/browsing/" + book_title;
    }
}

// // declare a function to be called when the user clicks on a genre filter
// function filter() {
//     // get the values selected for each genre filter
//     let selected_genres = [];
//     for (let i = 0; i < genre_filters.length; i++) {
//         if (genre_filters[i].checked) {
//             selected_genres.push(genre_filters[i].value);
//         }
//     }
//     // redirect to the browsing page with the selected genres (URL)
//     window.location.href = "/browsing?genres=" + selected_genres.join(",");
// }

