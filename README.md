**The functionality of this package is built into Laravel 5.5 and above, only install this in older Laravel versions**

# Use short class names in an Artisan Tinker session

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-tinker-tools.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-tinker-tools)
[![Build Status](https://img.shields.io/travis/spatie/laravel-tinker-tools/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-tinker-tools)
[![StyleCI](https://styleci.io/repos/91980495/shield?branch=master)](https://styleci.io/repos/91980495)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-tinker-tools.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-tinker-tools)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-tinker-tools.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-tinker-tools)

When using Artisan's Tinker command it can be quite bothersome having to type the fully qualified classname to do something simple.

```php
\App\Models\NewsItem::first();
```

This package contains a class that, when fully installed lets you use the short class names:

```php
NewsItem::first();
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-tinker-tools.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-tinker-tools)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

First install the package via Composer:

``` bash
composer require spatie/laravel-tinker-tools
```

Next, create a file named `.psysh.php` in the root of your Laravel app with this content:

```php
<?php

\Spatie\TinkerTools\ShortClassNames::register();
```

Finally, dump the optimized version of the autoloader so `autoload_classmap.php` gets created:

```bash
composer dump-autoload -o
```

## Usage

Open up a Tinker session with:

```bash
php artisan tinker
```

Inside that Tinker session you can now use short class names:

```php
NewsItem::first();
```

## A peek behind the curtains

When you use a class that hasn't been loaded in yet, PHP will call the registered autoloader functions. Such autoloader functions are responsible for loading up the requested class. In a typical project Composer will register an autoloader function that can `include` the file where the class is stored in.

Composer has a few ways to locate the right files. In most cases it will convert the fully qualified class name to a path. For example, when using a class `App\Models\NewsItem` Composer will load the file in `app/Models/NewsItem.php`. It's a bit more complicated behind the scenes but that's the gist of it. To make the process of finding a class fast, Composer caches all the fully qualified classnames and their paths in the generated `autoload_classmap.php`, which can be found in `vendor/composer`. 

Now, to make this package work, `\Spatie\TinkerTools\ShortClassNames` will read Composer's `autoload_classmap.php` and [convert the fully qualified class names to short class names](https://github.com/spatie/laravel-tinker-tools/blob/d3a3287/src/ShortClassNames.php#L23). The result is a collection that's being kept in [the `$classes` property](https://github.com/spatie/laravel-tinker-tools/blob/098e595/src/ShortClassNames.php#L8)

Our class will also [register an autoloader](https://github.com/spatie/laravel-tinker-tools/blob/098e595/src/ShortClassNames.php#L33). When you use `NewsItem` in your code. PHP will first call Composer's autoloader. But of course that autoloader can't find the class. So the autoloader from this package comes next. Our autoloader will use the aforementioned `$classes` collection to find to fully qualified class name. It will then [use `class_alias`](https://github.com/spatie/laravel-tinker-tools/blob/098e595/src/ShortClassNames.php#L46) to alias `NewsItem` to `App\Models\NewsItem`.

## What happens if there are multiple classes with same name?

Now you might wonder what'll happen it there are more classes with the same name in different namespaces? E.g. `App\Models\NewsItem`, `Vendor\PackageName\NewsItem`. Well, `autoload_classmap.php` is sorted alphabetically on the fully qualified namespace. So `App\Models\NewsItem` will be used and not `Vendor\PackageName\NewsItem`.

Because `App` starts with an "A" there's a high chance that, in case of a collision, a class inside your application will get picked. Currently there are no ways to alter this. I'd accept PRs that make this behaviour customizable.

## Need more Tinker magic?

There are a lot of other options that can be set in `tinker.config.php`. Learn all the options by reading [the official psysh configuration documentation](http://psysh.org/#configure).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Kruikstraat 22, 2018 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

We got the idea for `ShortClassnames` by reading the "Tailoring Tinker with custom config" section of [Caleb Porzio](https://twitter.com/calebporzio)'s excellent blogpost "[Supercharge Your Laravel Tinker Workflow](https://blog.tighten.co/supercharge-your-laravel-tinker-workflow)".

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.