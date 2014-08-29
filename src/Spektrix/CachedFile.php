<?php

namespace Spektrix;

class CachedFile extends Base {
  
  public $file_name;
  public $full_path_to_file;
  public $path_to_cache;
  
  const ONE_DAY = 86400;
  
  public function __construct($resource, $params){
    $this->path_to_cache = getenv('PATH_TO_CACHE');
    $this->file_name = $this->build_file_name($resource, $params);
    $this->full_path_to_file = $this->build_full_path();
  }
  
  /**
  * Stores a file in the cache
  *
  * @return boolean - was the file successfully written to the cache?
  * @access public
  */
  public function store($some_data){
    if($some_data){
      file_put_contents($this->full_path_to_file, $some_data);
    }
  }
  
  /**
  * Retrieves a file from the cache
  *
  * @return string - the contents of the file
  * @access public
  */
  public function retrieve(){
    return file_get_contents($this->full_path_to_file);
  }
  
  /**
  * Checks if file exists in cache directory
  *
  * @return boolean
  * @access public
  */
  public function is_cached(){
    return file_exists($this->full_path_to_file);
  }
  
  /**
  * Checks if file is less than a day old
  *
  * @return boolean
  * @access public
  */
  public function is_fresh(){
    $yesterday = time() - self::ONE_DAY;
    return filemtime($this->full_path_to_file) > $yesterday;
  }
  
  /**
  * Checks if file exists in cache directory
  * and is less than 24 hours old
  *
  * @return boolean
  * @access public
  */
  public function is_cached_and_fresh(){
    return $this->is_cached() && $this->is_fresh();
  }
  
  /**
  * Builds file_name
  *
  * @return string – the full path of the file
  * @access private
  */
  public function build_file_name($resource, $params){
    $ext = '.txt';
    if(empty($params)):
      return $resource.$ext;
    else:
      $params_string = '';
      foreach($params as $k => $v):
        if($k == 'instance_start_from' || $k == 'instance_start_to'){
          $v = substr($v,0,10);
        }
        $params_string .= '_' . $k . '_' . $v;
      endforeach;
      return $resource.$params_string.$ext;
    endif;
  }
  
  /**
  * Builds full path to file
  *
  * @return string – the full path of the file
  * @access private
  */
  private function build_full_path(){
    if($this->path_to_cache){
      return $this->path_to_cache . $this->file_name;
    } else {
      return $this->path_to_theme() . '/cache/' . $this->file_name;
    }
  } 
}