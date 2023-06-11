# SE & Webtech project repo

## Project URL's
Provide a link to the main page of your application. Or if you have multiple parts in your website you can provide a list of links (i.e. not all pages are in your main navigation bar)
* [Main login page](https://a22web12.studev.groept.be/public/welcome)
* [About page](https://a22web12.studev.groept.be/public/about)
* [Home page](https://a22web12.studev.groept.be/public/home)
* [Browsing page](https://a22web12.studev.groept.be/public/browsing)
* [Settings page](https://a22web12.studev.groept.be/public/settings)
* [Profile page](https://a22web12.studev.groept.be/public/profile)

---

## Website credentials
### Regular user
- login : zomaarivde@hotmail.com
- password : secret
### Admin / Library manager / ...
- login : test@test.com
- password : password

---

## Implemented Features
### General ###
* About Us page
* Bookable logo to home page
* Navigation bar which updates based on user's login status

### User Log in & Register ###
* user authentication
* log in
* log out
* register
* set up username
* set up email
* set up password
* confirm password
* authentication mail (check your spam)

### User Home ###
* display the recommended books based on user's followed books, based on the author of the book
* display the trending books based on the number of likes
* display random books based on each of user's followed genre
* link to the book page

### User Settings ###
* setting profile picture
* setting profile description (bio)
* setting preferred genres
* editing password
* 
### User Profile ###
* display user's profile picture
* display user's profile description (bio)
* display user's followed books
* display user's liked books
* display user's disliked books

### Book Page ###
* dislike books
* liking books
* follow books
* unfollow books

### User Browsing ###
* search libraries with book title
* filter books by single genre
* filter books by multiple genres
* displaying the number of search and filter results
* link to the book page
* previous and next page

## coding conventions
### names
* upper camel case for all classes (UpperCamelCase)
* lower camel casing for all functions (lowerCamelCase)
* lower camel casing for all variables (lowerCamelCase)
* snake_case for all key naming (snake_case) used for selection
* snake_case for all csv files (snake_case)
* snake_case for all html ids and classes (snake_case)
* ![img.png](img.png)
### indention
* Allman for functions and classes
* K&R for css blocks
* ![img_1.png](img_1.png)
* ![img_2.png](img_2.png)

## Testing
* The testing uses a local database that gets run by each member of the team. It has the same structure as the database on the server, but it contains the test data.
### Setting up the test database
#### Preliminary
* Have a MySQL server running on your localhost
* Set up your .env.test file such that it references your test database
#### Run the doctrine migration to get the tables and database structure
* Make sure there are no tables present in your test database (the migration might run into errors if there are tables present)
* Run the following command in your terminal from the root folder of your project: symfony console --env=test doctrine:migrations:migrate
#### Run data fixtures to populate your database tables
* Before we can start running the fixtures, we have to make sure that the memory_limit is set to 256M. This can be done by changing the php.ini file.
* These changes only have to be set up once, then we can start running the fixtures.
* Run the SQL queries listed in 'src/DataFixtures/setup for fixtures.txt', they clear the database and reset auto increment values.
* Execute the data fixtures by running the following command from your project root folder: php bin/console --env=test doctrine:fixtures:load
* After this the test data should be loaded in the database, and we can start testing.
