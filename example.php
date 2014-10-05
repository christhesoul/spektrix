<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);

echo '<pre>';

$shows = new Spektrix\ShowCollection();
$shows->grouped_by_month();
foreach($shows->data as $month => $shows){
  echo date('M Y', strtotime($month)) . ' -> ' . count($shows) . '<br>';
}