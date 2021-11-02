<?php
include_once 'config.php';
include_once 'function.php';

if (!empty($_POST['search_submit_search']) && $_POST['search_submit_search'] == 'RESET')
{
	unset($_POST);
	redirect();
}

if (!empty($_POST['add_submit']) && $_POST['add_submit'] == 'SAVE')
{
	$ondate = $_POST['add_ondate'];
	$title  = $_POST['add_title'];

	if (!empty($_POST['add_absent_member']))
	{
		$_POST['search_member_id'] = @$_POST['add_absent_member'];

		$member = intval($_POST['add_absent_member']);
		$type   = intval($_POST['add_absent_type']);

		$conn = mysqli_connect($servername, $username, $password, $database);
		if (mysqli_connect_errno()) 
			die('Failed to connect to MySQL: '.mysqli_connect_errno());
		$rows = mysqli_query($conn, 'INSERT INTO `member_absent` (`member_id`, `type_id`, `ondate`, `title`) VALUES ('.$member.', '.$type.', "'.$ondate.'", "'.$title.'")');
		if ($rows) {
		  echo 'New record created successfully';
		} else {
		  echo 'Error: '.mysqli_error($conn);
		}
		mysqli_close($conn);
	}else{
		$is_nation  = !empty($_POST['add_holiday_nation']) ? intval($_POST['add_holiday_nation']) : 0;
		$is_office  = !empty($_POST['add_holiday_office']) ? intval($_POST['add_holiday_office']) : 0;
		$is_offwork = !empty($_POST['add_holiday_offwork']) ? intval($_POST['add_holiday_offwork']) : 0;

		$conn = mysqli_connect($servername, $username, $password, $database);
		if (mysqli_connect_errno()) 
			die('Failed to connect to MySQL: '.mysqli_connect_errno());
		$rows = mysqli_query($conn, 'INSERT INTO `holiday` (`title`, `ondate`, `is_nation`, `is_office`, `is_offwork`) VALUES ("'.$title.'", "'.$ondate.'", '.$is_nation.', '.$is_office.', '.$is_offwork.')');
		if ($rows) {
		  echo 'New record created successfully';
		} else {
		  echo 'Error: '.mysqli_error($conn);
		}
		mysqli_close($conn);
	}


	redirect();
}

$admin_id   = '1';
$member_id  = !empty($_POST['search_member_id']) ? intval($_POST['search_member_id']) : 0;
$date_start = date('Y-01-01');
$date_end   = date('Y-m-d', strtotime('+1 year', strtotime($date_start)));

if (!empty($member_id))
{
	$conn = mysqli_connect($servername, $username, $password, $database);
	if (mysqli_connect_errno()) 
		die('Failed to connect to MySQL: '.mysqli_connect_errno());
	$rows = mysqli_query($conn, 'SELECT `join` FROM `member` WHERE `id` = '.$member_id);
	$rows = mysqli_fetch_row($rows);
	mysqli_close($conn);

	$join_month = !in_array($member_id, [1,2]) ? date('d M', strtotime($rows[0])) : '01 Jan';
	$active_end = date('Y-m-d', strtotime($join_month.date('Y')));
	$date_start = date('Y-m-d', strtotime('-1 year', strtotime($active_end)));
	$date_end   = $active_end;

	if ($active_end < date('Y-m-d')) {
		$date_start = $active_end;
		$date_end   = date('Y-m-d', strtotime('+1 year', strtotime($date_start)));
	}	
}

if (!empty($_POST['search_daterange']))
{
	$daterange  = explode(' - ', $_POST['search_daterange']);
	$date_start = $daterange[0];
	$date_end   = $daterange[1];
}
$period = new DatePeriod(
						new DateTime($date_start),
						new DateInterval('P1D'),
						new DateTime($date_end)
					);

$days = [];
foreach ($period as $v)
{
	$week_number = week_number($v->format('Y-m-d'));
	$week_number = str_pad($week_number, 2, '0', STR_PAD_LEFT);

	$days[$v->format('Y')][$v->format('n')][$week_number][$v->format('w')] = $v->format('Y-m-d');
}

$days_name   = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
$months_name = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];


$conn = mysqli_connect($servername, $username, $password, $database);
if (mysqli_connect_errno()) 
	die('Failed to connect to MySQL: '.mysqli_connect_errno());
$rows = mysqli_query($conn, 'SELECT `ondate`, `title` FROM `holiday` WHERE `is_nation` = 1');
$rows = mysqli_fetch_all($rows, MYSQLI_ASSOC);
mysqli_close($conn);

$holiday = [];
foreach ($rows as $v) {
	if (isset($holiday[$v['ondate']])) {
		$holiday[$v['ondate']] .= ' | '.$v['title'];
	}else{
		$holiday[$v['ondate']] = $v['title'];
	}
}


