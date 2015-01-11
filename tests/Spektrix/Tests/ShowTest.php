<?php

class ShowTest extends \PHPUnit_Framework_TestCase
{
  protected $show_xml_obj;

  protected function setUp()
  {
    $this->load_show();
    $this->load_show_with_performances_stubbed();
  }

  public function testName()
  {
    $this->assertEquals('A Farewell To Arms', $this->show->name);
  }

  public function testIsOnSale()
  {
    $this->assertTrue($this->show->is_on_sale);
  }

  public function testTagsAsClass()
  {
    $this->assertEquals('different drama', $this->show->tags_as_class());
  }

  public function testHasTag()
  {
    $this->assertTrue($this->show->has_tag('different'));
  }

  public function testPerformanceMock()
  {
    $this->assertEquals($this->grouped->data[22675], $this->show_with_performances->performances());
  }

  public function testFirstPerformances()
  {
    $this->assertEquals('12 February', $this->show_with_performances->first_performance()->start_time->format('j F'));
  }

  public function testPerformanceRange()
  {
    $this->assertEquals('Showing from Thu&nbsp;12 &mdash; Sat&nbsp;21&nbsp;Feb', $this->show_with_performances->performance_range());
  }

  public function testPerformanceCount()
  {
    $this->assertEquals(12, $this->show_with_performances->performance_count());
  }

  public function testAvailabilityCountMatchesPerformanceCount()
  {
    $this->assertEquals($this->show_with_performances->performance_count(), count($this->show_with_performances->availabilities()));
  }

  public function testIsBlockbuster()
  {
    $this->assertTrue($this->show_with_performances->is_blockbuster());
  }


  private function load_show()
  {
    $show_xml = file_get_contents(__DIR__ . '/fixtures/show.txt');
    $this->show_xml_obj = simplexml_load_string($show_xml);
    $this->show = new Spektrix\Show($this->show_xml_obj);
  }

  private function load_show_with_performances_stubbed()
  {
    $instances_xml = file_get_contents(__DIR__ . '/fixtures/instances.txt');
    $instances_xml_obj = simplexml_load_string($instances_xml);
    $this->performances = new Spektrix\PerformanceCollection($instances_xml_obj);
    $this->grouped = $this->performances->group_by_show();

    $performances = $this->grouped->data[22675];
    $performance_ids = array_map(function($p) { return $p->id; }, $performances);

    $availabilities_xml = file_get_contents(__DIR__ . '/fixtures/instance-status.txt');
    $availabilities_xml_obj = simplexml_load_string($availabilities_xml);
    $availability_collection = new Spektrix\AvailabilityCollection($availabilities_xml_obj);
    $filtered_availability = $availability_collection->filter_by_show($performance_ids);

    $stub = $this->getMockBuilder('\Spektrix\Show')
      ->disableOriginalConstructor()
      ->setMethods(array('performances','availabilities'))
      ->getMock();
    $stub->expects($this->any())->method('performances')->willReturn($performances);
    $stub->expects($this->any())->method('availabilities')->willReturn($filtered_availability);
    $this->show_with_performances = $stub;
  }

}