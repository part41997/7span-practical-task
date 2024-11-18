## Installation

- Clone the repository
- Copy .env.example to .env
- Set the DB_ environment variables in .env file
- Create a database with the name specified in DB_DATABASE
- ```composer install```
- ```php artisan key:generate```
- Migrate and seed the database with ```php artisan migrate:fresh --seed```
- Run the application:
- ```php artisan serve```