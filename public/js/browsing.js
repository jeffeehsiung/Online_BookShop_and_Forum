// // Initiate all variables
// let genre_selector = document.getElementsByClassName("genre-selector");
// let name_selector = document.getElementsByClassName("name-selector");
// let author_selector = document.getElementsByClassName("author-selector");
// let genre_filter = document.getElementById("genre-filter");
// let search_button = document.getElementById("search-button");
// let save_button = document.getElementById("save-button");
//
// // declare an array to store the selected genres
// let selected_genres = [];
// // declare a variable to store the selected bookname
// let selected_bookname = "";
// // declare a variable to store the selected author
// let selected_author = "";
//
// // for each genre_selector, add an click event listener to retrieve the genre value
// for (let i = 0; i < genre_selector.length; i++) {
//     genre_selector[i].addEventListener("click", function() {
//         // check the corresponding gnere_filter checkbox if the genre_selector is clicked
//         genre_filter[i].checked = true;
//         // add the genre to the selected_genres array if the genre_selector is clicked
//         selected_genres.push(genre_selector[i].getAttribute("value"));
//     });
// }
// // for each name_selector, add an change event listener to retrieve the name value
// for (let i = 0; i < name_selector.length; i++) {
//     name_selector[i].addEventListener("change", function() {
//         // update the selected_bookname variable if the name_selector is changed
//         selected_bookname = name_selector[i].getAttribute("value");
//     });
// }
//
// // for each author_selector, add an change event listener to retrieve the author value
// for (let i = 0; i < author_selector.length; i++) {
//     author_selector[i].addEventListener("change", function() {
//         // update the selected_author variable if the author_selector is changed
//         selected_author = author_selector[i].getAttribute("value");
//     });
// }
//
// // for each search_button, add an click event listener to query the database
// for (let i = 0; i < search_button.length; i++) {
//     search_button[i].addEventListener("click", function() {
//         // construct the query string
//         let query = "";
//         // modify the query string if selected_genres is not empty
//         if (selected_genres.length > 0) {
//             for (let i = 0; i < selected_genres.length; i++) {
//                 query += "genre="+ selected_genres[i] + "&";
//             }
//             // remove the last "&" character
//             query = query.substring(0, query.length - 1);
//             // go to BookRepository.php
//         }
//         // modify the query string if selected_bookname is not empty
//         if (selected_bookname != "") {
//             query += "&name=" + selected_bookname;
//         }
//         // modify the query string if selected_author is not empty
//         if (selected_author != "") {
//             query += "&author=" + selected_author;
//         }
//         // return the query string
//         return query;
//     });
// }