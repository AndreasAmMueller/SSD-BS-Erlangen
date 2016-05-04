<?php

/**
 * list.php
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

$content = '
<h1><span class="fa fa-users"></span> Personalübersicht</h1>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Name</th>
			<th>E-Mail Adresse</th>
			<th>Klasse</th>
			<th>Raum</th>
			';
			if (in_array('manage', $_SESSION['permissions']))
			{
				$content .= '<th>Handynummer</th>';
			}
			if (in_array('admin', $_SESSION['permissions']))
			{
				$content .= '<th>Rechte</th>';
			}
			$content.= '
		</tr>
	</thead>
	<tbody>';
	foreach ($db->getUserList() as $user)
	{
		$content .= '
		<tr>
			<td>'.$user->name.'</td>
			<td>'.$user->email.'</td>
			<td>'.$user->class.'</td>
			<td>'.$user->room.'</td>
			';
			if (in_array('manage', $_SESSION['permissions']))
			{
				$content .= '<td>'.$user->mobile.'</td>';
			}
			if (in_array('admin', $_SESSION['permissions']))
			{
				$content .= '<td>'.implode(', ', $user->permissions).'</td>';
			}
			$content .= '
		</tr>
		';
	}
	$content .= '
	</tbody>
</table>
';
$page->setContent($content);


?>