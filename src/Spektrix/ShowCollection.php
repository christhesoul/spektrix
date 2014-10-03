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

  public function with_tag($tag)
  {
    return array_filter($this->data->getArrayCopy(), function($show) use ($tag) { return $show->has_tag($tag); });
  }

  public function as_ids()
  {
    return array_keys($this->data->getArrayCopy());
  }

  public function with_ids($array_of_ids)
  {
    if(is_array($array_of_ids)){
      return array_filter($this->data->getArrayCopy(), function($show) use ($array_of_ids) { return in_array($show->id, $array_of_ids); });
    } else{
      throw new \Exception('Method with_ids($array) expects argument to be array, got ' . gettype($array_of_ids));
    }
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