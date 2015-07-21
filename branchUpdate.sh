#!/bin/bash
git pull;
php composer.phar dump-autoload;
php artisan migrate;