<?php

use App\Helpers;
use Illuminate\Support\Facades\File;

function readJsonFile(string $fileName): object {
    return json_decode(
        file_get_contents(storage_path('../' . $fileName))
    );
}

function delete($path): void
{
    exec('rm -rf ' . $path);
}

function doTest(
    callable $callable,
    $self,
    string $fileName,
    array $stubs,
    bool $force = true
) {
    $path = 'laraM'. random_int(1, 100) .'erge/';
    [$stubContent, $baseContent] = $stubs;
    $fullPath = Helpers::stubsPath($path) . '/';
    $basePath = storage_path('..') . '/';

    delete($fullPath);
    delete('data/');

    $self->artisan('create -d '. $path .$fileName);
    $self->artisan('create -d '. $path .'/data/' . $fileName);
    File::put($fullPath . $fileName, $stubContent);
    File::put($basePath . $fileName, $baseContent);
    mkdir('data');
    File::put('data/'.$fileName, $stubContent);

    $self->artisan($force ? 'merge -f ' . $path : 'merge ' . $path)
        ->expectsOutput('Fetching your files...');

    $callable($self, $basePath, $fileName);

    delete($fullPath);
    delete($basePath . $fileName);
    delete('data/');
}