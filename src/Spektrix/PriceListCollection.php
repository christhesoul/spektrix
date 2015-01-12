<?php
namespace Spektrix;

use ArrayObject;

class PriceListCollection extends Base
{
  public $data;

  public function __construct($price_lists = NULL)
  {
    $price_lists_xml = $this->load_and_clean_xml($price_lists);
    $this->data = new ArrayObject($this->collect_price_lists_from_xml($price_lists_xml));
    return $this;
  }

  private function collect_price_lists_from_xml($price_lists_xml)
  {
    $price_lists = array();
    foreach($price_lists_xml as $price_list){
      $price = new PriceList($price_list);
      $price_lists[$price->id] = $price;
    }
    return $price_lists;
  }

  private function load_and_clean_xml($price_lists = NULL)
  {
    if(isset($price_lists)){
      $price_lists_xml = $price_lists;
    } else {
      parent::__construct();
      $price_lists_xml = $this->get_xml_object('price-lists');
    }
    $price_lists_xml = property_exists($price_lists_xml, 'PriceList') ? $price_lists_xml->PriceList : $price_lists_xml;
    return $price_lists_xml;
  }
}