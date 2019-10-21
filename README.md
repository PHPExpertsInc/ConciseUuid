# ConciseUuid

ConciseUuid is an Eloquent Model that uses a concise UUIDv4 as the primary key.

A normal UUID is 36 characters and looks like: 

    d318fb95-5b49-47ca-abd5-326a60524e70
    
This is very long, and in base16, there is a lot of unnecessary space.

ConciseUuid takes this, strips out the "-" and converts the base16 into base62 (0-9, a-z, A-Z).
Here is the Concise version of the above UUID:

    Old: d318fb95-5b49-47ca-abd5-326a60524e70
    New: 6QKnU3XheQMk3E6Vq1B4l6
    
As you can see, it is much more concise: 15 characters less!

Because of how the UUIDv4 algorithm is specified, a UUID will never begin with anything
other than a number (0-9). So if you want a special class of UUIDs, pass in `true`. These
UUIDs will *always* begin with a letter, letting you quickly differentiate them from
normal UUIDs.

    echo ConciseUuid::generateNewId(true);
    // Output: rEBzkc6s67JU3kI7ZuA7TU

## Usage

    SQL: 
    CREATE TABLE my_model (id char(22) primary key);

    PHP:
    namespace PHPExperts\ConciseUuid\ConciseUuidModel;

    class MyModel extends ConciseUuidModel
    {
    }

    For your users table:
    namespace PHPExperts\ConciseUuid\ConciseUuidAuthModel;

    class User extends ConciseUuidAuthModel
    {
    }

## Installation

Via Composer

``` bash
$ composer require phpexperts/conciseuuid
```

Having the GMP extension enabled **really** improves the execution time of this package.

## Usage

## Change log

Please see the [changelog](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ phpunit
```

## Contributing

Please take a look at [contributing.md](contributing.md) if you want to make improvements.


## Credits

- [Theodore R. Smith](https://www.phpexperts.pro/])
- [Rishi Ramawat](https://github.com/rishi-ramawat)
- [Smijo Thekkudan](https://github.com/smijo149)

## License

MIT license. Please see the [license file](license.md) for more information.


[ico-version]: https://img.shields.io/packagist/v/phpexperts/conciseuuid.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/phpexperts/conciseuuid.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/phpexperts/conciseuuid/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/phpexperts/conciseuuid
[link-downloads]: https://packagist.org/packages/phpexperts/conciseuuid
[link-travis]: https://travis-ci.org/phpexperts/conciseuuid
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/phpexperts
[link-contributors]: ../../contributors]
