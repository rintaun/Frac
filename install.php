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

require_once(dirname(__FILE__) . "/config.php"); // eventually this will be user-input generated

require_once(dirname(__FILE__) . "/lib/Doctrine/Doctrine.php");
spl_autoload_register(array("Doctrine", "autoload"));

Doctrine_Manager::connection($config["database"]["dsn"]);
Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_TBLNAME_FORMAT, $config["database"]["prefix"] . "%s");
Doctrine::createTablesFromModels(dirname(__FILE__) . "/models");
