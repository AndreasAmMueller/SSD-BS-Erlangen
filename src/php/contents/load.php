<?php

/**
 * load.php
 *
 * @author     Andreas Mueller <webmaster@am-wd.de>
 * @copyright  (c) 2016 Andreas Mueller
 * @license    MIT - http://am-wd.de/?p=about#license
 */

// Get current site
$p = isset($_GET['p']) ? trim($_GET['p']) : 'default';

// Left menu
$menu = new AMWD\Menu();
//$menu->addNode('<span class="fa fa-fs fa-home"></span> Home', URL, $p == 'default');
$menu->addNode('<span class="fa fa-fs fa-info-circle"></span> Infos', URL.'/?p=infos', $p == 'infos');

if (!empty($_SESSION['id']))
{
	$menu->addNode('<span class="fa fa-fs fa-users"></span> Liste', URL.'/?p=list', $p == 'list');
	$menu->addNode('<span class="fa fa-fs fa-calendar"></span> Anwesend', URL.'/?p=attendence', $p == 'attendence');
	$menu->addNode('<span class="fa fa-fs fa-bed"></span> Krank', URL.'/?p=sick', $p == 'sick');
	$menu->addNode('<span class="fa fa-fs fa-envelope"></span> Mail', URL.'/?p=mail', $p == 'mail');
}

$page->addMenu($menu);

// Right menu
$menu = new AMWD\Menu(true);

if (!isset($_SESSION['id']) || empty($_SESSION['id']))
{
	$menu->addNode('<span class="fa fa-fs fa-sign-in"></span> Login', URL.'/?p=login', $p == 'login');
}
else
{
	if (in_array('manage', $_SESSION['permissions']) || in_array('admin', $_SESSION['permissions']))
	{
		$node = $menu->addNode('Verwalten', '#');

		if (in_array('admin', $_SESSION['permissions']))
			$node->addSubmenu(new AMWD\MenuNode('<span class="fa fa-fs fa-users"></span> Personal', URL.'/?p=user', $p == 'user'));
		if (in_array('manage', $_SESSION['permissions']))
		{
			$node->addSubmenu(new AMWD\MenuNode('<span class="fa fa-fs fa-calendar-check-o"></span> Einteilen', URL.'/?p=duty', $p == 'duty'));
			// Commented due to a missing implementation
			//$node->addSubmenu(new AMWD\MenuNode('<span class="fa fa-fs fa-phone"></span> Diensthandy', URL.'/?p=mobile', $p == 'mobile'));
		}
		if (in_array('admin', $_SESSION['permissions']))
			$node->addSubmenu(new AMWD\MenuNode('<span class="fa fa-fs fa-cogs"></span> Schuljahr & Ferien', URL.'/?p=holidays', $p == 'holidays'));
	}

	$node = $menu->addNode($_SESSION['name'], '#');
	$node->addSubmenu(new AMWD\MenuNode('<span class="fa fa-fs fa-cogs"></span> Einstellungen', URL.'/?p=settings', $p == 'settings'));
	$node->addSubmenu(new AMWD\MenuNode('<span class="fa fa-fs fa-sign-out"></span> Logout', URL.'/?p=logout', $p == 'logout'));
}

$page->addMenu($menu);

// Load the site's content
switch ($p)
{
	case 'login':
	case 'logout':
		include_once __DIR__.'/login.php';
		break;
	case 'imprint':
		include_once __DIR__.'/imprint.php';
		break;
	case 'privacy':
		include_once __DIR__.'/privacy.php';
		break;
	case 'infos':
		include_once __DIR__.'/infos.php';
		break;
	case 'list':
		include_once __DIR__.'/list.php';
		break;
	case 'attendence':
		include_once __DIR__.'/attendence.php';
		break;
	case 'sick':
		include_once __DIR__.'/sick.php';
		break;
	case 'mail':
		include_once __DIR__.'/mail.php';
		break;
	case 'settings':
		include_once __DIR__.'/settings.php';
		break;
	case 'user':
		include_once __DIR__.'/user.php';
		break;
	case 'duty':
		include_once __DIR__.'/duty.php';
		break;
	case 'mobile':
		include_once __DIR__.'/mobile.php';
		break;
	case 'holidays':
		include_once __DIR__.'/holidays.php';
		break;
	default:
		include_once __DIR__.'/plan.php';
		break;
}

?>