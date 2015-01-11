<?php
namespace Spektrix;

class Availability extends Base
{
  public $available;
  public $capacity;
  public $locked;
  public $reserved;
  public $selected;
  public $sold;

  function __construct($availability)
  {
    //Load a performance by ID if not object loaded in
    if(!is_object($availability)){
      $availability = $this->get_availability($availability);
    }

    $this->id = $availability->attributes()->id;

    $this->plan_id = (integer) $availability->Plan->attributes()->id;
    $this->instance_id = (integer) $availability->Instance->attributes()->id;

    $this->available = (integer) $availability->Available;
    $this->capacity = (integer) $availability->Capacity;
    $this->locked = (integer) $availability->Locked;
    $this->reserved = (integer) $availability->Reserved;
    $this->selected = (integer) $availability->Selected;
    $this->sold = (integer) $availability->Sold;
  }
}
/*

function availability_helper($av,$extremes)
{
  $big_house = 400;
  $no_need_for_best = 99;
  $best_availability = $av->capacity > 150 ? 50 : 20;
  $obj = (object) array(
    'small_house' => ($av->capacity < $big_house),
    'show_best' => ($av->capacity > $no_need_for_best) && ($extremes->lowest < $best_availability) && ($extremes->highest > $best_availability),
    'best_availability' => ($av->available > $best_availability) && ($extremes->lowest < $best_availability)
  );
  return $obj;
}

function book_online_class($av,$av_helper,$blockbuster = false)
{
  if($blockbuster && $av_helper->best_availability):
    return "btn btn-block btn-info";
  elseif($av->available === 0):
    return "btn btn-block btn-danger";
  else:
    return "btn btn-block btn-inverse";
  endif;
}

function book_online_text($av,$av_helper,$blockbuster = false)
{
  $string = '';
  if($av->available == 0):
    $string = 'Sold Out';
  else:
    $string = 'Book Online';
  endif;
  return $string;
}

function book_online_subtext($av,$av_helper,$blockbuster,$performance,$path_to_show)
{
  $string = '';
  if($av->available === 0):
    $string = '<strong>Really want tickets?</strong><br>There is hope; we sometimes get returns nearer the time. Please call 01473&nbsp;295900 to join the waiting&nbsp;list.';
  elseif($av->available == 1):
    $string = '<strong>Ridiculously limited availability!</strong><br>There is only one ticket left for this performance!';
  elseif($av->available < 20):
    $string = '<strong>Very limited availability!</strong><br>';
    if($av_helper->small_house):
      $string.= 'Only '.$av->available.' tickets left. ';
    else:
      $string.= 'Only '.$av->available.' tickets left, and it&#8217;s unlikely we&#8217;ll have seats together. ';
    endif;
    if($av_helper->show_best):
      $string.= is_singular('shows') ? '<a href="#" class="best-availability-shower">Show only best availability.</a>' : '';
      $string.= !is_singular('shows') && $blockbuster ? '<a href="'.$path_to_show.'#best-availability">Show only best availability.</a>' : '';
    endif;
  elseif($av->available < 40):
    $string = '<strong>Be quick!</strong><br>We only have ' . $av->available . ' tickets left for this performance.';
  elseif($blockbuster && $av_helper->best_availability):
    $string = '<strong>Best availability!</strong><br>';
    if($av_helper->small_house):
      $string.= 'Opportunity knocks!';
    else:
      $string.= 'Need tickets together? Opportunity knocks!';
    endif;
  endif;
  return $string;
}

function presale_info($performance)
{
  $href = site_url('/book-online/'.$performance->id);
  $now = current_time('timestamp');
  $unix_day = 60 * 60 * 24;
  $date_format = 'jS M \a\t ga';
  $presale_days = 7;
  $presale_begins = $performance->start_selling_at->format('U') - ($presale_days * $unix_day);
  $button_string = 'On sale ' . $performance->start_selling_at->format($date_format);
  $string = '<a href="#" class="btn btn-block btn-success">' . $button_string . '</a>';
  if($now >= $presale_begins){
    $string.= '<br>Presale now on for ' . '<a href="' . site_url('/support-us/friends/') . '">friends of the New Wolsey</a>';
    $string.= '<a class="btn btn-block btn-danger" href="'.$href.'" style="margin-top:10px;">Buy Presale Tickets</a>';
  } else {
    $string.= '<br>Presale begins ' . date($date_format,$presale_begins) . ' for ' . '<a href="' . site_url('/support-us/friends/') . '">friends of the New Wolsey</a>';
  }
  return $string;
}

function book_online_button($av,$av_helper,$performance,$blockbuster = false,$path_to_show = false)
{
  $now = new DateTime(current_time('mysql'));
  if($now < $performance->start_selling_at):
    echo presale_info($performance);
  elseif($now > $performance->stop_selling_at || !$performance->is_on_sale):
    echo '<a class="btn btn-block btn-warning">Call 01473 295900</a><br><span style="font-size:14px;"><strong>Sorry!</strong><br>This performance is not for sale online.</span>';
  else:
    $href = $av->available > 0 ? 'href="' . site_url('/book-online/'.$performance->id) . '"' : '';
    echo '<a ' . $href . ' class="' . book_online_class($av,$av_helper,$blockbuster) . '">' . book_online_text($av,$av_helper,$blockbuster) . '</a><br><span style="font-size:14px;">'. book_online_subtext($av,$av_helper,$blockbuster,$performance,$path_to_show) .'</span>';
  endif;
}

function availability_extremes($performances,$availabilities)
{
  $lowest = 1000000;
  $highest = 0;
  foreach($performances as $performance){
    $available = $availabilities[$performance->id]->available;
    $lowest = $available < $lowest ? $available : $lowest;
    $highest = $available > $highest ? $available : $highest;
  }
  $obj = (object) array(
    'lowest' => $lowest,
    'highest' => $highest
  );
  return $obj;
}
*/
?>