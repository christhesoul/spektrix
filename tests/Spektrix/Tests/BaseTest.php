<?php

class BaseTest extends \PHPUnit_Framework_TestCase
{
  /**
    * Test that true does in fact equal true
    */
  public function testConstructorAssignsVars()
  {
    $call = new Spektrix\Base();
    $this->assertEquals('abc-123', PHPUnit_Framework_Assert::readAttribute($call, 'api_key'));
    $this->assertEquals('/foo/bar/fuzz', PHPUnit_Framework_Assert::readAttribute($call, 'certificate_path'));
    $this->assertEquals('/fuzz/bar/foo', PHPUnit_Framework_Assert::readAttribute($call, 'key_path'));
    $this->assertEquals('http://spektrix.com/', PHPUnit_Framework_Assert::readAttribute($call, 'api_url'));
    
  }
  
  public function testBuildUrl()
  {
    $call = new Spektrix\Base();
    $this->assertEquals('http://spektrix.com/events?api_key=abc-123&all=true', $call->build_url('events'));
  }
  
  
}