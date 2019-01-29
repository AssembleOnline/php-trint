

## Introduction

PHPTrint is a PHP library that interacts with the [Trint](https://app.trint.com) API to push audio/video files for transcription and list transcribed file contents.

Their API docs can be found at: [https://dev.trint.com](https://dev.trint.com)

This package was developed to both serve as a plain PHP Client as well as integrate with [Laravel Framework](https://laravel.com/), as such it will register a `TrintServiceProvider` and alias the singleton instance to `Trint`

## Installation

To get started with PHPTrint, simply run:
```sh
composer require assemble/php-trint
```

### Laravel

To publish the config file, run:
```sh
php artisan vendor:publish --provider="Assemble\PHPTrint\TrintServiceProvider"
```


## Basic Usage

### PHP Instantiation

Firstly create an instance of the `Client`
```php
use Assemble\PHPTrint\Client as TrintClient;

// create an instance of trint client
$client = new TrintClient(["api-key" => "<your-key>"]);
```

### Laravel Facade

This package registers a laravel singleton instance and a facade accessor for this instance
```php
use Trint;

$res = Trint::put("/path/to/file.mp4"); // upload
$res = Trint::list();                   // list
$res = Trint::get("TrintId");           // content
```

### General Usage

Pushing a file to trint, available params can be found [here](https://dev.trint.com/reference#upload)
```php
// push a file to trint 
$client->put("/path/to/file.mp4", [...params]); 
```
Listing transcriptions on your account
```php
//listing transcriptions
$limit = 10; // number of results returned
$skip = 2 // offset from start of list
$client->list($limit, $skip); 
// OR just
$client->list(); 
```
Reading a single Trint file contents, available formats are: [srt](https://dev.trint.com/reference#srttrintid), [webvtt](https://dev.trint.com/reference#webvtttrintid), [edl](https://dev.trint.com/reference#edltrintid), [docx](https://dev.trint.com/reference#docxtrintid), [xml](https://dev.trint.com/reference#xmltrintid), [json](https://dev.trint.com/reference#jsontrintid)
```php
// get trint data as string
$data = $client->get("TrintId", "json", [...params]);
```

## License

PHPTrint is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
