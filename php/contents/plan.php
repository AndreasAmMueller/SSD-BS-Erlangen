<?php

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
		$week_select[] = '<option value="'.$w.'" selected="selected">KW '.$w.' | '.date('d.m.y', $i).' - '.date('d.m.y', strtotime('+ 4 day', $i)).'</option>';
	else
		$week_select[] = '<option value="'.$w.'">KW '.$w.' | '.date('d.m.y', $i).' - '.date('d.m.y', strtotime('+ 4 day', $i)).'</option>';
}

$list = array();
foreach ($onDuty as $d)
{
	$list[] = '		<tr>
			<td>'.$d->name.(empty($d->class) ? '' : ' ('.$d->class.')').'</td>
			<td'.(date('N') == 1 ? ' class="active"' : '').'>'.($d->mon == 1 ? '<span class="fa fa-check"></span>' : '').'</td>
			<td'.(date('N') == 2 ? ' class="active"' : '').'>'.($d->tue == 1 ? '<span class="fa fa-check"></span>' : '').'</td>
			<td'.(date('N') == 3 ? ' class="active"' : '').'>'.($d->wed == 1 ? '<span class="fa fa-check"></span>' : '').'</td>
			<td'.(date('N') == 4 ? ' class="active"' : '').'>'.($d->thu == 1 ? '<span class="fa fa-check"></span>' : '').'</td>
			<td'.(date('N') == 5 ? ' class="active"' : '').'>'.($d->fri == 1 ? '<span class="fa fa-check"></span>' : '').'</td>
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

<h2>Dienstplan</h2>
<form method="post" action="'.URL.'" class="form-inline">
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
';

$page->setContent($content);

?>