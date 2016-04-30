<?php

/**
 * api.php
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

/**
 * Provides a backend for the api.js function.
 * 
 * @author     Andreas Mueller <webmaster@am-wd.de>
 * @copyright  (c) 2016 Andreas Mueller
 * @license    MIT - http://am-wd.de/index.php?p=about#license
 */

// Report everything just to be sure it's clean code.
@error_reporting(E_ALL ^ E_NOTICE);
@ini_set('display_errors', 'on');

// Define the DIR variable
define('DIR', str_replace('/php/ajax', '', __DIR__));

// Define the URL variable
$url = str_replace($_SERVER['DOCUMENT_ROOT'], 'http://'.$_SERVER['HTTP_HOST'], DIR);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $url = str_replace("http", "https", $url);
define('URL', $url);

// Define output content type
header('Content-Type: text/html;charset=utf-8');

// Load all needed stuff
require_once DIR.'/php/includes/include.php';

// Load all API functions in subfiles.
$dir = scandir(__DIR__);
foreach ($dir as $file)
{
	if ($file != 'api.php' && strpos($file, '.api.php') !== false)
		include_once __DIR__.'/'.$file;
}

// Get request and decode it.
$params = json_decode(file_get_contents('php://input'));

// Prepare response.
$response = new stdClass();
$response->error = '';
$response->data = '';

// Try to execute the requested function.
if (function_exists($params->func))
{
	try
	{
		$response->data = @call_user_func($params->func, $params->data);
	}
	catch (Exception $ex)
	{
		$response->error = $ex->getMessage();
	}
}
else
{
	$response->error = 'Function "'.$params->func.'" not found.';
}

// Write proper response.
header('Content-Type: application/json;charset=utf-8');
echo json_encode($response);

?>