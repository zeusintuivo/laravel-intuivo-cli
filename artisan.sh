#!/bin/bash

# set working directory as the scripts location during the execution of script 
cd "$(dirname "$0")"

php "artisan" "$@";
