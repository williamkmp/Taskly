# **Taskly**

## Project Prerequisite

Below are the requirement for running the project

-   [Node](https://nodejs.org/en), LTS version 18.4.0 or above
-   [Composer](https://getcomposer.org/download/), version 2.5.4
-   [XAMPP](https://www.apachefriends.org/download.html), for PHP 8.1.6 and MySQL

## Project Setup

1. Clone Repository and download all dependency

    ```bash
    git clone https://github.com/williamkmp/laravel-taskly.git
    cd laravel-taskly
    composer install
    npm install
    php artisan key:generate
    code .
    ```

1. Download the recomended extension,
   to view the recomended extension open the command pallete `ctr + shift + p`, then search for `Extensions: Show Recommended Extensions` and download all the extensions.
1. Run xampp and turn on the MySQL server
1. Configure the project `.env` by copying the availabel `.env.example` and change below parameter:

    ```env
    APP_NAME = Taskly

    APP_URL = http://localhost

    DB_CONNECTION = mysql
    DB_HOST = 127.0.0.1
    DB_PORT = 3306
    DB_DATABASE = taskly
    DB_USERNAME = root
    ```

1. Run database migartions

    ```bash
    php artisan migrate:fresh
    ```

1. In a seperate terminal run the vite server (for building tailwind css stlye)

    ```bash
    npm run dev
    ```

1. In a seperate terminal run the artisan serve command

    ```bash
    php artisan serve --host='localhost' --port='8000'
    ```

## Project Dependecies

Below are libraries and devtools included inside the project:

-   [Tailwind](https://tailwindcss.com/docs/utility-first), for styling.
-   [Livewire](https://laravel-livewire.com/docs/2.x/quickstart), for dynamic UI component if it's a dumb component use [Blade Template Component](https://laravel.com/docs/10.x/blade#components) .
-   [Blade Icons](https://blade-ui-kit.com/blade-icons), font awesome icon.
-   [AlpineJs](https://alpinejs.dev/start-here), UI interactivity
