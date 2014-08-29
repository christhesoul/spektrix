<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);

echo '<pre>';
$show = new Spektrix\Show(19078);

print_r($show);
//print_r($hello);