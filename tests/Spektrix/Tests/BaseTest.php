<?php

class BaseTest extends \PHPUnit_Framework_TestCase
{

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
    $fixture = new Spektrix\Base();
    $reflector = new ReflectionProperty('Spektrix\Base', 'resource');
    $reflector->setAccessible(true);
    $reflector->setValue($fixture, 'events');
    
    $method = new ReflectionMethod(
      'Spektrix\Base', 'build_url'
    );
    
    $method->setAccessible(TRUE);
    
    $this->assertEquals(
      'http://spektrix.com/events?api_key=abc-123&all=true', 
      $method->invoke($fixture)
    );
  }
  
  
}