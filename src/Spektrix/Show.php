<?php
/**
 * A Show is an Event in Spektrix
 * From their API docs:
 * "An event in Spektrix can be thought of as a ‘show’. It encapsulates the descriptive data about an event, such as its Name and Description. It is a container for instances."
 */
namespace Spektrix;

class Show extends Base
{
  public $id;
  public $wp_id;
  public $name;
  public $short_description;
  public $long_description;
  public $image_url;
  public $image_thumb;
  public $duration;
  public $is_on_sale;

  public $related_events;
  public $tags;

  public $venue;
  public $season;
  private $account_code;
  private $sales_type;
  private $vat_code_for_accounts;

  function __construct($event)
  {

    if(!is_object($event)) {
      parent::__construct();
      $event = $this->get_show_from_spektrix($event);
    } else if(property_exists($event, 'Event')) {
      $event = $event->Event;
    }

    $this->id = (integer) $event->attributes()->id;
    $this->name = (string) $event->Name;
    $this->short_description = (string) $event->Description;
    $this->long_description = (string) $event->HTMLDescription;
    $this->image_url = (string) $event->ImageUrl;
    $this->image_thumb = (string) $event->ThumbnailUrl;
    $this->is_on_sale = (boolean) $event->IsOnSale;
    $this->duration = (integer) $event->Duration;

    $this->related_events = (array) $this->related_events_ids($event);
    $this->tags = (array) $this->tags_array($event);
    $this->set_misc_attributes($event);
  }

  public function tags_as_class()
  {
    $classes = implode(',',$this->tags);
    $classes = str_replace(' ','-',$classes);
    $classes = str_replace(',',' ',$classes);
    return $classes;
  }

  public function has_tag($tag)
  {
    return in_array($tag, $this->tags);
  }

  private function get_show_from_spektrix($id){
    return $this->get_xml_object('events',array('event_id'=>$id))->Event;
  }

  private function tags_array($event)
  {
    $tags = array();
    foreach($event->Attribute as $object){
      $name = (string) $object->Name;
      $name = strtolower($name);
      if((int) $object->Value == 1){
        $tags[] = $name;
      }
    }
    return $tags;
  }

  private function related_events_ids($event)
  {
    $related_events = array();
    if($event->RelatedEvents){
      foreach($event->RelatedEvents as $related_event){
        if($related_event){
          $related_events[] = (integer) $related_event->Event->attributes()->id;
        }
      }
    }
    return $related_events;
  }

  private function set_misc_attributes($event)
  {
    foreach($event->Attribute as $object){
      $name = (string) $object->Name;
      $name = strtolower($name);
      $name = str_replace(' ','_',$name);
      $this->$name = (string) $object->Value;
    }
  }

  static function find_all()
  {
    $api = new Spectrix();
    $shows = $api->get_events();
    return $api->collect_shows($shows);
  }

  static function find_all_in_future()
  {
    $api = new Spectrix();
    $eternity = time() + (60 * 60 * 24 * 7 * 500);
    $shows = $api->get_shows_until($eternity);
    return $api->collect_shows($shows);
  }

  static function this_week()
  {
    $api = new Spectrix();
    $next_week = time() + (60 * 60 * 24 * 7);
    $shows = $api->get_shows_until($next_week);
    return $api->collect_shows($shows);
  }

  static function six_weeks()
  {
    $api = new Spectrix();
    $six_weeks = time() + (60 * 60 * 24 * 7 * 6);
    $shows = $api->get_shows_until($six_weeks);
    return $api->collect_shows($shows);
  }

  function get_price_lists()
  {
    $pricelists = $this->get_price_list_for_show($this->id);
    $collection = array();
    foreach($pricelists->PriceList as $pl){
      $collection[] = new PriceList($pl);
    }
    return $collection;
  }

  public function is_in_past()
  {
    $last_performance = end($this->performances);
    $now = new \DateTime();
    return $now > $last_performance->start_time;
  }

  public function performance_range($prefix = true){
    $performances = $this->performances;
    if($prefix){
      $string = !$this->is_in_past() ? "Showing " : "Performed ";
    } else {
      $string = "";
    }

    $from = '';
    $to = '';
    $first_show = reset($performances)->start_time->format('D j');
    $first_show_month = reset($performances)->start_time->format('M');
    $first_show_year = reset($performances)->start_time->format('Y');
    $last_show = end($performances)->start_time->format('D j');
    $last_show_month = end($performances)->start_time->format('M');
    $last_show_year = end($performances)->start_time->format('Y');

    if($first_show == $last_show){
      $from .= $first_show . ' ' . $first_show_month;
    } else {
      $to = $last_show . ' ' . $last_show_month;
      if($first_show_month == $last_show_month){
        $from = $first_show;
      } else {
        if($first_show_year == $last_show_year){
          $from = $first_show . ' ' . $first_show_month;
        } else {
          $from = $first_show . ' ' . $first_show_month;
        }
      }
    }

    $from = str_replace(' ','&nbsp;',$from);
    $to = str_replace(' ','&nbsp;',$to);

    if($to == ''){
      if($prefix) $string.= 'on ';
      $string.= $from;
    } else {
      if($prefix) $string.= 'from ';
      $string.= $from . ' &mdash; ' . $to;
    }
    return $string;
  }

  public function performance_months()
  {
    return array_unique(array_map(function($performance) { return $performance->start_time->format('Y-m-01'); }, $this->performances));
  }
}