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

$period = new DatePeriod(
						new DateTime('2021-01-01'),
						new DateInterval('P1D'),
						new DateTime('2022-01-01')
					);

$days = [];
foreach ($period as $v)
{
	$week_number = week_number($v->format('Y-m-d'));
	$week_number = str_pad($week_number, 2, '0', STR_PAD_LEFT);

	$days[$v->format('n')][$week_number][$v->format('w')] = $v->format('Y-m-d');
}

$days_name   = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
$months_name = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$holiday     = [
								'2021-01-01' => 'Tahun Baru Masehi 2021',
								'2021-01-21' => 'Tahun Baru Masehi 2021',
							];
$holiday_esp = [
								'2021-01-07' => 'Libur esoftplay Tahun Baru Masehi 2021',
								'2021-01-21' => 'Tahun Baru Masehi 2021',
							];
$notes 			 = [
								'2021-01-04' => '<b>Kholil</b> : Ijin Istri Sakit',
								'2021-01-05' => '<b>Kholil</b> : Ijin Istri Sakit<br>
																 <b>Faid</b> : Ijin Sakit',
							];

?>
<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Esoftplay's Calender</title>

		<!-- Bootstrap CSS -->
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style type="text/css">
			.weekend {
				color: #F99;
			}
			.holiday {
				color: #f33;
				cursor: pointer;
			}

			.note_red,
			.note_blue {
		    position: relative;
		    cursor: pointer;
			}
			.note_red:after,
			.note_blue:after {
		    content: "";
		    position: absolute;
		    top: 0;
		    right: 0;
		    width: 0; 
		    height: 0; 
		    display: block;
		    border-left: 10px solid transparent;
		    border-bottom: 10px solid transparent;
			}
			.note_red:after {
		    border-top: 10px solid #F00;
			}
			.note_blue:after {
		    border-top: 10px solid #00F;
			}
		</style>
	</head>
	<body>
		<h1 class="text-center">Esoftplay's Calender</h1>
		<hr>
		<div class="container">
			<?php 
				foreach ($days as $months => $dates)
				{
					?>
					<div class="col-sm-4 text-center">
						<h2 class="text-center"><?php echo $months_name[$months] ?></h2>
						<table class="table table-bordered">
							<thead>
								<tr>
									<?php
									foreach ($days_name as $days_number => $days_initial)
									{
										$is_weekend = in_array($days_number, [0,6]) ? ' weekend' : '';
										?>
										<th class="text-center<?php echo $is_weekend?>"><?php echo $days_initial ?></th>
										<?php
									}
									?>
								</tr>
							</thead>
							<tbody>
								<?php
									$week_first = array_keys($days[$months]);
									$week_first = reset($week_first);

									foreach ($days[$months] as $week_number => $dates)
									{
										?>
										<tr>
											<?php
											if ($week_number == $week_first && count($dates) < 7)
											{
												?>
												<td colspan="<?php echo (7 - count($dates)) ?>"></td>
												<?php
											}
											foreach ($dates as $days_number => $date)
											{
												$is_weekend     = in_array($days_number, [0,6]) ? 'weekend' : '';
												$is_holiday     = in_array($date, array_keys($holiday)) ? 1 : 0;
												$is_holiday_esp = in_array($date, array_keys($holiday_esp)) ? ' note_red' : '';
												$date_color     = $is_holiday ? ' holiday' : '';
												$is_flag        = !empty($notes[$date]) ? ' note_blue' : '';
												$date_note      = $is_flag ? ' data-toggle="popover" data-container="body" data-placement="top" data-html="true" data-trigger="hover" data-content="'.$notes[$date].'"' 
																									 : ($is_holiday ? ' data-toggle="popover" data-container="body" data-placement="top" data-html="true" data-trigger="hover" data-content="'.$holiday[$date].'"' 
																									 								: ($is_holiday_esp ? ' data-toggle="popover" data-container="body" data-placement="top" data-html="true" data-trigger="hover" data-content="'.$holiday_esp[$date].'"' 
																									 																	 : ''));
												?>
												<td class="<?php echo $is_weekend.$is_flag.$is_holiday_esp?>">
													<span class="dates<?php echo $date_color?>"<?php echo $date_note;?>><?php echo date('j', strtotime($date))?></span>
												</td>
												<?php
											}
											?>
										</tr>
										<?php
									}
								?>
							</tbody>
						</table>
					</div>
					<?php
					if ($months % 3 == 0) echo '<div class="clearfix"></div>';
				}
			?>
		</div>

		<!-- jQuery -->
		<script src="//code.jquery.com/jquery.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	</body>
</html>


<script type="text/javascript">
$(document).ready(function() {
	$('[data-toggle="popover"]').popover();
});
</script>
