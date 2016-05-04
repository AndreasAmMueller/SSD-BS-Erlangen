<?php

if (empty($_SESSION['id']))
{
	$content = '<div class="alert alert-danger-outline">
		<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Nicht angemeldet.
	</div>';
	$page->setContent($content);
	return;
}

if (!in_array('manage', $_SESSION['permissions']))
{
	$content = '<div class="alert alert-danger-outline">
		<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Fehlende Berechtigung.
	</div>';
	$page->setContent($content);
	return;
}

$page->addJS(URL.'/js/duty.js');

function getDuty($duty, $attendence)
{
	if (count($duty) <= 0 || $attendence == null)
		return null;

	foreach ($duty as $d)
	{
		if ($d->id == $attendence->id)
			return $d;
	}

	return null;
}

$week = isset($_POST['week']) ? intval($_POST['week']) : date('W');
$notify = '';

$settings = $db->getSettings();

$yearstart = strtotime($settings->start);
$yearend = strtotime($settings->end);

if (isset($_POST['go']))
{
	if ($_POST['go'] == 'prev')
		$new_week = $week - 1;
	else
		$new_week = $week + 1;

	if ($new_week < date('W', $yearend) || date('W', $yearstart) < $new_week)
		$week = $new_week;
}
else if (isset($_POST['action']) && $_POST['action'] == 'save')
{
	$page->setContent('<pre>'.print_r($_POST, 1).'</pre>');

	$dut = new stdClass();
	$dut->week = $week;
	$dut->year = date('Y', $yearstart);
	$dut->duty = array();
	foreach ($_POST as $key => $value)
	{
		$ar = explode('_', $key);
		if (count($ar) != 3)
			continue;

		$id = intval($ar[1]);

		if (!isset($dut->duty[$id]))
			$dut->duty[$id] = new stdClass();

		switch ($ar[2])
		{
			case 'mon': $dut->duty[$id]->mon = $value == 1; break;
			case 'tue': $dut->duty[$id]->tue = $value == 1; break;
			case 'wed': $dut->duty[$id]->wed = $value == 1; break;
			case 'thu': $dut->duty[$id]->thu = $value == 1; break;
			case 'fri': $dut->duty[$id]->fri = $value == 1; break;
		}
	}

	if ($db->setDuty($dut))
	{
		$notify = '<div class="alert alert-success-outline">
			<strong><span class="fa fa-check"></span></strong> Einteilung gespeichert.
		</div>';
	}
	else
	{
		$notify = '<div class="alert alert-danger-outline">
			<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Einteilung konnte nicht gespeichert werden.
		</div>';
	}
}



$duty = $db->getDuty($week);
$attendence = $db->getAttendenceWeek($week);

$settings = $db->getSettings();

$yearstart = strtotime($settings->start);
$yearend = strtotime($settings->end);

if (date('N', $yearstart) == 1)
{
	$weekstart = $yearstart;
}
else {
	$weekstart = strtotime('-'.(date('N', $yearstart) - 1).' day', $yearstart);
}

$week_select = array();
for ($i = $weekstart; $i <= $yearend; $i = strtotime('+ 1 week', $i))
{
	$w = date('W', $i);

	if ($w == $week)
		$week_select[] = '<option value="'.$w.'" selected="selected">KW '.$w.' | '.date('d.m.y', $i).' - '.date('d.m.y', strtotime('+ 4 day', $i)).'</option>';
	else
		$week_select[] = '<option value="'.$w.'">KW '.$w.' | '.date('d.m.y', $i).' - '.date('d.m.y', strtotime('+ 4 day', $i)).'</option>';
}

$list = array();

foreach ($attendence as $att)
{
	$dut = getDuty($duty, $att);

	$tmp = '<tr>';
	$tmp.= '<td><span data-toggle="tooltip" data-placement="top" title="'.$att->qualification.'">'.$att->name.(empty($att->class) ? '' : ' ('.$att->class.')').'</span></td>';

	// Monday
	if ($att->mon == 0)
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_mon" value="1" disabled></td>';
	}
	else if ($att->mon == 1 && $dut != null && $dut->mon == 1)
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_mon" value="1" checked="checked"></td>';
	}
	else
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_mon" value="1"></td>';
	}

	// Tuesday
	if ($att->tue == 0)
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_tue" value="1" disabled></td>';
	}
	else if ($att->tue == 1 && $dut != null && $dut->tue == 1)
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_tue" value="1" checked="checked"></td>';
	}
	else
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_tue" value="1"></td>';
	}

	// Wednesday
	if ($att->wed == 0)
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_wed" value="1" disabled></td>';
	}
	else if ($att->wed == 1 && $dut != null && $dut->wed == 1)
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_wed" value="1" checked="checked"></td>';
	}
	else
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_wed" value="1"></td>';
	}

	// Thursday
	if ($att->thu == 0)
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_thu" value="1" disabled></td>';
	}
	else if ($att->thu == 1 && $dut != null && $dut->thu == 1)
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_thu" value="1" checked="checked"></td>';
	}
	else
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_thu" value="1"></td>';
	}

	// Friday
	if ($att->fri == 0)
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_fri" value="1" disabled></td>';
	}
	else if ($att->fri == 1 && $dut != null && $dut->fri == 1)
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_fri" value="1" checked="checked"></td>';
	}
	else
	{
		$tmp.= '<td><input type="checkbox" name="usr_'.$att->id.'_fri" value="1"></td>';
	}

	$tmp.= '<td>
		<button type="button" class="btn btn-success-outline btn-xs set-user" user="'.$att->id.'"><span class="fa fa-check-circle"></span></button>
		<button type="button" class="btn btn-danger-outline btn-xs unset-user" user="'.$att->id.'"><span class="fa fa-trash"></span></button>
	</td>';
	$tmp.= '</tr>';

	$list[] = $tmp;
}

$content = '
<h1><span class="fa fa-calendar-check-o"></span> Personal einteilen</h1>
<form method="post" action="'.URL.'/?p=duty" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-2 col-xs-12">Kalenderwoche</label>
		<div class="col-xs-3 col-sm-2 text-right hidden-xxs">
			<button type="submit" class="btn btn-sm btn-bs-outline" name="go" value="prev"><span class="fa fa-chevron-left"></span> Vorherige</button>
		</div>
		<div class="col-xxs-12 col-xs-6 col-sm-6">
			<select name="week" class="form-control input-sm" id="week">
				'.implode(PHP_EOL, $week_select).'
			</select>
		</div>
		<div class="col-xxs-6 col-xs-3 col-sm-2 text-right visible-xxs">
			<button type="submit" class="btn btn-sm btn-bs-outline" name="go" value="prev"><span class="fa fa-chevron-left"></span> Vorherige</button>
		</div>
		<div class="col-xxs-6 col-xs-3 col-sm-2">
			<button type="submit" class="btn btn-sm btn-bs-outline" name="go" value="next">Nächste <span class="fa fa-chevron-right"></span></button>
		</div>
	</div>
</form>

'.$notify.'

<form method="post" action="'.URL.'/?p=duty">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="week" value="'.$week.'">
	<table class="table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Mo</th>
				<th>Di</th>
				<th>Mi</th>
				<th>Do</th>
				<th>Fr</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><button type="submit" class="btn btn-bs-outline">Speichern</button></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			'.implode(PHP_EOL, $list).'
			<tr>
				<td><button type="submit" class="btn btn-bs-outline">Speichern</button></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</form>
';

$page->setContent($content);

?>