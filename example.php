<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);

echo '<pre>';
$shows = new Spektrix\ShowCollection();
$shows->with_performances();

foreach($shows->data as $show){
  echo $show->name . '<br>';
  echo implode(', ', $show->performance_months()) . '<br>';
}