# Problog API

API for Problog app, created using Laravel

## How to use it locally

1. Download source code or clone this repository

```
git clone https://github.com/madeaditya02/problog-api.git
```

2. Install dependencies

```
composer install
```

3. Create a mysql database in your local computer named `problog`
4. Duplicate file `.env.example` and rename it to `.env` and set some configuration such as :
    - `APP_URL` set to `http://127.0.0.1:8000`
    - `DB_DATABASE` set to `problog`
    - Add the following configuration :
        ```
        SESSION_DRIVER=cookie
        SANCTUM_STATEFUL_DOMAINS=localhost
        SESSION_DOMAIN=localhost
        ```
5. Generate app key

```
php artisan key:generate
```

6. Run migrations and seed

```
php artisan migrate --seed
```

7. Run app

```
php artisan serve
```
