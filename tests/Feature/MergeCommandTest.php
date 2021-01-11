<?php

use App\Helpers;
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

function doTest(callable $callable, $self, $stubContent, $cmpContent): void {
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

it('will merge files', function () use ($cmpContent, $stubContent) {
    doTest(function ($fileName) {
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

it('will replace content if force was true', function () use ($cmpContent, $stubContent) {
    doTest(function ($fileName) {
            $this->artisan('merge looaMerge -f');
            $updatedFile = getFile($fileName);
            expect($updatedFile->require->{"laravel/laravel"})
                ->toBe('~59.0');
            $nestedFile = getFile('data/' . $fileName);
            expect($nestedFile->require->{"laravel/laravel"})
                ->toBe('~59.0');
    }, $this, $stubContent, $cmpContent);
});