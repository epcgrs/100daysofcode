# Stoic Quotes

### This is a simple web app that shows a random stoic quote every time you refresh the page. It uses a sqlite3 database for storage, an API in PHP, and JS for the front end to get the quotes from API.

### The project is divided into two parts: the API and the front end.

### The API is a simple PHP script that connects to a sqlite3 database and returns a random quote in JSON format. The front end is a simple HTML page with a button that fetches the quote from the API and displays it on the page.

## What I learned

- How to use SQLITE with PHP
- How to use PHP to create a simple web app
- How to implement a simple API
- How to use JS to fetch data from an API
- How to use JS to manipulate the DOM
- Create a admin panel to manage the quotes in the database

## How to run the project

1. Clone the repository

```bash
git clone https://github.com/epcgrs/100daysofcode.git
cd 100daysofcode
cd 1
```

2. Run the server in php

```bash
php -S localhost:8000
```

3. Open your browser and go to `http://localhost:8000` to see the app in action.

4. You can also run the API separately by going to `http://localhost:8000/api.php` to see the JSON response.

5. You can administer the database using the `admin.php` file. This file allows you to add, edit, and delete quotes from the database. You can access it by going to `http://localhost:8000/admin.php`.
