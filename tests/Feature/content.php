<?php

$cmpContent = <<<'JSN'
{
    "description": "not Me",
    "require": {
        "laravel\/laravel": "~1.0",
        "mcamara/laravel-localization": "^1.5",
        "nicolaslopezj/searchable": "^1.13",
        "omnipay/paypal": "^3.0",
        "spatie/laravel-sitemap": "^5.8",
        "vinkla/hashids": "^8.1"
    }
}
JSN;

$stubContent = <<<'JSN'
{    
    "description": "new something long",
    "require": {
        "laravel\/laravel": "~59.0",
        "mcamara/laravel-localization": "^1.5",
        "nicolaslopezj/searchable": "^1.13"
    }
}
JSN;

$baseEnvContent = <<<'ENV'
    APP_NAME=laravel
    APP_KEY=base64:uGFvrjWQFEMHptgq7bQXuRPSnEc8P5H3r8d75F2TNa4=
    APP_DEBUG=true
    DB_DATABASE=homestead
    DB_USERNAME=
    DB_PASSWORD=
ENV;

$stubEnvContent = <<<'ENV'
    APP_NAME="some thing new"
    APP_DEBUG=false
    DB_DATABASE=mydb
    DB_USERNAME=dbName
    DB_PASSWORD=password

    TELESCOPE_ENABLED=false
ENV;

$baseYmlContent = <<<'YML'
build: false
clone_depth: 2
clone_folder: c:\projects\symfony

cache:
    - composer.phar
    - .phpunit -> phpunit

init:
    - SET PATH=c:\php;%PATH%
    - SET COMPOSER_NO_INTERACTION=1
YML;

$stubYmlContent = <<<'YML'
build: true
clone_depth: 25
clone_folder: c:\projects\symfony

cache:
    - composer.phar
    - SET ANSICON=121x90 (121x90)
    - SET SYMFONY_PHPUNIT_DISABLE_RESULT_CACHE=1

init:
    - SET PATH=c:\php;%PATH%
    - SET COMPOSER_NO_INTERACTION=1
    - copy /Y php.ini-development php.ini-min
    - echo memory_limit=-1 >> php.ini-min
    - echo serialize_precision=-1 >> php.ini-min
    - echo max_execution_time=1200 >> php.ini-min

call:
    - readmeFirst
    - will be appended anyway
YML;