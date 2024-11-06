# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/lumen)](https://packagist.org/packages/laravel/lumen-framework)






Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

> **Note:** In the years since releasing Lumen, PHP has made a variety of wonderful performance improvements. For this reason, along with the availability of [Laravel Octane](https://laravel.com/docs/octane), we no longer recommend that you begin new projects with Lumen. Instead, we recommend always beginning new projects with [Laravel](https://laravel.com).


# Lumen API Project

Welcome to the Lumen API boilerplate! This project is designed to help you quickly set up a Lumen-based API with minimal configuration.

## Requirements

- **PHP** >= 7.3
- **Composer** (dependency manager)
- **Database**: MySQL (or another supported database of your choice)

## Getting Started

Follow these steps to set up and run the project:

### 1. Clone the Repository

Clone the repository and navigate into the project directory:


git clone <repository-url>
cd <project-directory>

### 2. Install Dependencies

composer install

### 3. Set Up Environment Variables

Lumen uses an .env file to store environment variables. If there is no .env file, create one by copying the example file:

cp .env.example .env
or 
touch .env

### 4. Generate the Application Key
If your application needs encryption or hashed tokens, you’ll need to generate an APP_KEY. Run the following command to generate a base64-encoded 32-character key:

php -r "echo 'base64:'.base64_encode(random_bytes(32)), PHP_EOL;"

Take the output from the command and add it to your .env file as follows:


APP_KEY=base64:your_generated_key

### 5. Configure Database
   Edit the .env file to add your database configuration. Update the following fields with your database details:

### 6. Run Migrations (if applicable)
If your application includes database migrations, run the following command to create the necessary tables:

php artisan migrate

### 7. Start the Development Server
To start the server on localhost:8000, use the following command:

php -S localhost:8000 -t public
### 8. Query Logging

This Lumen API boilerplate includes an optional query logging feature in the authentication middleware, which allows you to log all database queries executed during a request. This can be useful for debugging and performance monitoring.

When LOG_QUERIES is set to true, the middleware will log all executed queries for each authenticated request. This data is appended to the API response as a queries field, allowing you to inspect SQL statements and their bindings.

### Enabling Query Logging

To enable query logging, set the `LOG_QUERIES` environment variable to `true` in your `.env` file:


LOG_QUERIES=true
To start the server on localhost:8000, use the following command:

## Additional Configuration

Scheduled Tasks: If your application uses scheduled tasks, configure a cron job on your server to run the scheduler every minute. Here’s an example cron configuration:

* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1

# Troubleshooting
Error with Missing RouteServiceProvider: Lumen does not use RouteServiceProvider by default. Ensure that routes are defined directly in routes/web.php or routes/api.php.

Custom Middleware: If you need middleware like ThrottleRequests or ValidateSignature, refer to the Lumen documentation on adding custom middleware as they are not included by default.


## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
