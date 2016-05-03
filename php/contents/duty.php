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

if (isset($_POST['action']) && $_POST['action'] == 'save')
{
	$page->setContent('<pre>'.print_r($_POST, 1).'</pre>');
	return;
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

	$tmp.= '</tr>';

	$list[] = $tmp;
}

$content = '
<h1><span class="fa fa-calendar-check-o"></span> Personal einteilen</h1>
<form method="post" action="'.URL.'/?p=duty" class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-2 col-xs-12">Kalenderwoche</label>
		<div class="col-sm-9 col-xs-9">
			<select name="week" class="form-control input-sm">
				'.implode(PHP_EOL, $week_select).'
			</select>
		</div>
		<div class="col-sm-1 col-xs-3 text-right">
			<button type="submit" class="btn btn-sm btn-bs-outline">Go</button>
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
			</tr>
			'.implode(PHP_EOL, $list).'
			<tr>
				<td><button type="submit" class="btn btn-bs-outline">Speichern</button></td>
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