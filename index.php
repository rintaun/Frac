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
if(!file_exists(dirname(__FILE__) . "/config.php")) die("Unable to find config.php; if you have not run the installer, please <a href=\"install\">do so now</a>.");
require_once(dirname(__FILE__) . "/config.php");

$fwork = new Fwork($config);
$fwork->serve(array_slice(explode("/", $_SERVER['PATH_INFO']), 1));
unset($fwork);
