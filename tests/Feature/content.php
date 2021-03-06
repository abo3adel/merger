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

$baseGit = <<<'GIT'
/vendor
/.idea
/.vscode
GIT;

$stubGit = <<<'GIT'
_ide_helper.php
_ide_helper_models.php
.phpstorm.meta.php
/.vscode
/storage/stubs/*
* text=auto
GIT;

$baseXml = <<<'XM'
<?xml version="1.0" encoding="UTF-8"?>
<phpunit colors="true"
         backupGlobals="false"
         backupStaticAttributes="false"
         bootstrap="vendor/autoload.php"
         stopOnFailure="false">
    <testsuites>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <php>
        <server name="APP_ENV" value="testing"/>
        <server name="BCRYPT_ROUNDS" value="4"/>
        <server name="CACHE_DRIVER" value="array"/>
        <server name="DB_CONNECTION" value="mysql"/>
        <server name="DB_DATABASE" value=":memory:"/>
        <server name="MAIL_MAILER" value="array"/>
        <server name="QUEUE_CONNECTION" value="sync"/>
        <server name="SESSION_DRIVER" value="array"/>
        <server name="TELESCOPE_ENABLED" value="true"/>
    </php>
</phpunit>
XM;

$stubXml = <<<'XM'
<?xml version="1.0" encoding="UTF-8"?>
<phpunit colors="false"
         convertErrorsToExceptions="true"
         convertNoticesToExceptions="true"
         convertWarningsToExceptions="true"
         processIsolation="false"
         stopOnFailure="false">
    <testsuites>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
    </testsuites>
    <coverage processUncoveredFiles="true">
        <include>
            <directory suffix=".php">./app</directory>
        </include>
    </coverage>
    <php>
        <server name="APP_ENV" value="testing"/>
        <server name="BCRYPT_ROUNDS" value="5"/>
        <server name="CACHE_DRIVER" value="array"/>
        <!-- <server name="DB_CONNECTION" value="sqlite"/> -->
        <server name="DB_DATABASE" value=":memory:"/>
        <server name="MAIL_MAILER" value="array"/>
        <server name="QUEUE_CONNECTION" value="sync"/>
        <server name="SESSION_DRIVER" value="array"/>
        <server name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
XM;

$baseJs = <<<'JS'
const mix = require('laravel-mix');


mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);
JS;

$stubJs = <<<'JS'
    // .browserSync('laravel.test')
    .polyfill({
        enabled: true,
        useBuiltIns: "usage",
        targets: {"ie": 10},
        debug: true
     })
    .version();
JS;

$stubPackage = <<<'PKG'
composer:
    - vlucas/phpdotenv
    - laravel/ui
    - dev:
        - sven/artisan-view
npm:
    - ts-loader
    - include-media
    - dev:
        - ts-loader
        - vue-particles
yarn:
    - typescript
    - dev:
        - vue-property-decorator
PKG;