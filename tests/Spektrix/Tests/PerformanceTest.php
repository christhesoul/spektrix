<?php
date_default_timezone_set("Europe/London");

class PerformanceTest extends \PHPUnit_Framework_TestCase
{
  protected $instances_xml_obj;

  protected function setUp()
  {
    $instances_xml = file_get_contents(__DIR__ . '/fixtures/instances.txt');
    $instances_xml_obj = simplexml_load_string($instances_xml);
    $this->performances = new Spektrix\PerformanceCollection($instances_xml_obj);
    $grouped = $this->performances->group_by_show();
    $this->performance = $grouped->data[22675][0];
  }

  public function testShowId()
  {
    $this->assertEquals(22675, $this->performance->show_id);
  }

  public function testStartTime()
  {
    $this->assertEquals('12 February', $this->performance->start_time->format('j F'));
  }

  public function testIsInPast()
  {
    $this->assertEquals(false, $this->performance->is_in_past());
  }

}