<?php

if (empty($_SESSION['id']))
{
	$content = '<div class="alert alert-danger-outline">
		<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Nicht angemeldet.
	</div>';
	$page->setContent($content);
	return;
}

$notify = '';

if ($_POST)
{
	$user = new stdClass();
	$user->id            = $_SESSION['id'];
	$user->name          = trim($_POST['name']);
	$user->email         = trim($_POST['email']);
	$user->password      = trim($_POST['password']);
	$user->class         = trim($_POST['class']);
	$user->room          = trim($_POST['room']);
	$user->mobile        = trim($_POST['mobile']);
	$user->qualification = trim($_POST['qualification']);
	
	if (empty($user->name) || empty($user->email))
	{
		$notify = '<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="alert alert-danger-outline">
					<strong><span class="fa fa-bolt"></span> Fehler:</strong> die Pflichtfelder sind nicht alle ausgefüllt.
				</div>
			</div>
		</div>';
	}
	else
	{
		if ($db->updateUser($user))
		{
			$notify = '<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="alert alert-success-outline">
						<strong><span class="fa fa-check"></span> Erfolg:</strong> Daten aktualisiert.
					</div>
				</div>
			</div>';
			$_SESSION['name'] = $user->name;
		}
		else
		{
			$notify = '<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="alert alert-danger-outline">
						<strong><span class="fa fa-triangle"></span> Fehler:</strong> Aktualisierung fehlgeschlagen.
					</div>
				</div>
			</div>';
			$user = $db->getUser($_SESSION['id']);
		}
	}
}
else
{
	$user = $db->getUser($_SESSION['id']);
}

$content = '
<h1><span class="fa fa-cogs"></span> Einstellungen</h1>
<form method="post" action="'.URL.'/?p=settings" class="form-horizontal">
	'.$notify.'

	<div class="form-group">
		<label class="col-sm-2 control-label required">Name</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="name" value="'.$user->name.'" required>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label required">E-Mail Adresse</label>
		<div class="col-sm-10">
			<input class="form-control" type="email" name="email" value="'.$user->email.'" required>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">Passwort</label>
		<div class="col-sm-10">
			<input class="form-control" type="password" name="password" placeholder="Nur zum Ändern eintragen">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">Klasse</label>
		<div class="col-sm-4">
			<input class="form-control" type="text" name="class" value="'.$user->class.'">
		</div>
		<label class="col-sm-2 control-label">Raum</label>
		<div class="col-sm-4">
			<input class="form-control" type="text" name="room" value="'.$user->room.'">
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">Handynummer</label>
		<div class="col-sm-4">
			<input class="form-control" type="text" name="mobile" value="'.$user->mobile.'">
		</div>
		<label class="col-sm-2 control-label">Vorbildung</label>
		<div class="col-sm-4">
			<input class="form-control" type="text" name="qualification" value="'.$user->qualification.'">
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-8 col-xs-6">
			<button type="submit" class="btn btn-bs-outline">Speichern</button>
		</div>
		<div class="col-sm-2 col-xs-6 text-right form-control-static">
			<span class="required"></span> Pflichtfeld
		</div>
	</div>
</form>
';

$page->setContent($content);

?>