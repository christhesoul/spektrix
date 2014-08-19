<?php

namespace Spektrix;

class Base
{
  protected $wp_theme;

  private $api_key;
  private $certificate_path;
  private $key_path;
  private $api_url;
  
  /**
    * Create a new connect to Spektrix
    */
  public function __construct()
  {
    $this->api_key = getenv('SPEKTRIX_API_KEY');
  }

}