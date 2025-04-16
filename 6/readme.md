# Pay for a joke

A simple bitcoin lightning payments for a random joke.

## What I learn

- How to use the coinos API to create a payment request
- Review PHP and MySQL 
- Use Slim framework to create a simple project
- Use Composer to manage dependencies
- Use dotenv to manage environment variables
- Recursive functions in JS for check the payment status
- Review Jquery Ajax function
- Use the bitcoin lightning network to make payments

## Requirements
- PHP 
- Composer
- MySQL

## Installation
1. Clone the repository
```bash
git clone https://github.com/epcgrs/100daysofcode.git
cd 100daysofcode
cd 6
```

2. Install the dependencies
```bash
composer install
```

3. Migrate the database
```bash
php database/migration.php
```

4. Create a coinos account and get the token
- Go to [Coinos](https://coinos.io/)
- Create an account
- Go to the [API section](https://coinos.io/docs) and create a new token

5. Create a `.env` file and set the database connection and coinos token


```bash
COINOS_TOKEN=
COINOS_API_URL=https://coinos.io/api/

DB_HOST=
DB_NAME=
DB_USER=
DB_PASSWORD=

```

6. Run the server
```bash
php -S localhost:8000 -t public
```

7. Open your browser and go to `http://localhost:8000/`

## Usage
- Click on the "Get a joke" button
- Pay with bitcoin lightning
- Get a random joke
- Enjoy!