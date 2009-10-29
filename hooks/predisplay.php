<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

// commands to run before Fwork::savant::display is executed. Please note that this file can be called in either Fwork::error or Fwork::serve so access to controllers is not guaranteed.

$session = SesMan::getInstance();

if(isset($session["flash"]) && !isset($session["redirected"]) && !$session["redirected"])
{
	$this->savant->flashmsg = $session["flash"];
	// if we had a flash message, NOW WE DON'T
	unset($session["flash"]);
}

if(isset($session["redirected"])) unset($session["redirected"]);
