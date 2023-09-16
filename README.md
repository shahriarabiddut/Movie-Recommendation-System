## Required Configuration

PHP >= 8.1
Laravel 10

## Use THis Command to dowload required Packages

The most important thing to do when cloning a laravel project is to first run `composer update` then `composer install` The ###composer install command installs any required dependencies for that laravel app.

The steps I took to clone a laravel project required the `php artisan key:generate` command. You can see in your .env file that there is an updated APP_KEY=base64:xxxxxxxxxxxxxxxxxxxx after running this command.

## Storage Link Command

`php artisan storage:link`

## Databse Initialization Command

`php artisan migrate`

### Migration with Seeder

`php artisan migrate:refresh --seed`

## Controller Initialization with crud Command

`php artisan make:controller Staff/CastController -r`

## Model Initialization with migration Command

`php artisan make:model Cast -m`

## Project Ongoing Process

1. Multi Auth Complete
   Admin , Staff & User
   Larvel Breeze
   Email Verification Enabled for user
   Login for all
   Registration Only For Users
2. Admin CRUD for Users and Staff
3. CRUD Management
   3.1 Cast CRUD
   3.2 Genre CRUD
   3.3 Production Company CRUD
   3.4 Language CRUD
   3.5 Director CRUD
   3.6 Movie CRUD
   3.7 Country CRUD
   3.8 Dashboard simply Designed
4. User profile and Interest Added

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Cubet Techno Labs](https://cubettech.com)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[Many](https://www.many.co.uk)**
-   **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
-   **[DevSquad](https://devsquad.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[OP.GG](https://op.gg)**
-   **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
-   **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
