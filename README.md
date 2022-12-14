# User Property Management

Responsible for managing and storing information about users' properties, such as the address and type of each property they have. This information could be used to help users manage their properties, as well as to provide relevant information to cleaning service providers to help them deliver their services.

# Installation

Before cloning the repository:
- create a `user_properties` schema in your MySQL database

Upon cloning this repository:
- `cd` into the repository
- run `composer update` then `composer install` to generate dependencies in the vendor folder
- change `.env.example` to `.env`
- run `php artisan key:generate`
- configure the `.env`  with your MySQL configurations

# Usage

Upon installation:
- run `php artisan migrate:fresh` to create tables in database
- run `php artisan serve --port=8002` to start the server on a local environment
