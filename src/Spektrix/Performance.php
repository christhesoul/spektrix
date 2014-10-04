<?php
namespace Spektrix;

class Performance extends Base
{
  public $id;
  public $start_time;
  public $start_time_unformatted;
  public $start_time_utc;
  public $start_selling_at;
  public $start_selling_at_utc;
  public $stop_selling_at;
  public $stop_selling_at_utc;
  
  public $pricelist_id;
  public $plan_id;
  public $show_id;
  
  public $is_on_sale;
  public $attributes;
  
  function __construct($performance)
  {
    //Load a performance by ID if not object loaded in
    if(!is_object($performance)){
      $performance = $this->get_performance($performance);
    }
    
    $this->id = (integer) $performance->attributes()->id;
    
    $this->start_time = new \DateTime((string) $performance->Start);
    $this->start_time_utc = new \DateTime((string) $performance->StartUtc);
    $this->start_selling_at = new \DateTime((string) $performance->StartSellingAt);
    $this->start_selling_at_utc = new \DateTime((string) $performance->StartSellingAtUtc);
    $this->stop_selling_at = new \DateTime((string) $performance->StopSellingAt);
    $this->stop_selling_at_utc = new \DateTime((string) $performance->StopSellingAtUtc);
    
    $this->start_time_unformatted = (string) $performance->Start;
    
    $this->show_id = (integer) $performance->Event['id'];
    $this->plan_id = (integer) $performance->Plan['id'];
    $this->pricelist_id = (integer) $performance->PriceList['id'];
    
    $this->is_on_sale = $performance->IsOnSale == 'true' ? true : false;
    
    $this->attributes = array();
    foreach($performance->Attribute as $attr):
      if($attr->Value != 0):
        $this->attributes[] = (string) $attr->Name;
      endif;
    endforeach;
  }
  
  static function find_all()
  {
    $api = new Spectrix();
    $performances = $api->get_performances();
    return $api->collect_performances($performances);
  }
  
  static function find_all_in_future($by_show = false)
  {
    $api = new Spectrix();
    $eternity = time() + (60 * 60 * 24 * 7 * 500);
    $performances = $api->get_performances_until($eternity);
    if($by_show) {
      return $api->collect_performances_by_show($performances);
    } else {
      return $api->collect_performances($performances);
    }
  }
  
  static function this_week()
  {
    $api = new Spectrix();
    $next_week = time() + (60 * 60 * 24 * 7);
    $performances = $api->get_performances_until($next_week);
    return $api->collect_performances($performances);
  }
  
  static function six_weeks()
  {
    $api = new Spectrix();
    $six_weeks = time() + (60 * 60 * 24 * 7 * 6);
    $performances = $api->get_performances_until($six_weeks);
    return $api->collect_performances($performances);
  }
  
  function get_price_list()
  {
    $pricelists = $this->get_price_list_for_performance($this->id);
    $collection = array();
    foreach($pricelists->PriceList as $pl){
      $collection[] = new PriceList($pl);
    }
    return $collection;
  }
  
  function is_accessible(){
    $accessible_performances = accessible_performance_types();
    $result = count(array_intersect($accessible_performances,$this->attributes)) ? true : false;
    return $result;
  }
  
  function end_time($show_duration,$format = 'G.i'){
    $unix_start = $this->start_time->format('U');
    $duration_seconds = convert_to_seconds($show_duration);
    $unix_end = $unix_start + $duration_seconds;
    return date($format,$unix_end);
  }
}

function get_price_list_for_performance($show_prices,$performance)
{
  foreach($show_prices as $sp){
    if($performance->pricelist_id == $sp->id) return $sp;
  }
}

function get_performance_months($performances){
  $months = array();
  foreach($performances as $performance){
    $months[] = $performance->start_time->format('F Y');
  }
  $months = array_unique($months);
  return $months;
}

function get_performance_dates($performances){
  $dates = array();
  foreach($performances as $performance){
    $dates[] = $performance->start_time->format('Ymd');
  }
  $dates = array_unique($dates);
  return $dates;
}

function any_accessible($performances){
  $any = false;
  foreach($performances as $performance){
    if($performance->is_accessible()){
      $any = true;
    }
  }
  return $any;
}