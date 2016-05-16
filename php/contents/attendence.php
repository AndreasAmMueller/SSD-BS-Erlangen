<?php

/**
 * attendences.php
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

	if ($db->setAttendences(intval($_SESSION['id']), $att))
	{
		$notify = '<div class="alert alert-success-outline">
			<strong><span class="fa fa-check"></span></strong> Anwesenheit gespeichert.
		</div>';
	}
	else
	{
		$notify = '<div class="alert alert-danger-outline">
			<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Anwesenheit konnte nicht gespeichert werden.
		</div>';
	}
}

$page->addJS(URL.'/js/attendence.js');

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

if (date('N', $yearstart) == 1)
{
	$weekstart = $yearstart;
}
else {
	$weekstart = strtotime('-'.(date('N', $yearstart) - 1).' day', $yearstart);
}

for ($week = $weekstart; $week <= $yearend; $week = strtotime('+ 1 week', $week))
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
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_mon" value="1"></td>';
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
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_tue" value="1"></td>';
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
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_wed" value="1"></td>';
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
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_thu" value="1"></td>';
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
		$tmp.= '<td><input type="checkbox" name="week_'.$w.'_fri" value="1"></td>';
	}

	$tmp.= '<td>
		<button type="button" class="btn btn-success-outline btn-xs set-week" week="'.$w.'"><span class="fa fa-check-circle"></span></button>
		<button type="button" class="btn btn-danger-outline btn-xs unset-week" week="'.$w.'"><span class="fa fa-trash"></span></button>
	</td>';
	$tmp.= '</tr>';

	$weeks[] = $tmp;
}

$content = '
<h1><span class="fa fa-calendar"></span> Jahresanwesenheit '.date('Y', $yearstart).'/'.date('y', $yearend).'</h1>
<p>
	Die Änderungen werden erst übernommen, wenn auf <em>Speichern</em> gedrückt wird.
</p>
<form method="post" action="'.URL.'/?p=attendence">
'.$notify.'
<table class="table">
	<thead>
		<tr>
			<th>KW</th>
			<th>Schulwoche</th>
			<th>
				Mo
				<button type="button" class="btn btn-xs btn-success-outline set-day" day="mon"><span class="fa fa-check-circle"></span></button>
				<button type="button" class="btn btn-xs btn-danger-outline unset-day" day="mon"><span class="fa fa-trash"></span></button>
			</th>
			<th>
				Di
				<button type="button" class="btn btn-xs btn-success-outline set-day" day="tue"><span class="fa fa-check-circle"></span></button>
				<button type="button" class="btn btn-xs btn-danger-outline unset-day" day="tue"><span class="fa fa-trash"></span></button>
			</th>
			<th>
				Mi
				<button type="button" class="btn btn-xs btn-success-outline set-day" day="wed"><span class="fa fa-check-circle"></span></button>
				<button type="button" class="btn btn-xs btn-danger-outline unset-day" day="wed"><span class="fa fa-trash"></span></button>
			</th>
			<th>
				Do
				<button type="button" class="btn btn-xs btn-success-outline set-day" day="thu"><span class="fa fa-check-circle"></span></button>
				<button type="button" class="btn btn-xs btn-danger-outline unset-day" day="thu"><span class="fa fa-trash"></span></button>
			</th>
			<th>
				Fr
				<button type="button" class="btn btn-xs btn-success-outline set-day" day="fri"><span class="fa fa-check-circle"></span></button>
				<button type="button" class="btn btn-xs btn-danger-outline unset-day" day="fri"><span class="fa fa-trash"></span></button>
			</th>
			<th><button type="button" class="btn btn-xs btn-danger-outline unset"><span class="fa fa-trash"></span> Alles</button></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td></td>
			<td><button type="submit" class="btn btn-bs-outline">Speichern</button></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		'.implode(PHP_EOL, $weeks).'
		<tr>
			<td></td>
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