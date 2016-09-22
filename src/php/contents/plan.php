<?php

/**
 * plan.php
 *
 * @author     Andreas Mueller <webmaster@am-wd.de>
 * @copyright  (c) 2016 Andreas Mueller
 * @license    MIT - http://am-wd.de/?p=about#license
 */

$week = isset($_POST['week']) ? intval($_POST['week']) : date('W');

$onDuty = $db->getPlan($week);
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
		$week_select[] = '<option value="'.$w.'" selected="selected">KW '.$w.' | '.date('d.m.y', $i).' - '.date('d.m.y', strtotime('+ 4 day', $i)).($w == date('W') ? ' &#9668;' : '').'</option>';
	else
		$week_select[] = '<option value="'.$w.'">KW '.$w.' | '.date('d.m.y', $i).' - '.date('d.m.y', strtotime('+ 4 day', $i)).($w == date('W') ? ' &#9668;' : '').'</option>';
}

$list = array();
foreach ($onDuty as $d)
{
	$list[] = '		<tr'.((isset($_SESSION['id']) && $_SESSION['id'] == $d->id) ? ' class="info"' : '').'>
			<td>
				'.$d->name.(empty($d->class) ? '' : ' ('.$d->class.')').'
			</td>
			<td'.((date('N') == 1 && $week == date('W')) ? ' class="active"' : '').'>
				<span class="fa fa-fw'.($d->mon == 1 ? $d->flag_mon == 1 ? ' fa-exclamation sick' : ' fa-check' : '').'"></span>
			</td>
			<td'.((date('N') == 2 && $week == date('W')) ? ' class="active"' : '').'>
				<span class="fa fa-fw'.($d->tue == 1 ? $d->flag_tue == 1 ? ' fa-exclamation sick' : ' fa-check' : '').'"></span>
			</td>
			<td'.((date('N') == 3 && $week == date('W')) ? ' class="active"' : '').'>
				<span class="fa fa-fw'.($d->wed == 1 ? $d->flag_wed == 1 ? ' fa-exclamation sick' : ' fa-check' : '').'"></span>
			</td>
			<td'.((date('N') == 4 && $week == date('W')) ? ' class="active"' : '').'>
				<span class="fa fa-fw'.($d->thu == 1 ? $d->flag_thu == 1 ? ' fa-exclamation sick' : ' fa-check' : '').'"></span>
			</td>
			<td'.((date('N') == 5 && $week == date('W')) ? ' class="active"' : '').'>
				<span class="fa fa-fw'.($d->fri == 1 ? $d->flag_fri == 1 ? ' fa-exclamation sick' : ' fa-check' : '').'"></span>
			</td>
		</tr>';
}

$content = '
<h1><span class="fa fa-heartbeat"></span> Schulsanitätsdienst Berufsschule Erlangen</h1>
<h2>Alarmkette</h2>
<p>
	Sekretariat der Berufsschule Erlangen: <a href="tel:+4991315338480">09131 533 848 0</a> (immer anrufen)
	<br />
	Im Normalfall alarmiert das Sekretariat den Schulsanitätsdienst nach Dienstplan (siehe unten) und/oder den Notruf 112 (bei Bedarf)
</p>

<h2>Dienstplan '.date('Y', $yearstart).'/'.date('y', $yearend).'</h2>
<form method="post" action="'.URL.'/" class="form-inline">
	<div class="form-group">
		<label class="sr-only" for="week">Dienstwoche</label>
		<select class="form-control" name="week" id="week">
			'.implode(PHP_EOL, $week_select).'
		</select>
	</div>

	<div class="form-group">
		<button type="submit" class="btn btn-bs-outline no-js">Auswählen</button>
	</div>
</form>

<table class="table">
	<thead>
		<tr>
			<th>Name</th>
			<th'.(date('N') == 1 ? ' class="active"' : '').'>Mo</th>
			<th'.(date('N') == 2 ? ' class="active"' : '').'>Di</th>
			<th'.(date('N') == 3 ? ' class="active"' : '').'>Mi</th>
			<th'.(date('N') == 4 ? ' class="active"' : '').'>Do</th>
			<th'.(date('N') == 5 ? ' class="active"' : '').'>Fr</th>
		</tr>
	</thead>
	<tbody>
		'.implode(PHP_EOL, $list).'
	</tbody>
</table>

<div class="row">
	<p class="text-right"><span class="sick">*</span> Krank gemeldet</p>
</div>
';

$page->setContent($content);

?>