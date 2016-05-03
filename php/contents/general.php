<?php

if (empty($_SESSION['id']))
{
	$content = '<div class="alert alert-danger-outline">
		<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Nicht angemeldet.
	</div>';
	$page->setContent($content);
	return;
}

if (!in_array('admin', $_SESSION['permissions']))
{
	$content = '<div class="alert alert-danger-outline">
		<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Fehlende Berechtigung.
	</div>';
	$page->setContent($content);
	return;
}

$notify = '';

if ($_POST)
{
	$obj = new stdClass();

	$start = trim($_POST['start']);
	$end = trim($_POST['end']);

	$obj->start = strtotime($start);
	$obj->end   = strtotime($end);

	switch ($_POST['action'])
	{
		case 'save':
			if ($obj->start < $obj->end)
			{
				if ($db->setSettings($obj))
				{
					$notify = '<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<div class="alert alert-success-outline">
				<strong><span class="fa fa-check"></span></strong> Daten gespeichert.
			</div>
		</div>
	</div>';
				}
				else
				{
					$notify = '<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<div class="alert alert-danger-outline">
				<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Speichern fehlgeschlagen.
			</div>
		</div>
	</div>';
				}
			}
			else
			{
				$notify = '<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<div class="alert alert-danger-outline">
				<strong><span class="fa fa-bolt"></span> Fehler:</strong> Schuljahr nicht korrekt.
			</div>
		</div>
	</div>';
			}
			break;
		case 'add':
		case 'change':
			if ($obj->start <= $obj->end)
			{
				$obj->id = intval($_POST['id']);

				if (!$db->setHolidays($obj))
				{
					$notify = '<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<div class="alert alert-danger-outline">
				<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Speichern fehlgeschlagen.
			</div>
		</div>
	</div>';
				}
			}
			else
			{
				$notify = '<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<div class="alert alert-danger-outline">
				<strong><span class="fa fa-bolt"></span> Fehler:</strong> Zeitraum ungültig.
			</div>
		</div>
	</div>';
			}
			break;
		case 'delete':
			if (!$db->deleteHolidays(intval($_POST['id'])))
			{
				$notify = '<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<div class="alert alert-danger-outline">
				<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Löschen fehlgeschlagen.
			</div>
		</div>
	</div>';
			}
			break;
	}
}

$settings = $db->getSettings();

$start = date('d.m.Y', strtotime($settings->start));
$end = date('d.m.Y', strtotime($settings->end));

$holidays = array();
foreach ($db->getHolidays() as $h)
{
	$holidays[] = '<tr>
						<form action="'.URL.'/?p=general" method="post">
						<td>
							<div class="input-group input-daterange" id="datepicker">
								<input type="text" class="form-control" name="start" placeholder="Erster Ferientag (dd.mm.yyyy)" value="'.date('d.m.Y', strtotime($h->start)).'" />
								<span class="input-group-addon">to</span>
								<input type="text" class="form-control" name="end" placeholder="Letzter Ferientag (dd.mm.yyyy)" value="'.date('d.m.Y', strtotime($h->end)).'" />
							</div>
						</td>
						<td>
							<input type="hidden" name="id" value="'.$h->id.'">
							<button type="submit" name="action" class="btn btn-success-outline" value="change"><span class="fa fa-check-circle"></span></button>
							<button type="submit" name="action" class="btn btn-danger-outline" value="delete"><span class="fa fa-trash"></span></button>
						</td>
						</form>
					</tr>';
}

$content = '
<h1><span class="fa fa-cogs"></span> Allgemeine Einstellungen</h1>
<div class="form-horizontal">
	'.$notify.'

	<form method="post" action="'.URL.'/?p=general">
	<div class="form-group">
		<label class="control-label col-sm-2">Schuljahresbeginn</label>
		<div class="col-sm-10">
			<input type="text" class="form-control datepicker" name="start" value="'.$start.'" placeholder="Erster Schultag (dd.mm.yyyy)">
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-sm-2">Schuljahresende</label>
		<div class="col-sm-10">
			<input type="text" class="form-control datepicker" name="end" value="'.$end.'" placeholder="Letzter Schultag (dd.mm.yyyy)">
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-bs-outline" name="action" value="save">Speichern</button>
		</div>
	</div>
	</form>

	<!-- ====================================================== -->

	<div class="form-group">
		<label class="control-label col-sm-2">Schulferien</label>
		<div class="col-sm-10">
			<p class="form-control-static">Es werden nur die Ferien des aktuellen Schuljahres angezeigt.</p>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Zeitraum</th>
						<th>Aktion</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<form action="'.URL.'/?p=general" method="post">
						<td>
							<div class="input-group input-daterange">
								<input type="text" class="form-control" name="start" placeholder="Erster Ferientag (dd.mm.yyyy)" />
								<span class="input-group-addon">to</span>
								<input type="text" class="form-control" name="end" placeholder="Letzter Ferientag (dd.mm.yyyy)" />
							</div>
						</td>
						<td>
							<input type="hidden" name="id" value="0">
							<button type="submit" name="action" class="btn btn-success-outline" value="add"><span class="fa fa-plus"></span></button>
							<button type="reset" class="btn btn-danger-outline"><span class="fa fa-trash"></span></button>
						</td>
						</form>
					</tr>
					'.implode(PHP_EOL, $holidays).'
				</tbody>
			</table>
		</div>
	</div>

</div>
';

$page->setContent($content);

?>