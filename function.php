<?php

function week_max($year)
{
  $date = new DateTime;
  $date->setISODate($year, 53);
  return ($date->format('W') == '53' ? 53 : 52);
}

function week_number($date)
{
  $week      = date('W',strtotime($date));
  $day       = date('N',strtotime($date));
  $max_weeks = week_max(date('Y',strtotime($date)));

  if($day == 7 && $week < $max_weeks)
  {
    return ++$week;
  }else if($day == 7){
    return 1;
  }else{
    return $week;
  }
}

function redirect($url='')
{
	$url = !empty($url) ? $url : _URL;
	header('location:'.$url);
	die($url);
}

