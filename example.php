<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);
Dotenv::required(
  [
    'SPEKTRIX_API_KEY',
    'SPEKTRIX_CERTIFICATE_PATH',
    'SPEKTRIX_KEY_PATH',
    'SPEKTRIX_API_URL'
  ]
);

echo '<pre>';

$price_lists = new \Spektrix\PriceListCollection();
print_r($price_lists);
