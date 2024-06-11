## About

Simple Laravel project to manage money transactions between users.

## Setup

### With Composer

1. Clone the repository
2. Run `composer install`
3. Create a new database
4. Copy the `.env.example` file to `.env` and set the database connection
5. Run `php artisan key:generate`
6. Run `php artisan migrate`
7. Run `php artisan serve`
8. Access the application at `localhost`

### With Docker

1. Clone the repository
2. Run `composer install`
3. Copy the `.env.example-docker` file to `.env` and set the database connection
4. Run `./vendor/bin/sail artisan key:generate`
5. Run `./vendor/bin/sail artisan migrate`
6. Run `./vendor/bin/sail up`
7. Access the application at `localhost`

## API

### Auth

#### Login

HTTP Method: POST
Endpoint: /api/login
Body:
```json
{
    "email": "email@email.com",
    "password": "password"
}
```

#### Register

HTTP Method: POST
Endpoint: /api/register
Body:
```json
{
    "name": "Name",
    "email": "email@email.com",
    "password": "password",
    "password_confirmation": "password",
    "document": "12345678901",
    "type": "common" // or "shopkeeper"
}
```

#### Logout

HTTP Method: POST
Endpoint: /api/logout
Middleware: auth

### Users

#### List known users

HTTP Method: GET
Endpoint: /api/users
Middleware: auth

#### Show user

HTTP Method: GET
Endpoint: /api/users/{id}
Middleware: auth

### Wallet

#### List wallets

HTTP Method: GET
Endpoint: /api/wallets
Middleware: auth

#### Create wallet

HTTP Method: POST
Endpoint: /api/wallet
Middleware: auth
Body:
```json
{
    "user_id": 1,
    "balance": 0.00
}
```

#### Show wallet

HTTP Method: GET
Endpoint: /api/wallet/{id}
Middleware: auth

#### Update wallet

HTTP Method: POST
Endpoint: /api/wallet/{id}
Middleware: auth
Body:
```json
{
    "type": "in", // or "out"
    "amount": 10.00
}
```

### Transactions

#### List transactions

HTTP Method: GET
Endpoint: /api/transactions
Middleware: auth

#### Create transaction

HTTP Method: POST
Endpoint: /api/transactions
Middleware: auth
Body:
```json
{
    "payer": {
        "wallet_id": 1 // optional
    },
    "payee": {
        "id": 2,
        "wallet_id": 2 // optional
    },
    "amount": 10.00,
    "description": "Description"
}
```

