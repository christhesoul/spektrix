# Spektrix PHP Wrapper

A wrapper for the Spektrix API

## Install

Via Composer

``` json
{
    "require": {
        "christhesoul/spektrix": "~1.0"
    }
}
```

## Configuration

You will need a few things:

* Spektrix API key
* Valid Spektrix .crt file
* Valid Spektrix .key file
* The API URL endpoint for your customer

``` php
// 1. Composer magic
require('vendor/autoload.php');
// 2. Load your secret stuff
Dotenv::load(__DIR__);
// 3. Throw an error if your secret stuff falls short (see 2)
Dotenv::required(
  [
    'SPEKTRIX_API_KEY',
    'SPEKTRIX_CERTIFICATE_PATH',
    'SPEKTRIX_KEY_PATH',
    'SPEKTRIX_API_URL'
  ]
);
```

## Testing

``` bash
$ phpunit
```


## Contributing

Please see [CONTRIBUTING](https://github.com/thephpleague/:package_name/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Chris Waters](https://github.com/christhesoul)
- Originally built for the [New Wolsey Theatre](http://www.wolseytheatre.co.uk)

## License

The MIT License (MIT). Please see [License File](https://github.com/christhesoul/spektrix/blob/master/LICENSE) for more information.
