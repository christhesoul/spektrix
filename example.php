<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);

echo '<pre>';
$shows = new Spektrix\ShowCollection();
print_r($shows->with_ids(array(19078,18877)));