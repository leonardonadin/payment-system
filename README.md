## About

Simple Laravel project to manage money transactions between users.

## Setup

### With Composer

1. Clone the repository
2. Run `composer install`
3. Create a new database
4. Copy the `.env.example` file to `.env` and set the database connection (if necessary)
5. Run `php artisan key:generate`
6. Run `php artisan migrate`
7. Run `php artisan serve`
8. Access the application at `localhost`

### With Docker

1. Clone the repository
2. Run `composer install`
3. Copy the `.env.example-docker` file to `.env` and set the database connection (if necessary)
4. Run `./vendor/bin/sail artisan key:generate`
5. Run `./vendor/bin/sail artisan migrate`
6. Run `./vendor/bin/sail up`
7. Access the application at `localhost`

## API

Postman file: `./postman/api.postman_collection.json`

### Auth

> #### Login
> 
> - HTTP Method: POST
> - Endpoint: /api/login
> - Body:
> ```json
> {
>     "email": "email@email.com",
>     "password": "password"
> }
> ```

> #### Register
> 
> - HTTP Method: POST
> - Endpoint: /api/register
> - Body:
> ```json
> {
>     "name": "Name",
>     "email": "email@email.com",
>     "password": "password",
>     "password_confirmation": "password",
>     "document": "12345678901",
>     "type": "common"
> }
> ```
> - **type**: common = common user, merchant = merchant user

> #### Logout
> 
> - HTTP Method: POST
> - Endpoint: /api/logout
> - Middleware: auth

### Users

> #### List known users
> 
> - HTTP Method: GET
> - Endpoint: /api/users
> - Middleware: auth
> - Query:
> - ```?email=email@email.com```
> - ```?document=12345678901```
> - **email** and **document** are optional
> - If **email** or **document** is provided, the system will try to find the user by the email or document
> - If **email** or **document** is not provided, the system will return users that has transactions with the authenticated user

### Profile

> #### Show user
> 
> - HTTP Method: GET
> - Endpoint: /api/profile
> - Middleware: auth

> #### Update user
>
> - HTTP Method: PUT
> - Endpoint: /api/profile
> - Middleware: auth
> - Body:
> ```json
> {
>     "name": "Name",
>     "email": "email@email.com",
>     "password": "password",
>     "password_confirmation": "password"
> }
> ```
> - **name**, **email** and **password** are optional

### Wallet

> #### List wallets
> 
> - HTTP Method: GET
> - Endpoint: /api/wallets
> - Middleware: auth

> #### Show wallet
> 
> - HTTP Method: GET
> - Endpoint: /api/wallet/{id}
> - Middleware: auth

> #### Create wallet
> 
> - HTTP Method: POST
> - Endpoint: /api/wallet
> - Middleware: auth
> - Body:
> ```json
> {
>     "user_id": 1,
>     "balance": 0.00
> }
> ```

> #### Update wallet
> 
> - HTTP Method: POST
> - Endpoint: /api/wallet/{id}
> - Middleware: auth
> - Body:
> ```json
> {
>     "type": "in",
>     "amount": 10.00
> }
> ```
> - **type**: in = deposit, out = withdraw

> #### Delete wallet
>
> - HTTP Method: DELETE
> - Endpoint: /api/wallet/{id}
> - Middleware: auth

### Transactions

> #### List transactions
> 
> - HTTP Method: GET
> - Endpoint: /api/transactions
> - Middleware: auth

> #### Create transaction
> 
> - HTTP Method: POST
> - Endpoint: /api/transactions
> - Middleware: auth
> - Body:
> ```json
> {
>     "payer": {
>         "wallet_id": 1
>     },
>     "payee": {
>         "id": 2,
>         "wallet_id": 2
>     },
>     "amount": 10.00,
>     "description": "Description"
> }
> ```
> - **payer.wallet_id** and **payee.wallet_id** are optional, if not provided, the system will try to find the wallet by the user id
