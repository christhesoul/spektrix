<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);

echo '<pre>';
$shows = new Spektrix\ShowCollection();

foreach($shows->with_tag('spill festival') as $show){
  echo '<h1>' . $show->name . '</h1>';
}
//print_r($hello);