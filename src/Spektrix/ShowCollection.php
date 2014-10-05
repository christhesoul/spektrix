<?php
namespace Spektrix;

use ArrayObject;

class ShowCollection extends Base
{
  public $data;

  public function __construct($shows = NULL)
  {
    $events_xml = $this->load_and_clean_xml($shows);
    $this->data = new ArrayObject($this->collect_shows_from_xml($events_xml));
  }
  
  public function sort_by_first_performance()
  {
    $shows = $this->data->getArrayCopy();
    usort($shows, function($a, $b) {
      return $a->performances[0]->start_time->format('U') - $b->performances[0]->start_time->format('U');
    });
    $this->data = new ArrayObject($shows);
    return $this;
  }
  
  /**
    * Groups shows by month, so you can display all shows for January, February etc.
    * The key is in the format 2014-04-01 (first day of month)
    * This means you can strtotime($key) and format accordingly
    *
    * @return object ShowCollection object but with shows grouped by month
    */
  
  public function grouped_by_month()
  {
    $by_month = array();
    foreach($this->data as $show){
      foreach($show->performance_months() as $month){
        $by_month[$month][] = $show;
      }
    }
    ksort($by_month);
    $this->data = new ArrayObject($by_month);
    return $this;
  }

  public function with_tag($tag)
  {
    return array_filter($this->data->getArrayCopy(), function($show) use ($tag) { return $show->has_tag($tag); });
  }

  public function as_ids()
  {
    return array_keys($this->data->getArrayCopy());
  }

  public function with_ids($array_of_ids)
  {
    if(is_array($array_of_ids)){
      return array_filter($this->data->getArrayCopy(), function($show) use ($array_of_ids) { return in_array($show->id, $array_of_ids); });
    } else{
      throw new \Exception('Method with_ids($array) expects argument to be array, got ' . gettype($array_of_ids));
    }
  }
  
  /**
    * Loads all performances and groups them by show
    * (which means the array keys are show ids)
    * and then associates each array of performance with a show.
    * This means you $show->performances will return an array of performances.
    *
    * @return object ShowCollection object but now with performance info
    */
  
  public function with_performances()
  {
    $performances = new PerformanceCollection();
    $performances->group_by_show();
    foreach($this->data as $show){
      $show->performances = $performances->data[$show->id];
    }
    return $this;
  }

  /**
    * Takes an array of Wordpress posts that have a Spektrix ID as a custom meta
    * matches the Wordpress post to Spektrix show object, and then filters out
    * all Spektrix objects that aren't in the Wordpress database.
    * Each Spektrix $show will now have a wp_id attribute, i.e. $show->wp_id
    *
    * @param array $wp_posts Array of Wordpress shows
    * @param string $meta_key Your meta key for the Spektrix ID
    * @return array Shows in both Spektrix and Wordpress
    */

  public function map_shows_to_wp_array($wp_posts, $meta_key){
    foreach($wp_posts as $post){
      $spektrix_id = get_post_meta($post->ID, $meta_key, true);
      if(array_key_exists($spektrix_id, $this->data)){
        $this->data[$spektrix_id]->wp_id = $post->ID;
      }
    }
    $this->data = new ArrayObject(array_filter($this->data->getArrayCopy(), function($show) { return $show->wp_id; }));
    return $this;
  }

  private function collect_shows_from_xml($events_xml)
  {
    $shows = array();
    foreach($events_xml as $event){
      $show = new Show($event);
      $shows[$show->id] = $show;
    }
    return $shows;
  }

  private function load_and_clean_xml($shows = NULL)
  {
    if(isset($shows)){
      $events_xml = $shows;
    } else {
      parent::__construct();
      $events_xml = $this->get_xml_object('events')->Event;
    }
    $events_xml = property_exists($events_xml, 'Event') ? $events_xml->Event : $events_xml;
    return $events_xml;
  }
}