<?php

/**
 * index.php
 *
 * @author     Andreas Mueller <webmaster@am-wd.de>
 * @copyright  (c) 2016 Andreas Mueller
 * @license    MIT - http://am-wd.de/?p=about#license
 */

// Report everything just to be sure it's clean code
// But notices sucks some times
@error_reporting(E_ALL ^ E_NOTICE);
@ini_set('display_errors', 'on');

// Define the DIR variable
define('DIR', str_replace('\\', '/', __DIR__));

// Define the URL variable
$url = str_replace($_SERVER['DOCUMENT_ROOT'], 'http://'.$_SERVER['HTTP_HOST'], DIR);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $url = str_replace('http', 'https', $url);
define('URL', $url);

// Define output content type
header('Content-Type: text/html; Charset=UTF-8');

// Load all needed stuff
require_once DIR.'/php/includes/include.php';

// Enforce using TLS encrypted connection (HTTPS)
// Except: it is disabled in the config
if ($config['require_https'] && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on'))
{
	header('HTTP/1.1 302 Found');
	header('Location: '.str_replace('http:', 'https:', URL));
	die('Please reload the page: <a href="'.str_replace('http:', 'https:', URL).'">'.str_replace('http:', 'https:', URL).'</a>');
}

// Prepare statics for the page
// Load the template
$page = new AMWD\Page(DIR.'/php/layout.html');

// Set the brand (at the left of the navbar)
$page->setBrand('<span class="fa fa-heartbeat"></span> BS Erlangen');

// Set the title shown in the browsers head
$page->setTitle('SSD BS Erlangen');

// Set the footer with some information
$page->setFooter('&copy; '.date('Y').' BS Erlangen | <a href="'.URL.'/?p=imprint">Impressum</a> - <a href="'.URL.'/?p=privacy">Datenschutz</a>');

// Load CSS and JS files needed
$page->addCSS('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
$page->addCSS(URL.'/css/bootstrap-outline.min.css');
$page->addCSS('https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css');
$page->addCSS(URL.'/submodules/datepicker/dist/css/bootstrap-datepicker3.min.css');
$page->addCSS(URL.'/css/layout.min.css');
$page->addCSS(URL.'/css/main.css');

$page->addJS('https://code.jquery.com/jquery-2.2.3.min.js');
$page->addJS('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
$page->addJS(URL.'/submodules/datepicker/dist/js/bootstrap-datepicker.min.js');
$page->addJS(URL.'/submodules/datepicker/dist/locales/bootstrap-datepicker.de.min.js');
$page->addJS(URL.'/js/api.js');
$page->addJS(URL.'/js/main.js');

// Load the contents
require_once DIR.'/php/contents/load.php';

echo $page;

?>