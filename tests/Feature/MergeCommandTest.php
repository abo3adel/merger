<?php

use App\Helpers;
use Dotenv\Dotenv;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Str;

include_once __DIR__ . '\\content.php';
include_once __DIR__ . '\\helpers.php';

it('will show error if directory not found', function () {
    $this->artisan('merge laro')->expectsOutput('Directory not found');
});

it('will merge json files', function () use ($cmpContent, $stubContent) {
    doTest(function ($self, $basePath, $fileName) {
        $updatedFile = readJsonFile($fileName);
        expect($updatedFile->description)
            ->toBe('new something long');
        expect($updatedFile->require->{'nicolaslopezj/searchable'})
            ->toBe('^1.13');
        expect($updatedFile->require->{"laravel/laravel"})
            ->toBe('~1.0');
        
        $nestedFile = readJsonFile('data/' . $fileName);
        expect($nestedFile->description)
            ->toBe('new something long');
    }, $this, bin2hex(random_bytes(6)) . '.json', [
        $stubContent,
        $cmpContent
    ], false);
});

it('will replace json files content if force was true', function () use ($cmpContent, $stubContent) {
    doTest(function ($self, $basePath, $fileName) {
        $updatedFile = readJsonFile($fileName);
            expect($updatedFile->require->{"laravel/laravel"})
                ->toBe('~59.0');
            $nestedFile = readJsonFile('data/' . $fileName);
            expect($nestedFile->require->{"laravel/laravel"})
                ->toBe('~59.0');
    }, $this, bin2hex(random_bytes(6)) . '.json', [
        $stubContent,
        $cmpContent
    ]);
});

it('will merge env files', function () use ($stubEnvContent, $baseEnvContent) {
    doTest(function($self, $basePath, $fileName) {
        $updatedFile = Dotenv::createArrayBacked($basePath)->load();
        expect($updatedFile['APP_NAME'])->toBe('some thing new');
        expect($updatedFile['DB_USERNAME'])->toBe('dbName');
        expect($updatedFile['TELESCOPE_ENABLED'])->toBe('false');

    }, $this, '.env', [
        $stubEnvContent, $baseEnvContent
    ]);
});

it('will merge yaml files', function () use ($stubYmlContent, $baseYmlContent) {
    doTest(function($self, $basePath, $fileName) {
        $updatedFile = (object)Yaml::parseFile($basePath . $fileName);
        expect($updatedFile->build)->toBe(true);
        expect($updatedFile->call[1])->toBe('will be appended anyway');
        expect($updatedFile->cache[2])->toBe('SET SYMFONY_PHPUNIT_DISABLE_RESULT_CACHE=1');
    },
    $this,
    bin2hex(random_bytes(4)) . '.yml',
    [
        $stubYmlContent, $baseYmlContent
    ]);
});

it('will merge .git files', function () use ($stubGit, $baseGit) {
    doTest(function ($self, $basePath, $fileName) {
        $updatedFile =  explode("\n", file_get_contents($basePath . $fileName));
        expect(in_array("/vendor\r", $updatedFile))->toBe(true);
        expect(in_array("_ide_helper_models.php\r", $updatedFile))
            ->toBe(true);
        expect(in_array("* text=auto", $updatedFile))->toBe(true);
    }, $this, '.gitignoreme', [
        $stubGit,
        $baseGit
    ]);
});

it('will merge xml files', function () use ($stubXml, $baseXml) {
    doTest(function ($self, $basePath, $fileName) {
        $updatedFile =  \LaLit\XML2Array::createArray(file_get_contents($basePath . $fileName))['phpunit'];
        expect($updatedFile['php']['server'][7]['@attributes']['value'])->toBe('false');
        expect($updatedFile['@attributes']['colors'])->toBe('false');
    }, $this, bin2hex(random_bytes(5)) . '.xml.dist', [
        $stubXml,
        $baseXml
    ], false);
});

it('will append to other files', function () use ($baseJs, $stubJs) {
    doTest(function ($self, $basePath, $fileName) {
        $updatedFile = file_get_contents($basePath . $fileName);
    expect(Str::contains($updatedFile, '.version();'))->toBe(true);        
    }, $this, bin2hex(random_bytes(5)) . '.mix.js', [
        $stubJs,
        $baseJs
    ], false);
});

// it('will istall composer and npm packages', function () use ($stubPackage) {
//     doTest(function ($self, $basePath, $fileName) {
//     //     $updatedFile = file_get_contents($basePath . $fileName);
//     // expect(Str::contains($updatedFile, '.version();'))->toBe(true);        
//     }, $this, 'install.yml', [
//         $stubPackage,
//         $stubPackage
//     ], false);
// });