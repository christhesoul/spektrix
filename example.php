<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);

$hello = new Spektrix\Base();
var_dump($hello);