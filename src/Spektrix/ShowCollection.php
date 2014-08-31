<?php
namespace Spektrix;

use ArrayObject;

class ShowCollection extends Base
{
  public $data;
  
  public function __construct($shows = NULL)
  {
    $events_xml = $this->load_and_clean_xml($shows);
    $this->data = new ArrayObject($this->collect_shows_from_xml($events_xml));
  }
  
  public function as_ids()
  {
    return array_keys($this->data->getArrayCopy());
  }
  
  private function collect_shows_from_xml($events_xml)
  {
    $shows = array();
    foreach($events_xml as $event){
      $show = new Show($event);
      $shows[$show->id] = $show;
    }
    return $shows;
  }
  
  private function load_and_clean_xml($shows = NULL)
  {
    if(isset($shows)){
      $events_xml = $shows;
    } else {
      parent::__construct();
      $events_xml = $this->get_xml_object('events')->Event;
    }
    $events_xml = property_exists($events_xml, 'Event') ? $events_xml->Event : $events_xml; 
    return $events_xml;
  }
}