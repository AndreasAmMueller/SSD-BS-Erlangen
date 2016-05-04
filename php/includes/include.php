<?php

/**
 * index.php
 *
 * Includes / loads all needed classes, functions and data for the site.
 * 
 * @author     Andreas Mueller <webmaster@am-wd.de>
 * @copyright  (c) 2016 Andreas Mueller
 * @license    MIT - http://am-wd.de/?p=about#license
 */

require_once DIR.'/php/config.php';

require_once DIR.'/php/includes/database.php';
require_once DIR.'/php/includes/menu.php';
require_once DIR.'/php/includes/page.php';

require_once DIR.'/php/includes/session.php';

global $db;
$db = new Database();

?>