# Scanner API

This the backend API for the [Scanner](https://github.com/timetablebot/scanner) app.

It's built using PHP and [Symfony Flex](https://symfony.com/doc/current/setup/flex.html).

## Setup

1. Install PHP 7
2. Install [Composer](https://getcomposer.org/)
3. Clone the source code
4. Install the dependencies with `composer install --no-dev --optimize-autoloader` 
5. Copy the `.env` file to a `.env.local` file.
6. Edit the configuration in your `.env.local` file:   
Set `APP_ENV` to `prod`, change `APP_SECRET` to a random string, 
input your `DATABASE_URL` and enter a custom `PUSH_KEY`.
7. Setup your web server.  
See [this guide](https://symfony.com/doc/current/setup/web_server_configuration.html) 
for more details on how to setup Apache (easiest) or Nginx (fastest).

For more information about the deployment of a Symfony application look at 
[this guide](https://symfony.com/doc/current/deployment.html#common-post-deployment-tasks).

After a successful setup you should be able to open the plain URL
in your web browser and see the message `Hey`.

## Contributing

To test your local changes, you have to install Composer.

Then download all required libraries by running `composer install`.

Run a small integrated web server with the command `php bin/console server:run`.  
Now you can access the page on your PC.
