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

require_once(dirname(__FILE__) . "/fwork/fwork.php");
if(!file_exists(dirname(__FILE__) . "/config.php")) die("Unable to find config.php; if you have not run the installer, please <a href=\"install.php\">do so now</a>.");
require_once(dirname(__FILE__) . "/config.php");

$fwork = new Fwork($config);
$fwork->serve(isset($_GET["q"]) ? explode("/", $_GET["q"]) : array("index"));
unset($fwork);
