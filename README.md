## Installation

- Below is project setup steps, As of now as quick setup and test from your side so currenlty i have pushed the vendor and .env file on repository so now you can directly run the db seed commands and server start commands and quick check

- Clone the repository
- Copy .env.example to .env
- Set the DB_ environment variables in .env file
- Create a database with the name specified in DB_DATABASE
- ```composer install```
- ```php artisan key:generate```
- Migrate and seed the database with ```php artisan migrate:fresh --seed```
- Run the application:
- ```php artisan serve```