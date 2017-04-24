<?php

/**
 * imprint.php
 *
 * @author     Andreas Mueller <webmaster@am-wd.de>
 * @copyright  (c) 2016 Andreas Mueller
 * @license    MIT - http://am-wd.de/?p=about#license
 */

$siteUrl = 'imprint';

$site = $db->getSite($siteUrl);

if (!empty($_POST['action']) && in_array('admin', $_SESSION['permissions']))
{
	switch ($_POST['action'])
	{
		case 'edit':
			$content = '
			<h1>Bearbeitung der Seite <em>?p='.$siteUrl.'</em></h1>
			<form action="'.URL.'/index.php?p='.$siteUrl.'" method="post">
				<div class="form-group">
					<label>Quellcode</label>
					<textarea name="content" placeholder="Sourcecode written in HTML" class="form-control" style="height: 400px">'.htmlentities($site->content).'</textarea>
				</div>
				
				<div class="form-group" style="text-align: right">
					<button type="submit" class="btn btn-success-outline" name="action" value="save">Speichern</button>
					<a href="'.URL.'/index.php?p='.$siteUrl.'" class="btn btn-warning-outline">Abbrechen</a>
				</div>
			</form>
			';
			$page->setContent($content);
			break;
		
		case 'save':
			$site->content = $_POST['content'];
			
			$db->updateSite($site);
			$site = $db->getSite($site->url);
			
			$page->setContent('<div class="alert alert-success-outline" role="alert"><span class="fa fa-check"></span> Seite gespeichert</div>');
			$page->addContent($site->content);
			$page->addContent('<form action="'.URL.'/index.php?p='.$siteUrl.'" method="post" style="text-align: right"><button type="submit" name="action" value="edit" class="btn btn-xs btn-bs">Bearbeiten</button></form>');
			break;
	}
}
else
{
	if (!empty($_SESSION['id']) && in_array('admin', $_SESSION['permissions']))
	{
		$page->setContent('<form action="'.URL.'/index.php?p='.$siteUrl.'" method="post" style="text-align: right"><button type="submit" name="action" value="edit" class="btn btn-xs btn-bs">Bearbeiten</button></form>');
		$page->addContent($site->content);
		$page->addContent('<form action="'.URL.'/index.php?p='.$siteUrl.'" method="post" style="text-align: right"><button type="submit" name="action" value="edit" class="btn btn-xs btn-bs">Bearbeiten</button></form>');
	}
	else
	{
		$page->setContent($site->content);
	}
}

?>