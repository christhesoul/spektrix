<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);

echo '<pre>';

$price_lists = new \Spektrix\PriceListCollection();
print_r($price_lists);