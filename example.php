<?php
ini_set('display_errors', 1);

require('vendor/autoload.php');
Dotenv::load(__DIR__);

echo '<pre>';
$shows = new Spektrix\ShowCollection();

foreach($shows->with_tag('spill festival') as $show){
  echo '<h1>' . $show->name . '</h1>';
  $show_id = $show->id;
}

$spektrix_iframe_url = new Spektrix\iFrame('EventDetails',array('EventId' => $show_id));
echo $spektrix_iframe_url->render_iframe();