<?php

$page->addJS(URL.'/js/user.js');

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

if ($_POST)
{
	switch ($_POST['action'])
	{
		case 'new':
			$usr = new stdClass();
			$usr->name = trim($_POST['name']);
			$usr->email = trim($_POST['email']);
			$usr->password = trim($_POST['password']);
			$usr->class = trim($_POST['class']);
			$usr->room = trim($_POST['room']);
			$usr->mobile = trim($_POST['mobile']);
			$usr->qualification = trim($_POST['qualification']);
			$usr->permissions = isset($_POST['permissions']) ? $_POST['permissions'] : array();
			
			if (empty($usr->name) || empty($usr->email) || empty($usr->password))
			{
				$content = '<div class="alert alert-danger-outline">
					<strong><span class="fa fa-bolt"></span> Fehler:</strong> Ein Pflichtfeld wurde nicht ausgefüllt.
				</div>
				<a href="'.URL.'/?p=user" class="btn btn-bs-outline"><span class="fa fa-users"></span> Personal <span class="fa fa-fs fa-angle-double-right"></span></a>';
				$page->setContent($content);
				return;
			}
			
			if ($db->addUser($usr) == 0)
			{
				$content = '<div class="alert alert-danger-outline">
					<strong><span class="fa fa-exclamation-triangle"></span> Fehler:</strong> Der Benutzer konnte nicht angelegt werden.
				</div>';
				$page->setContent($content);
				return;
			}
			
			$content = '<div class="alert alert-success-outline">
				<strong><span class="fa fa-check"></span></strong> Der Benutzer wurde angelegt.
			</div>
			<a href="'.URL.'/?p=user" class="btn btn-bs-outline"><span class="fa fa-users"></span> Personal <span class="fa fa-fs fa-angle-double-right"></span></a>';
			$page->setContent($content);
			return;
		case 'edit':
			$usr = new stdClass();
			$usr->id = intval($_POST['user']);
			$usr->name = trim($_POST['name']);
			$usr->email = trim($_POST['email']);
			$usr->password = trim($_POST['password']);
			$usr->class = trim($_POST['class']);
			$usr->room = trim($_POST['room']);
			$usr->mobile = trim($_POST['mobile']);
			$usr->qualification = trim($_POST['qualification']);
			$usr->permissions = isset($_POST['permissions']) ? $_POST['permissions'] : array();
			
			$db->updateUser($usr);
			
			$content = '<div class="alert alert-success-outline">
				<strong><span class="fa fa-check"></span></strong> Die Daten wurden aktualisiert.
			</div>
			<a href="'.URL.'/?p=user" class="btn btn-bs-outline"><span class="fa fa-users"></span> Personal <span class="fa fa-fs fa-angle-double-right"></span></a>';
			$page->setContent($content);
			return;
		case 'delete':
			if ($db->deleteUser(intval($_POST['user'])))
			{
				$content = '<div class="alert alert-success-outline">
					<strong><span class="fa fa-check"></span></strong> Der Benutzer wurde gelöscht.
				</div>
				<a href="'.URL.'/?p=user" class="btn btn-bs-outline"><span class="fa fa-users"></span> Personal <span class="fa fa-fs fa-angle-double-right"></span></a>';
			}
			else
			{
				$content = '<div class="alert alert-danger-outline">
					<strong><span class="fa fa-bolt"></span> Fehler:</strong> Das Löschen ist fehlgeschlagen.
				</div>';
			}
			$page->setContent($content);
			return;
	}
	
	return;
}

$users = $db->getUserList();
$user_list = array();
foreach ($users as $u)
{
	$user_list[] = '<option value="'.$u->id.'">'.$u->name.' ('.$u->email.')</option>';
}

$content = '
<h1><span class="fa fa-users"></span> Personalverwaltung</h1>
<form method="post" action="'.URL.'/?p=user" class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-2 control-label">Aktion</label>
		<div class="col-sm-10">
			<select name="action" class="form-control">
				<option value="">Auswählen...</option>
				<option value="new">Benutzer anlegen</option>
				<option value="edit">Benutzer bearbeiten</option>
				<option value="delete">Benutzer löschen</option>
			</select>
		</div>
	</div>
	
	<div class="form-group" id="user-user">
		<label class="col-sm-2 control-label">Benutzer</label>
		<div class="col-sm-10">
			<select name="user" class="form-control">
				<option value="">Auswählen...</option>
				'.implode(PHP_EOL, $user_list).'
			</select>
		</div>
	</div>
	
	<div class="form-group" id="user-name">
		<label class="col-sm-2 control-label required">Name</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="name">
		</div>
	</div>
	
	<div class="form-group" id="user-email">
		<label class="col-sm-2 control-label required">E-Mail Adresse</label>
		<div class="col-sm-10">
			<input class="form-control" type="email" name="email">
		</div>
	</div>
	
	<div class="form-group" id="user-password">
		<label class="col-sm-2 control-label">Passwort</label>
		<div class="col-sm-10">
			<input class="form-control" type="password" name="password">
		</div>
	</div>
	
	<div class="form-group" id="user-class">
		<label class="col-sm-2 control-label">Klasse</label>
		<div class="col-sm-4">
			<input class="form-control" type="text" name="class">
		</div>
		<label class="col-sm-2 control-label">Raum</label>
		<div class="col-sm-4">
			<input class="form-control" type="text" name="room">
		</div>
	</div>
	
	<div class="form-group" id="user-mobile">
		<label class="col-sm-2 control-label">Handynummer</label>
		<div class="col-sm-4">
			<input class="form-control" type="text" name="mobile">
		</div>
		<label class="col-sm-2 control-label">Vorbildung</label>
		<div class="col-sm-4">
			<input class="form-control" type="text" name="qualification">
		</div>
	</div>
	
	<div class="form-group" id="user-permission">
		<label class="control-label col-sm-2">Berechtigungen</label>
		<div class="col-sm-10">
			<label class="checkbox-inline">
				<input type="checkbox" name="permissions[]" value="manage"> Verwaltung
			</label>
			<label class="checkbox-inline">
				<input type="checkbox" name="permissions[]" value="admin"> Administrator
			</label>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-8 col-xs-6">
			<button type="submit" class="btn btn-bs-outline"><span class="fa fa-play-circle"></span> Ausführen</button>
		</div>
		<div class="col-sm-2 col-xs-6 text-right form-control-static">
			<span class="required"></span> Pflichtfeld
		</div>
	</div>
</form>
';

$page->setContent($content);

?>