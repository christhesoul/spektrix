<?php
namespace Spektrix;

use ArrayObject;

class AvailabilityCollection extends Base
{
  public $data;

  public function __construct($availabilities = NULL)
  {
    $availabilities_xml = $this->load_and_clean_xml($availabilities);
    $this->data = new ArrayObject($this->collect_availabilities_from_xml($availabilities_xml));
    return $this;
  }

  public function filter_by_show($performance_ids)
  {
    $show_availabilities = array_filter($this->data->getArrayCopy(), function($availability) use ($performance_ids) {
      return in_array($availability->instance_id, $performance_ids);
    });
    return $show_availabilities;
  }

  private function collect_availabilities_from_xml($availabilities_xml)
  {
    $availabilities = array();
    foreach($availabilities_xml as $availability){
      $availability = new Availability($availability);
      $availabilities[$availability->instance_id] = $availability;
    }
    return $availabilities;
  }

  private function load_and_clean_xml($availabilities = NULL)
  {
    if(isset($availabilities)){
      $availabilities_xml = $availabilities;
    } else {
      parent::__construct();
      $availabilities_xml = $this->get_xml_object('instance-status')->InstancePlanStatus;
    }
    $availabilities_xml = property_exists($availabilities_xml, 'InstancePlanStatus') ? $availabilities_xml->InstancePlanStatus : $availabilities_xml;
    return $availabilities_xml;
  }
}