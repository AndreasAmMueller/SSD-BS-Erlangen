<?php

/**
 * include.php
 * 
 * Includes / loads all needed classes,
 * functions and data for the site.
 */

require_once DIR.'/php/config.php';

require_once DIR.'/php/includes/database.php';
require_once DIR.'/php/includes/menu.php';
require_once DIR.'/php/includes/page.php';

require_once DIR.'/php/includes/session.php';

global $db;
$db = new Database();

?>