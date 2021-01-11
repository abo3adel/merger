<?php

use App\Helpers;

it('has helpers class', function () {
    $stubsPath = storage_path('stubs');

    expect($stubsPath)->toBe(Helpers::stubsPath());

    exec("mkdir {$stubsPath}\laravel");

    expect($stubsPath.'\laravel', Helpers::stubsPath('laravel'));

    exec("rm -rf {$stubsPath}\laravel");
});
