<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);

echo '<pre>';

$shows = new Spektrix\ShowCollection();
$tags = $shows->upcoming()->tags_from_shows();

print_r($tags);

