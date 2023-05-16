let genre_selectors = document.getElementsByClassName("genre-selector");
let book_name = document.getElementById("book-name");
let search_btn = document.getElementById("search-btn");
let genre_filters = document.getElementsByClassName("genre-filter");
let details_btn = document.getElementById("details-btn");


// declare a function to be called when the user clicks on search button
function search() {
    // genre_selectors is a list of all genre options
    // iterate through the list and check if the option is selected
    // if it is selected, add it to the list of selected genres
    let selected_genres = [];
    for (let i = 0; i < genre_selectors.length; i++) {
        if (genre_selectors[i].checked) {
            selected_genres.push(genre_selectors[i].value);
        }
    }
    // get the book name value
    let book_name_value = book_name.value;

    // redirect to the browsing page with the selected genres and book name
    window.location.href = "/browsing?genres=" + selected_genres.join(",") + "&book_name=" + book_name_value;

}