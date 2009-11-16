<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

define("FRAC_VERSION", "0.1-alpha");

define("IN_FRAC_", true);
define("IN_FWORK_", true);

// Verify PHP version.
if(!defined('PHP_VERSION_ID'))
{
    $version = explode('.',PHP_VERSION);
    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}
if(PHP_VERSION_ID < 50000)
	die('Frac requires PHP 5.');

// Perform some extension sanity checks.
// Though if you don't have these, your PHP install is messed up.
if(!function_exists('spl_autoload_register'))
	die('Frac requires the Standard PHP Library extension to be loaded.');
if(!function_exists('session_start'))
	die('Frac requires PHP Session support to be enabled.');
if(!class_exists('PDO'))
	die('Frac requires the PHP Data Objects extension to be loaded.');

require_once("fwork/fwork.php");
if(!file_exists("config.php"))
	die("Unable to find config.php; if you have not run the installer, please <a href=\"install.php\">do so now</a>.");
require_once("config.php");

$time = array_sum(explode(" ", microtime()));
$fwork = new Fwork($config);
$fwork->serve(!empty($_GET["q"]) ? explode("/", $_GET["q"]) : array("index"));
unset($fwork);
