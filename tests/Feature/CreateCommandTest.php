<?php

use App\Helpers;

it('will create directories', function () {
    $filePath = 'laravel/composer.json';
    expect(file_exists(Helpers::stubsPath($filePath)))->toBe(false);

    $this->artisan('create -d ' . $filePath)->expectsOutput('Creating: ' . $filePath);

    expect(file_exists(Helpers::stubsPath($filePath)))->toBe(true);

    exec('rm -rf ' . dirname(Helpers::stubsPath($filePath)));
});

it('will show error if file was created', function() {
    $filePath = 'laravel/composer.json';

    $this->artisan('create -d ' . $filePath)->expectsOutput('Creating: ' . $filePath);

    $this->artisan('create -d ' . $filePath)->expectsOutput('file was created already');

    exec('rm -rf ' . dirname(Helpers::stubsPath($filePath)));
});
