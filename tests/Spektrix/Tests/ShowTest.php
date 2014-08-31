<?php

class ShowTest extends \PHPUnit_Framework_TestCase
{
  protected $show_xml_obj;
  
  protected function setUp()
  {
    $show_xml = file_get_contents(__DIR__ . '/fixtures/show.txt');
    $this->show_xml_obj = simplexml_load_string($show_xml);
    $this->show = new Spektrix\Show($this->show_xml_obj);
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
  
}