# PHP Coding Challenge

This project is a codding challenge for senior software engineer at Foodics.

### Prerequisites

```
- PHP > 8.1
- Apache 2.4.52 with mod_rewrite module
- mysql >= 5.6
- Git
- Composer
- Curl
- MCrypt
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
```

### Installation and Setup

1- Clone project by running the following command

    $ git clone git@github.com:saadmehmood/coding-challenge-foodics.git

2- Create a mysql database and use that database credentials in the next step

3- There is a file at project root named ".env.example", make a copy of this file with name ".env" and change the values in the following keys:

            DB_HOST = localhost
            DB_DATABASE = Database Name
            DB_USERNAME = Username
            DB_PASSWORD = Password

4- Following directories should be writable by your web server

    storage
    bootstrap/cache

5- Go to project's root directory and run the following command to install all package dependencies

    $ composer install

6- Run the following command to execute migration and seed data

    $ php artisan migrate:refresh --seed

7- Run the following command to run project at local machine

    $ php artisan serve

8- API post endpoint

    http://localhost:8000/api/v1/orders

9- Run the following command to run test cases

    $ php artisan test

All Set!
