<?php

namespace Spektrix;

class iFrame
{
  public $url;
  
  private $page_name;
  private $params_string;
  public $secure;
  public $stylesheet;
  
  private $secure_prefix;
  private $insecure_prefix;
  
  function __construct($page_name, $params = false, $secure = false){
    $this->page_name = strtolower($page_name);
    $this->params_string = $params ? http_build_query($params) . '&' : '';
    $this->secure = $secure;
    $this->secure_prefix = getenv('SECURE_IFRAME_PATH');
    $this->insecure_prefix = getenv('INSECURE_IFRAME_PATH');
  }
  
  public function set_stylesheet($string)
  {
    $this->stylesheet = $string;
    return $this;
  }
  
  public function iframe_url(){
    return $this->prefix($this->secure) . $this->page_name . '.aspx?' . $this->params_string . 'stylesheet=' . $this->stylesheet . '&resize=true';
  }
  
  public function render_iframe(){
    return '<iframe name="SpektrixIFrame" id="SpektrixIFrame" src="' . $this->iframe_url() . '" frameborder="0" height="400" width="100%"></iframe>';
  }
  
  public function is_insecure(){
    return !$this->secure;
  }
  
  private function prefix($secure = false){
    return $secure ? $this->secure_prefix : $this->insecure_prefix;
  }
}