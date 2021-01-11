<?php

use App\Helpers;

it('has helpers class', function () {
    $stubsPath = storage_path('stubs');

    expect($stubsPath)->toBe(Helpers::stubsPath());

    exec("mkdir {$stubsPath}\looa");

    expect($stubsPath.'\looa', Helpers::stubsPath('looa'));

    exec("rm -rf {$stubsPath}\looa");
});
