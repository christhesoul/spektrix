<?php
namespace Spektrix;

class PriceList extends Base
{
  public $id;

  private $prices;

  function __construct($price_list)
  {
    $this->id = (integer) $price_list->attributes()->id;

    foreach($price_list->Price as $price){
      $this->prices[] = $price;
    }
  }

  public function prices()
  {

  }
}