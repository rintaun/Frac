<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

class AdminController extends Controller
{   
	public function index($args) // display settings, like phpinfo()
	{	
	}

	public function settings($args) // edit the settings
	{
		$p = PermissionHandler::getInstance();
		// do we have an error thing?
		if (!$p->allowedto(PERM_EDIT_SETTINGS))
		{
			Utils::error("You don't have permission to edit settings.");
			return;
		}
	}
}
