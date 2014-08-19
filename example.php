<?php

require('vendor/autoload.php');
Dotenv::load(__DIR__);

$hello = new Spektrix\Base();
var_dump($hello);