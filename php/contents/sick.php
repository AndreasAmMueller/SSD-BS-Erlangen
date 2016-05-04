<?php

/**
 * sick.php
 *
 * @author     Andreas Mueller <webmaster@am-wd.de>
 * @copyright  (c) 2016 Andreas Mueller
 * @license    MIT - http://am-wd.de/?p=about#license
 */

if (empty($_SESSION['id']))
{
	$content = '<div class="alert alert-danger-outline">
		<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Nicht angemeldet.
	</div>';
	$page->setContent($content);
	return;
}

$notify = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$att = array();
	foreach ($_POST as $key => $value)
	{
		$week = intval(substr($key, 5, 2));
		if (!isset($att[$week]))
			$att[$week] = new stdClass();

		switch(substr($key, 8, 3))
		{
			case 'mon': $att[$week]->mon = $value == 1; break;
			case 'tue': $att[$week]->tue = $value == 1; break;
			case 'wed': $att[$week]->wed = $value == 1; break;
			case 'thu': $att[$week]->thu = $value == 1; break;
			case 'fri': $att[$week]->fri = $value == 1; break;
		}
	}

	if ($db->setSick(intval($_SESSION['id']), $att))
	{
		$notify = '<div class="alert alert-success-outline">
			<strong><span class="fa fa-check"></span></strong> Krankmeldung gespeichert.
		</div>';
	}
	else
	{
		$notify = '<div class="alert alert-danger-outline">
			<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Krankmeldung konnte nicht gespeichert werden.
		</div>';
	}
}

function isHoliday($array, $unix_time, $settings = null)
{
	if (count($array) <= 0 || intval($unix_time) <= 0)
		return false;

	foreach ($array as $hd)
	{
		if (strtotime($hd->start) <= $unix_time && $unix_time <= strtotime($hd->end))
			return true;
		if ($settings != null && ($unix_time < strtotime($settings->start) || $unix_time > strtotime($settings->end)))
			return true;
	}

	return false;
}

function isPresent($array, $week, $day)
{
	$allowed = array('mon', 'tue', 'wed', 'thu', 'fri');

	if (count($array) <= 0 || intval($week) <= 0 || !in_array($day, $allowed))
		return false;

	foreach ($array as $at)
	{
		if ($at->week == $week)
			return intval($at->$day) > 0;
	}

	return false;
}

$weeks = array();

$settings = $db->getSettings();
$holidays = $db->getHolidays();
$attendence = $db->getAttendence(intval($_SESSION['id']));

$yearstart = strtotime($settings->start);
$yearend = strtotime($settings->end);

if ($yearstart < time())
{
	$yearstart = time();
}

if (date('N', $yearstart) == 1)
{
	$weekstart = $yearstart;
}
else {
	$weekstart = strtotime('-'.(date('N', $yearstart) - 1).' day', $yearstart);
}

for ($week = $weekstart, $i = 0; $week <= $yearend && $i < 5; $week = strtotime('+ 1 week', $week), $i++)
{
	$w = date('W', $week);
	$tmp = '<tr>';
	$tmp.= '<td>'.$w.'</td>';
	$tmp.= '<td>'.date('d.m.y', $week).' - '.date('d.m.y', strtotime('+ 4 day', $week)).'</td>';

	// Monday
	if (isHoliday($holidays, $week, $settings))
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_mon" value="1" disabled></td>';
	}
	else if (isPresent($attendence, $w, 'mon'))
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_mon" value="1" checked="checked"></td>';
	}
	else
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_mon" value="1" class="check_mon"></td>';
	}

	// Tuesday
	if (isHoliday($holidays, strtotime('+1 day', $week), $settings))
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_tue" value="1" disabled></td>';
	}
	else if (isPresent($attendence, $w, 'tue'))
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_tue" value="1" checked="checked"></td>';
	}
	else
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_tue" value="1" class="check_tue"></td>';
	}

	// Wednesday
	if (isHoliday($holidays, strtotime('+2 day', $week), $settings))
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_wed" value="1" disabled></td>';
	}
	else if (isPresent($attendence, $w, 'wed'))
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_wed" value="1" checked="checked"></td>';
	}
	else
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_wed" value="1" class="check_wed"></td>';
	}

	// Thursday
	if (isHoliday($holidays, strtotime('+3 day', $week), $settings))
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_thu" value="1" disabled></td>';
	}
	else if (isPresent($attendence, $w, 'thu'))
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_thu" value="1" checked="checked"></td>';
	}
	else
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_thu" value="1" class="check_thu"></td>';
	}

	// Friday
	if (isHoliday($holidays, strtotime('+4 day', $week), $settings))
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_fri" value="1" disabled></td>';
	}
	else if (isPresent($attendence, $w, 'fri'))
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_fri" value="1" checked="checked"></td>';
	}
	else
	{
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_fri" value="1" class="check_fri"></td>';
	}
	$tmp.= '</tr>';

	$weeks[] = $tmp;
}

$content = '
<h1><span class="fa fa-bed"></span> Krankmeldung</h1>
<p>
	Für eine Krankmeldung wird einfach die Anwesenheit ausgetragen.
	<br />
	Um die Übersicht zu vereinfachen, werden lediglich die nächsten 4 Wochen angezeigt.
</p>
<form method="post" action="'.URL.'/?p=sick">
'.$notify.'
<table class="table">
	<thead>
		<tr>
			<th>KW</th>
			<th>Schulwoche</th>
			<th>Mo</th>
			<th>Di</th>
			<th>Mi</th>
			<th>Do</th>
			<th>Fr</th>
		</tr>
	</thead>
	<tbody>
		'.implode(PHP_EOL, $weeks).'
		<tr>
			<td></td>
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