# Rakamin-Assessment

---

## Requirements and Dependecies

---

1. PHP >8.0.2
2. Laravel v9.19
3. Composer 2.3.5
4. MySQL(DB engine can be changed later on into one of these 5 options: MariaDB 10.3+, MySQL 5.7+, PostgreSQL 10.0+, SQLite 3.8.8+, SQL Server 2017+)
5. Laravel/Sanctum

---

## Installation

---

1. Clone this repository (URL HTTPS : https://github.com/Ackyras/web_chat_api.git ; SSH : git@github.com:Ackyras/web_chat_api.git)
2. Run `composer install` command in your terminal to install all the required dependencies
3. Run `cp .env.example .env` command in your terminal to create **.env** file for setting up the environment
4. Create 2 new database with laravel supported DB engine (MariaDB 10.3+, MySQL 5.7+, PostgreSQL 10.0+, SQLite 3.8.8+, SQL Server 2017+). 1 is used for main application, and latter for testing purpose.
5. Open **.env** and change the database configuration(it's marked with the initials **"DB\_"** ) to match the database configuration you have.
6. Open **.env.testing** and change the database configuration(it's marked with the initials **"DB\_"** ) to match the database configuration you have.
7. Run `php artisan migrate --seed` command in your terminal to migrate the database table and create dummy data.
8. Run `php artisan key:generate` command in your terminal to create new application key.
9. Run `php artisan serve` command in your terminal to run the application. (you can use account from table user, all of the password is "password". ex: email : user@user, password : password);

---

## Endpoint

---

You can see all of the endpoint available by open url `/request-docs` from this app. You can see all endpoints there, but the only endpoints used are those that have the `api/v1` . prefix. You can filter it in Top-Left-Corner Box.
I also provide a postman collection which you can find in the `docs/` . directory

---

## Authentication

---

This app uses Laravel/Sanctum help for authentication, which uses bearer token as authentication key.

---

## Testing

---

Testing for this application is made with PHP unit testing which is in the default laravel application. The TestCase data used is created with the help of seeders, and is stored in a configurable testing database in .env.testing. So make sure you have configured the database for testing in **.env.testing** file.
Testing is made according to the UserStories in the work documentation([dropbox](https://www.dropbox.com/scl/fi/zqd7up1r67n47ursck8ip/Simple-Chat-API.paper?dl=0&rlkey=npv4sm71g0rcjmdazlspbjjni))
You can run test by running `php artisan test` in your terminal.

---

## Closing

---

I would like to thank the Rakamin Academy HRD team for the valuable opportunity that has been given to take part in the assessment of applying for work at Rakamin Academy by working on this mini project.

In working on this mini project, of course there are still many shortcomings in the implementation and can be solved even better. However, I still hope that the HRD team of Rakamin Academy can give me the opportunity to be able to develop my skills by working with other development teams and together advancing Rakamin Academy.
