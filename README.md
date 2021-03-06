# Merger
composer project to merge stub files with project predefined files
---
[![Latest Stable Version](https://poser.pugx.org/abo3adel/merger/v)](//packagist.org/packages/abo3adel/merger) [![Total Downloads](https://poser.pugx.org/abo3adel/merger/downloads)](//packagist.org/packages/abo3adel/merger) [![Latest Unstable Version](https://poser.pugx.org/abo3adel/merger/v/unstable)](//packagist.org/packages/abo3adel/merger) [![License](https://poser.pugx.org/abo3adel/merger/license)](//packagist.org/packages/abo3adel/merger)

## Requirements

- PHP 7.3+
- composer

## Project Idea

I have a lot of dependencies to include to any fresh project, so i created this project to quickly install and configure these dependencies

## Installation

```bash
composer global require abo3adel/merger
```

## Configration

set default editor to open created stub files
```bash
merger editor:set code
```

## Supported Files

<strong>json, xml, yaml, .git*</strong> and for other files: it will append your stubs to any files with the same name and directory

## Usage

- create new stub file
**Note** by default this will open file, to disable add ```-d``` or ```--do-not-open``` option to the create command
```bash
merger create lara/composer.json
```
- updated the file with stub code
```json
"config": {
    "sort-packages": false
},
"scripts": {
    "models": ["@php artisan ide-helper:models --nowrite"]
}
```
- add installable packages file (filename must be "install.yml")
```bash
merger create lara/install.yml
```
- add some packages to install
```yaml
composer:
    - laravel/ui
    - dev:
        - sven/artisan-view
        - barryvdh/laravel-ide-helper
npm:
    - include-media
```
- then run merger from your fresh project directory
```bash
merger merge lara
```
this will merge all created stubs and files with the same name, then install all packages

## Avaliable Commands
- create: creates new file then open it in the default editor
```bash
merger create {dir}/{file.ext} 
```
- open: open stub file for editing
```bash
merger open {dir}/{file.ext}

Options:
  -d, --do-not-open     do not open the file after creating
```
- delete: delete stub file
```bash
merger delete {dir}/{file.ext}
```
- merge: merge stub files with your project files and install dependencies
```bash
merger merge {dir}

Options:
  -f, --force           replace content in  your project files with stub files
  -d, --no-append       do not append stubs that has no user files with same names
  -i, --no-install      do not run install file
      --only[=ONLY]     list of files to be merged seperated by comma
```
- dir:list : lists all user created directories or files if user added directory name
```bash
merger dir:list {?dir}
```
- editor:set : set default code editor to open files
```bash
merger editor:set notepad++
```


## Contribution

Contributions are **welcome** and will be fully **credited**.
see [CONTRIBUTING.md](./CONTRIBUTING.md)

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) file for details