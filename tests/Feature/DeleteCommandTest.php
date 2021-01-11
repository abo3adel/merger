<?php

use App\Helpers;

it('will show error if file not exists', function () {
    $this->artisan('delete a.jpg')->expectsOutput(
        'file a.jpg not found'
    );
});

it('will delete file', function() {
    $file = 'lara/comp.json';

    $this->artisan('create -d ' . $file);

    expect(file_exists(Helpers::stubsPath($file)))->toBe(true);

    $this->artisan('delete ' . $file)->expectsOutput(
        basename($file) . ' Delete Successfully'
    );

    expect(file_exists(Helpers::stubsPath($file)))->toBe(false);
});