$conn = mysqli_connect($servername, $username, $password, $database);
if (mysqli_connect_errno()) 
	die('Failed to connect to MySQL: '.mysqli_connect_errno());
$rows = mysqli_query($conn, 'SELECT `ondate`, `title` FROM `holiday` WHERE `is_office` = 1');
$rows = mysqli_fetch_all($rows, MYSQLI_ASSOC);
mysqli_close($conn);

$holiday_office = [];
foreach ($rows as $v) {
	if (isset($holiday_office[$v['ondate']])) {
		$holiday_office[$v['ondate']] .= ' | '.$v['title'];
	}else{
		$holiday_office[$v['ondate']] = $v['title'];
	}
}


if (!empty($member_id))
{
	$conn = mysqli_connect($servername, $username, $password, $database);
	if (mysqli_connect_errno()) 
		die('Failed to connect to MySQL: '.mysqli_connect_errno());
	$rows = mysqli_query($conn, 'SELECT `ondate`, `a`.`title` FROM `member_absent` AS `a` LEFT JOIN `member_absent_type` AS `t` ON(`a`.`type_id`=`t`.`id`) WHERE `member_id` = '.$member_id);
	$rows = mysqli_fetch_all($rows, MYSQLI_ASSOC);
	mysqli_close($conn);

	$notes = [];
	foreach ($rows as $v) {
		if (isset($notes[$v['ondate']])) {
			$notes[$v['ondate']] .= ' | '.$v['title'];
		}else{
			$notes[$v['ondate']] = $v['title'];
		}
	}
}
?>
<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Esoftplay's Calender</title>

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/daterangepicker.css">

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
			
			.flagged_red {
				content: "";
		    position: absolute;
		    top: 0;
		    right: 0;
		    width: 0; 
		    height: 0; 
		    display: block;
		    border-top: 8px solid #F00;
		    border-left: 8px solid transparent;
		    border-bottom: 8px solid transparent;
		    z-index: 2;
			}

			.flagged_blue {
				content: "";
		    position: absolute;
		    top: 0;
		    right: 0;
		    width: 0; 
		    height: 0; 
		    display: block;
		    border-top: 10px solid #00F;
		    border-left: 10px solid transparent;
		    border-bottom: 10px solid transparent;
		    z-index: 1;
			}
			
			.dotted {
				position: absolute;
				bottom: 0;
				left: 3px;
				font-weight: bold;
				font-size: 20px;
				letter-spacing: -2px;
				z-index: -1;
			}
		</style>
	</head>
	<body>
		<h1 class="text-center">Esoftplay's Calender</h1>
		<hr>
		<div class="container">
			<form method="POST" action="" name="search" class="form-inline pull-right" role="form">
				<div class="form-group">
					<label class="sr-only">Member List</label>
					<select name="search_member_id" class="form-control" title="Member List" placeholder="Member List" onchange="this.form.submit()">
						<option value="">---- Pilih Member ----</option>
						<?php
						$conn = mysqli_connect($servername, $username, $password, $database);
						if (mysqli_connect_errno()) 
							die('Failed to connect to MySQL: '.mysqli_connect_errno());
						$rows = mysqli_query($conn, 'SELECT `id`, `name` FROM `member` WHERE 1');
						$rows = mysqli_fetch_all($rows, MYSQLI_ASSOC);
						mysqli_close($conn);

						$member = [];
						foreach ($rows as $v) {
							$member[$v['id']] = $v['name'];
						}


						foreach ($member as $members => $member_name)
						{
							$selected = ($members == $member_id) ? ' selected' : '';
							echo '<option value="'.$members.'"'.$selected.'>'.$member_name.'</option>';
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<label class="sr-only">Date Range</label>
					<input type="text" class="form-control js-daterangepicker-clear" name="search_daterange" placeholder="Select Dates">
				</div>
				<button type="submit" name="search_submit_search" value="SEARCH" class="btn btn-default btn-secondary"><span class="glyphicon glyphicon-search"></span></button>
				<button type="submit" name="search_submit_search" value="RESET" class="btn btn-default btn-secondary"><span class="glyphicon glyphicon-remove-circle"></span></button> 
			</form>
		</div>
		<hr>

		<div class="container">
			<?php 
				foreach ($days as $year => $years)
				{
					foreach ($years as $months => $dates)
					{
						?>
						<div class="col-sm-4 text-center">
							<h2 class="text-center"><?php echo $months_name[$months].' '.$year ?></h2>
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
										$week_first = array_keys($days[$year][$months]);
										$week_first = reset($week_first);

										foreach ($days[$year][$months] as $week_number => $dates)
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
													$is_weekend        = in_array($days_number, [0,6]) ? 'weekend ' : '';
													$is_holiday        = in_array($date, array_keys($holiday)) ? 1 : 0;
													$is_holiday_office = in_array($date, array_keys($holiday_office)) ? 'note_red ' : '';
													$date_color        = $is_holiday ? ' holiday' : '';
													$is_flag           = !empty($notes[$date]) ? 'note_blue ' : '';

													$date_notes = [];
													if ($is_flag) $date_notes[] = '<b>Absent: </b>'.$notes[$date];
													if ($is_holiday) $date_notes[] = $holiday[$date];
													if ($is_holiday_office && @$holiday[$date] != $holiday_office[$date]) $date_notes[] = '<b>Office: </b>'.$holiday_office[$date];

													$date_note = !empty($date_notes) ? ' data-toggle="popover" data-container="body" data-placement="top" data-html="true" data-trigger="hover" data-content="'.implode('<hr style=\'margin:5px 0\'><br/>', $date_notes).'"' : '';

													$is_admin = !empty($admin_id) ? ' data-admin="'.$admin_id.'" data-date="'.$date.'" style="cursor: pointer;"' : '';
													?>
													<td class="<?php echo $is_weekend.$is_flag.$is_holiday_office?>">
														<span class="dates<?php echo $date_color?>"<?php echo $date_note.$is_admin;?>><?php echo date('j', strtotime($date))?></span>
														<?php
														if ($is_holiday_office) echo '<div class="flagged_red"></div>';
														if ($is_flag) echo '<div class="flagged_blue"></div>';
														?>
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
				}
			?>
		</div>

		<!-- jQuery -->
		<script src="assets/js/jquery.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/moment.min.js"></script>
		<script src="assets/js/daterangepicker.js"></script>
	</body>
</html>




<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
				<form method="POST" action="" name="add" role="form">
					<?php
					if (!empty($member_id))
					{
						?>
						<input type="hidden" class="form-control" name="add_absent_member" placeholder="Member" value="<?php echo $member_id ?>">
						<div class="form-group">
							<label>Tipe Absen</label>
							<select name="add_absent_type" class="form-control" title="Tipe Absen" placeholder="Tipe Absen">
								<option value="">---- Pilih Tipe ----</option>
								<?php
								$conn = mysqli_connect($servername, $username, $password, $database);
								if (mysqli_connect_errno()) 
									die('Failed to connect to MySQL: '.mysqli_connect_errno());
								$rows = mysqli_query($conn, 'SELECT `id`, `title` FROM `member_absent_type` WHERE 1');
								$rows = mysqli_fetch_all($rows, MYSQLI_ASSOC);
								mysqli_close($conn);

								$absent_type = [];
								foreach ($rows as $v) {
									$absent_type[$v['id']] = $v['title'];
								}


								foreach ($absent_type as $absent_type_id => $absent_type_name)
								{
									echo '<option value="'.$absent_type_id.'">'.$absent_type_name.'</option>';
								}
								?>
							</select>
						</div>
						<?php
					}else{
						?>
						<div class="form-group">
							<label>Holiday Option</label>
							<div class="input-group">
								<label class="checkbox-inline">
								  <input type="checkbox" value="1" name="add_holiday_nation">National Holiday
								</label>
								<label class="checkbox-inline">
								  <input type="checkbox" value="1" name="add_holiday_office">Office Holiday
								</label>
								<label class="checkbox-inline">
								  <input type="checkbox" value="1" name="add_holiday_offwork">Off Work
								</label>
							</div>
						</div>
						<?php
					}
					?>
					<input type="hidden" class="form-control" name="add_ondate" placeholder="Ondate">
					<div class="form-group">
						<label>Keterangan</label>
						<input type="text" class="form-control" name="add_title" placeholder="Keterangan">
					</div>
					<button type="submit" name="add_submit" value="SAVE" class="btn btn-primary btn-secondary"><span class="glyphicon glyphicon-floppy-disk"></span> Simpan</button>
				</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
$(document).ready(function() {
	$('[data-toggle="popover"]').popover();

	$("[data-admin]").click(function () {
    $("[name='add_ondate']").val($(this).data("date"));
    $(".modal-title").html($(this).data("date"));

    $("#myModal").modal("show");  
    $("[data-toggle='popover']").popover("hide");
	});
});
</script>

<script>
  $(document).on('ready', function () {
    $('.js-daterangepicker-clear').daterangepicker({
      autoUpdateInput: false,
      locale: {
        cancelLabel: 'Clear'
      }
    });

    $('.js-daterangepicker-clear').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('.js-daterangepicker-clear').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
  });
</script>