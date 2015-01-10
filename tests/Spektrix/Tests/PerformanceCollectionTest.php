<?php
date_default_timezone_set("Europe/London");

class PerformanceCollectionTest extends \PHPUnit_Framework_TestCase
{
  protected $instances_xml_obj;

  protected function setUp()
  {
    $instances_xml = file_get_contents(__DIR__ . '/fixtures/instances.txt');
    $instances_xml_obj = simplexml_load_string($instances_xml);
    $this->performances = new Spektrix\PerformanceCollection($instances_xml_obj);
  }

  public function testCount()
  {
    $this->assertEquals(822, $this->performances->data->count());
  }

  public function testShowPerformanceCount()
  {
    $grouped = $this->performances->group_by_show();
    $this->assertEquals(12, count($grouped->data[22675]));
  }

}