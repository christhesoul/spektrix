<?php
namespace Spektrix;

use ArrayObject;

class PerformanceCollection extends Base
{
  public $data;
  
  public function __construct($performances = NULL)
  {
    $performances_xml = $this->load_and_clean_xml($performances);
    $this->data = new ArrayObject($this->collect_performances_from_xml($performances_xml));
    return $this;
  }
  
  public function group_by_show()
  {
    $grouped_performances = array();
    foreach($this->data->getArrayCopy() as $performance){
      $grouped_performances[$performance->show_id][] = $performance;
    }
    $this->data = new ArrayObject($grouped_performances);
    return $this;
  }
  
  private function collect_performances_from_xml($performances_xml)
  {
    $performances = array();
    foreach($performances_xml as $instance){
      $performance = new Performance($instance);
      $performances[$performance->id] = $performance;
    }
    return $performances;
  }
  
  private function load_and_clean_xml($performances = NULL)
  {
    if(isset($performances)){
      $performances_xml = $performances;
    } else {
      parent::__construct();
      $performances_xml = $this->get_xml_object('instances')->Instance;
    }
    $performances_xml = property_exists($performances_xml, 'Instance') ? $performances_xml->Instance : $performances_xml;
    return $performances_xml;
  }
}