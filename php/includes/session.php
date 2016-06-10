<?php

/**
 * session.php
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

/**
 * Tries to provide basic security checkings to prevent a man in the middle attack.
 * 
 * @author     Andreas Mueller <webmaster@am-wd.de>
 * @copyright  (c) 2016 Andreas Mueller
 * @license    MIT - http://am-wd.de/index.php?p=about#license
 */

// Name of the session.
$session_name = 'SSDBSER';

// Set a value that indicates whether the IPs should be checked if possible.
// Therefore the $_SESSION['remote_addr'] value has to be set at a proper position in your code.
$check_ip = false;

// Set the name.
session_name($session_name);

// Check if there is a session given by $_GET.
if (!empty($_GET[$session_name]))
	session_id($_GET[$session_name]);

// Start the session.
session_start();

$valid_ips = array('::1', '127.0.0.1', $_SERVER['SERVER_ADDR']);
if (!empty($_SESSION['remote_addr']))
	$valid_ips[] = $_SESSION['remote_addr'];

// Try to check if possible
if (isset($check_ip) && $check_ip
	&& isset($_SESSION['remote_addr']) && !in_array($_SERVER['REMOTE_ADDR'], $valid_ips))
{
	header('HTTP/1.1 403 Forbidden');
	die('The remote address changed while session was active.');
}

?>