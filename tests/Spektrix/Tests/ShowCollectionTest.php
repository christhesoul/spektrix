<?php

class ShowCollectionTest extends \PHPUnit_Framework_TestCase
{
  protected $events_xml_obj;
  
  protected function setUp()
  {
    $events_xml = file_get_contents(__DIR__ . '/fixtures/events.txt');
    $events_xml_obj = simplexml_load_string($events_xml);
    $this->events = new Spektrix\ShowCollection($events_xml_obj);
  }
  
  public function testCount()
  {
    $this->assertEquals(57, $this->events->data->count());
  }
  
  public function testShowIdIsArrayIndex()
  {
    $this->assertEquals(19078, $this->events->data[19078]->id);
  }
  
  public function testAsIds()
  {
    $this->assertCount(57, $this->events->as_ids());
    $this->assertEquals(19078, reset($this->events->as_ids()));
  }
}