<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);

echo '<pre>';

$show = new Spektrix\Show(19078);
$show->add_performances();
print_r($show);

