<?php

use App\Helpers;
use Dotenv\Dotenv;
use Illuminate\Support\Facades\File;

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

it('will show error if directory not found', function () {
    $this->artisan('merge laro')->expectsOutput('Directory not found');
});

function getFile(string $fileName): object {
    return json_decode(
        file_get_contents(storage_path('../' . $fileName))
    );
}

function delete($path): void
{
    exec('rm -rf ' . $path);
}

function doJsonTest(callable $callable, $self, $stubContent, $cmpContent): void {
    $fileName = bin2hex(random_bytes(5)) . '.json';
    delete('data/');
    delete(dirname(Helpers::stubsPath('looaMerge/' . $fileName)));
    $self->artisan('create -d looaMerge/data/' . $fileName);
    $self->artisan('create -d looaMerge/' . $fileName);

    File::put(Helpers::stubsPath('looaMerge/' . $fileName), $stubContent);
    File::put($fileName, $cmpContent);
    mkdir('data');
    File::put('data/'.$fileName, $stubContent);

    $callable($fileName);

    delete(dirname(Helpers::stubsPath('looaMerge/' . $fileName)));
    delete($fileName);
    delete('data/');
}

it('will merge json files', function () use ($cmpContent, $stubContent) {
    doJsonTest(function ($fileName) {
        $this->artisan('merge looaMerge')
            ->expectsOutput('Fetching your files...')
            ->assertExitCode(0);

        $updatedFile = getFile($fileName);
        expect($updatedFile->description)
            ->toBe('new something long');
        expect($updatedFile->require->{'nicolaslopezj/searchable'})
            ->toBe('^1.13');
        expect($updatedFile->require->{"laravel/laravel"})
            ->toBe('~1.0');
        
        $nestedFile = getFile('data/' . $fileName);
        expect($nestedFile->description)
            ->toBe('new something long');
    }, $this, $stubContent, $cmpContent);
});

it('will replace json files content if force was true', function () use ($cmpContent, $stubContent) {
    doJsonTest(function ($fileName) {
            $this->artisan('merge looaMerge -f');
            $updatedFile = getFile($fileName);
            expect($updatedFile->require->{"laravel/laravel"})
                ->toBe('~59.0');
            $nestedFile = getFile('data/' . $fileName);
            expect($nestedFile->require->{"laravel/laravel"})
                ->toBe('~59.0');
    }, $this, $stubContent, $cmpContent);
});

it('will read env files', function () use ($stubEnvContent, $baseEnvContent) {
    $path = 'laraMerge/';
    $fullPath = Helpers::stubsPath($path) . '/';
    $basePath = storage_path('..') . '/';
    delete($fullPath);
    delete($basePath . '.env');

    $this->artisan('create -d ' . $path . '.env');
    File::put($fullPath . '.env', $stubEnvContent);
    File::put($basePath . '.env', $baseEnvContent);

    $this->artisan('merge -f ' . $path)->expectsOutput('Fetching your files...');

    $updatedFile = Dotenv::createArrayBacked($basePath)->load();
    expect($updatedFile['APP_NAME'])->toBe('some thing new');
    expect($updatedFile['DB_USERNAME'])->toBe('dbName');
    expect($updatedFile['TELESCOPE_ENABLED'])->toBe('false');

    delete($fullPath);
    delete($basePath . '.env');
});