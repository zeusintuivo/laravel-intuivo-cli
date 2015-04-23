#!/usr/bin/env bash
git pull;
php composer.phar dump-autoload;
php artisan migrate;