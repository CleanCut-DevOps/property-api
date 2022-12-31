<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Property Management service

Responsible for managing and storing information about users' properties, such as the address and type of each property
they have. This information could be used to help users manage their properties, as well as to provide relevant
information to cleaning service providers to help them deliver their services.

> Access the API on https://property-api.klenze.com.au

## Installation

To get started, you'll need to have the following software installed on your local machine:

-   MySQL (local server or DBaaS)
-   PHP 8
-   Composer

Once you have MySQL, create a schema in the MySQL DB called `property`.

Once you have PHP 8 and Composer installed, clone this repository to your local machine.

```bash
$ git clone "https://github.com/CleanCut-DevOps/property-api.git"
```

Next, navigate to the root directory of the project and install the dependencies:

```bash
$ cd property-api

$ composer update

$ composer install
```

Next, copy the `.env.example` file to `.env`.

1. Update the database credentials to match your local MySQL database
2. Set the URLs of all other services listed in the `.env` file to the URLs where the other services can be accessed
   from.
3. Generate a new laravel key.

```bash
$ cp .env.example .env

# Update the database credentials in the .env file

# Update the URLs of all other services

$ php artisan key:generate

$ php artisan config:cache
```

Next, run the database migration to create the tables in the database.

```bash
$ php artisan migrate:fresh
```

Finally, start the development server.

```bash
$ php artisan serve --port=8003
```

The application will now be running on http://localhost:8003.

If you're running this on a server, point the server to the entry point: `public/index.php`.
