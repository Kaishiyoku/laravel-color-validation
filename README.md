# Validate colors with Laravel 5

This package will let you validate that a certain value is a valid CSS color string.

## Installation

Install via [composer](https://getcomposer.org/) - In the terminal:
```bash
composer require kaishiyoku/laravel-color-validation
```
If you're not Using Laravel 5.7+ and don't have package auto-discovery add the following to the `providers` array in your `config/app.php`
```php
Kaishiyoku\Validation\Color\ServiceProvider::class,
```

## Usage

```php
// Test any color type
Validator::make(['test' => '#454ACF'], ['test' => 'color']);

// Test for rgb 
Validator::make(['test' => 'rgb(0, 200, 150)'], ['test' => 'color_rgb']);

// Test for rgba 
Validator::make(['test' => 'rgba(0, 200, 150, 0.52)'], ['test' => 'color_rgba']);

// Test for hex 
Validator::make(['test' => '#333'], ['test' => 'color_hex']);
```
## Using other locales

By default English and German locales can be used. If you're using a different locale you will have to add a `validation.php` file and a custom folder named after the locale code (e.g. "ja" for Japanese) to the `/resources/vendor/color_validation/` folder.

Example folder structure:

```
.
    └── resources
        └── lang
            └── vendor
                └── color_validation
                    ├── de
                    ├── en
                    └── japanese
```


## Adjusting existing locales

If you want to change any of the existing translations, you can publish the locale files with:

```bash
php artisan vendor:publish --provider="Kaishiyoku\Validation\Color\ServiceProvider"
```
