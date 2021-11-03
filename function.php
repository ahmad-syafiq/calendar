<?php

function work_date($member_id=0, $member_join='')
{
  $join_month  = !in_array($member_id, [1,2]) ? date('d M', strtotime($member_join)) : '01 Jan';
  $active_end  = date('Y-m-d', strtotime($join_month.date('Y')));
  $date_start  = date('Y-m-d', strtotime('-1 year', strtotime($active_end)));
  $date_end    = $active_end;

  if ($active_end < date('Y-m-d')) {
    $date_start = $active_end;
    $date_end   = date('Y-m-d', strtotime('+1 year', strtotime($date_start)));
  }

  $get_offwork = strtotime($member_join) <= strtotime('-1 year', strtotime('now')) ? 1 : 0;

  return [$date_start, $date_end, $get_offwork];
}

/*
EXAMPLE:
echo table(array('Nama' => 'Danang','Alamat' => 'Pringgondani'));
echo table(array(array('Danang','Pringgondani'),array('Widiantoro','Surgo')), array('Nama','Alamat'));
*/
function table($data, $header = array(), $title='')
{
  $output = '';
  if (!empty($data))
  {
    $tHead = '';
    $tBody = '';
    if (!empty($header) && !is_array($header))
    {
      if (empty($title))
      {
        $title = $header;
      }
      $header = array();
    }
    if (!empty($header))
    {
      $tHead = '<thead><tr><th>'.implode('</th><th>', $header).'</th></tr></thead>';
      $rows  = array();
      foreach ($data as $row)
      {
        $rows[] = '<td>'.implode('</td><td>', $row).'</td>';
      }
      $tBody = '<tbody><tr>'.implode('</tr><tr>', $rows).'</tr></tbody>';
    }else{
      foreach ((array)$data as $key => $value)
      {
        if (is_array($value))
        {
          $value = call_user_func(__FUNCTION__, $value);
        }
        $tBody .= '<tr><th>'.$key.'</th><td>'.$value.'</td></tr>';
      }
    }
    $output = '<table class="table table-striped table-bordered table-hover">'.$tHead.$tBody.'</table>';
    if (!empty($title))
    {
      $output = '
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">'.$title.'</h3>
          </div>
          <div class="panel-body">
            '.$output.'
          </div>
        </div>';
    }
  }
  return $output;
}

function getDB($dbID=0)
{
  $i = ($dbID > 0) ? $dbID : '';
  if (isset($GLOBALS['db'.$i]))
  {
    return $GLOBALS['db'.$i];
  }else{
    include_once _ROOT.'sql.php';
    $d = $GLOBALS['_DB'][$dbID];
    $GLOBALS['db'.$i] = new SQL();
    $ifconn = $GLOBALS['db'.$i]->Connect($d['SERVER'], $d['USERNAME'], $d['PASSWORD'], $d['DATABASE']);
    if (!$ifconn){
      die('Error while connecting to Database "'.$d['DATABASE'].'" on Server');
    }
    unset($GLOBALS['_DB'][$dbID]);
    return $GLOBALS['db'.$i];
  }
}

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

