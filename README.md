[![StyleCI](https://styleci.io/repos/79059090/shield?branch=master)](https://styleci.io/repos/79059090)
## Timber based wordpress starter theme

- used Timber with Twig template engine
- support custom routes/controllers
- support composer package manager
- easy custom post type integration

## How to use

- use "composer install" in project folder
- "npm i" and "npm run watch" to work with css/js
- set "wp_debug" to "true" in wp-config.php

## Fix for ubuntu localhost

- put in wp-config.php directive: define('FS_METHOD', 'direct');
