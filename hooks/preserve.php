<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

// commands to run before Fwork::__construct is executed. Full access to the Fwork object is provided in its state at the beginning of Fwork::serve.

$session = SesMan::getInstance();

// we don't want to redirect them if they're already at login...
// ... is there some better way of checking for this? O_o
if ((!isset($session['staffid'])) && (($path[0] != "staff") || ($path[1] != "login")))
{
	// if they're not logged in, send them to login, PERIOD.
	// But set their last page first.
	$session['lastpage'] = 'staff/login';
	
	Utils::redirect("staff/login");
	return; 
}

$this->savant->staffid = $session['staffid'];

// they're logged in, so start the permissionhandler
$permissions = PermissionHandler::getInstance();
$permissions->id = $session['staffid'];
