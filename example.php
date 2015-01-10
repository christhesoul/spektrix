<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);

echo '<pre>';

$show = new \Spektrix\Show(22675);
print_r($show->performances());