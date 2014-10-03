<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);

echo '<pre>';
$shows = new Spektrix\ShowCollection();
foreach($shows->with_ids(array(19078,18877)) as $show){
  $show->wp_id = 2;
  print_r($show);
}