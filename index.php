<?php
/*
 * Frac
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

define("IN_FRAC_", true);
define("IN_FWORK_", true);

// Perform some extension sanity checks.
// Though if you don't have these, your PHP install is messed up.
if(!function_exists('spl_autoload_register'))
	die('Frac requires the Standard PHP Library extension to be loaded.');
if(!function_exists('session_start'))
	die('Frac requires PHP Session support to be enabled.');
if(!class_exists('PDO'))
	die('Frac requires the PHP Data Objects extension to be loaded.');
if((count(PDO::getAvailableDrivers()) == 1) && (in_array('sqlite', PDO::getAvailableDrivers())))
	die('Frac requires a non-sqlite PDO driver to be loaded.');

require_once("fwork/fwork.php");
if(!file_exists("config.php"))
	die("Unable to find config.php; if you have not run the installer, please <a href=\"install.php\">do so now</a>.");
require_once("config.php");

// check if you want gzip compression
if ($config["site"]["gzip"] && substr_count($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip"))
	ob_start("ob_gzhandler");
else
	ob_start();

$time = array_sum(explode(" ", microtime()));
$fwork = new Fwork($config);
$fwork->serve(!empty($_GET["q"]) ? explode("/", $_GET["q"]) : array("index"));
unset($fwork);

if($config["site"]["gentime"])
	printf("<!-- generated in %.2f seconds -->", array_sum(explode(" ", microtime())) - $time);
	
// end the buffer
ob_end_flush();

?>