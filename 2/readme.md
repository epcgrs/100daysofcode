# Linkito

## URL shortener

Linkito is a URL shortener that allows you to create short links for long URLs. It is built using PHP and SQLITE and is designed to be simple and easy to use.

## Features

- Shorten long URLs
- Custom short links
- View link statistics
- Easy to use interface
- Login
- Register
- Admin panel
- Expiration date
- Delete links
- List links
- Limit IP

## Requirements

- PHP 8.2 or higher
- SQLITE

## What I learned

- Route handling in vanilla PHP
- How to use SQLITE with PHP
- How to use PHP sessions
- How to use PHP to create a simple web app
- How to implement user authentication

## Installation

1. Clone the repository

```bash
git clone https://github.com/epcgrs/100daysofcode.git
cd 100daysofcode
cd 2
```

2. Run the server in php

```bash
php -S localhost:8000
```

3. Open your browser and go to `http://localhost:8000` to see the app in action.

## Observations

- The IP Limit will always return a 404 error on localhost, it is only working in production because PHP is not able to get the real IP of the client in localhost.
