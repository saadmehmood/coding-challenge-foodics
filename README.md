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
    For database:

            DB_HOST = localhost
            DB_DATABASE = Database Name
            DB_USERNAME = Username
            DB_PASSWORD = Password

    For mail settings:

            MAIL_MAILER=smtp
            MAIL_HOST=mailpit
            MAIL_PORT=1025
            MAIL_USERNAME=null
            MAIL_PASSWORD=null
            MAIL_ENCRYPTION=null
            MAIL_FROM_ADDRESS="hello@example.com"

4- Following directories should be writable by your web server

    storage
    bootstrap/cache

5- Go to project's root directory and run the following command to install all package dependencies

    $ composer install

6- Run the following command to execute migration and seed data

    $ php artisan migrate:refresh --seed

7- Run the following command to run project at local machine

    $ php artisan serve

8- Run the following command to run test cases

    $ php artisan test


## Api Documentation

REST api to store order info, update products and ingredients record in database. If the ingredients used more than 50% an email alert will be sent to merchant/vendor.

### Api Endpoint

```http
POST http://localhost:8000/api/orders
```

### Request Body

```json
{
    "products": [
        {
            "product_id": 1,
            "quantity": 1
        }
    ]
}
```

### Success Responses

Success response will return the order info with products and ingredients data

```json
{
    "data": {
        "id": 6,
        "products": [
            {
                "id": 1,
                "name": "aut",
                "ingredients": [
                    {
                        "id": 1,
                        "stock": 611,
                        "usedStock": 10,
                        "stockAlertSent": 0
                    },
                    {
                        "id": 2,
                        "stock": 108,
                        "usedStock": 60,
                        "stockAlertSent": 1
                    },
                    {
                        "id": 3,
                        "stock": 446,
                        "usedStock": 10,
                        "stockAlertSent": 0
                    }
                ]
            }
        ]
    }
}
```
### Error Response

```json
{
    "message": "The selected products.0.product_id is invalid.",
    "errors": {
        "products.0.product_id": [
            "The selected products.0.product_id is invalid."
        ]
    }
}
```

## Status Codes

Orders Api returns the following status codes in response:

| Status Code | Description               |
|:------------|:--------------------------|
| 201         | `CREATED`                 |
| 422         | `UNPROCESSABLE CONTENT`   |
| 500         | `INTERNAL SERVER ERROR`   |


