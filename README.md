# Potato-ORM

[![Latest Version on Packagist](https://img.shields.io/badge/packagist-v1.0.0-orange.svg)](https://packagist.org/packages/opeyemiabiodun/potato-orm)
[![Software License][ico-license]](LICENSE.md)
[![Build Status](https://travis-ci.org/andela-oogunjimi/Potato-ORM.svg?branch=master)](https://travis-ci.org/andela-oogunjimi/Potato-ORM)
[![Code Coverage](https://scrutinizer-ci.com/g/andela-oogunjimi/Potato-ORM/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/andela-oogunjimi/Potato-ORM/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/andela-oogunjimi/Potato-ORM/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/andela-oogunjimi/Potato-ORM/?branch=master)
[![Total Downloads][ico-downloads]][link-downloads]

###### CheckPoint 2 - Potato ORM
A Simple PHP ORM (Object Relational Mapper). PSR-2 coding standard was adopted in writing the package. The PSR-4 autoloading convention was also adopted.

## Install

Via Composer

``` bash
$ composer require opeyemiabiodun/potato-orm
```

## Usage

``` php

#Create a record from the database

$user = new User();
$user->name = "Tayo";
$user->address = "54, Kilani street, Akarigbo, Jiyanland.";
$user->phone = "07834531265";
$user->save();


#Find a record from the database

$user = User::find($id);


#Update a record

$user = User::find($id);
$user->address = "No. 1 Update grove, off The Past Street, Now Savedland.";
$user->save();


#Delete a record -- returns a boolean

$result = User::destroy($id):


#Find all users in the database - Returns object array

$users = User::getAll();

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email opeyemi.ogunjimi@andela.com instead of using the issue tracker.

## Credits

- [Opeyemi Ogunjimi][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/opeyemiabiodun/potato-orm.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/andela-oogunjimi/Potato-ORM/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/andela-oogunjimi/Potato-ORM.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/andela-oogunjimi/Potato-ORM.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/opeyemiabiodun/potato-orm.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/opeyemiabiodun/potato-orm
[link-travis]: https://travis-ci.org/andela-oogunjimi/Potato-ORM
[link-scrutinizer]: https://scrutinizer-ci.com/g/andela-oogunjimi/Potato-ORM/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/andela-oogunjimi/Potato-ORM
[link-downloads]: https://packagist.org/packages/opeyemiabiodun/potato-orm
[link-author]: https://github.com/andela-oogunjimi
[link-contributors]: ../../contributors
