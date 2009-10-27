<?php
/*
 * Frac
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

// commands to run before Fwork::serve is executed. Full access to the Fwork object is provided, as well as SesMan.

$session = SesMan::getInstance();

// we don't want to redirect them if they're already at login...
// ... is there some better way of checking for this? O_o
if ((!isset($session['staffid'])) && (($path[0] != "staff") || ($path[1] != "login")))
{
	// if they're not logged in, send them to login, PERIOD.
	Utils::redirect("staff/login");
	return; 
}

$this->savant->staffid = $session['staffid'];

// they're logged in, so start the permissionhandler
$permissions = PermissionHandler::getInstance();
$permissions->id = $session['staffid'];
