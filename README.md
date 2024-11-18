## Installation

- Clone the repository
- Copy .env.example to .env
- Set the DB_ environment variables in .env file
- Create a database with the name specified in DB_DATABASE
- ```composer install```
- ```php artisan key:generate```
- Migrate and seed the database with 
- ```php artisan migrate:fresh --seed```
- Create storage link:
- ```php artisan storage:link```
- Update APP_URL value in .env file:
- ```http://127.0.0.1:8000```
- Run the application:
- ```php artisan serve```