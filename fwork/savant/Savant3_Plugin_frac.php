<?php
/*
 * Frac
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

class Savant3_Plugin_frac extends Savant3_Plugin
{
	/**
	 * Call Utils::idtouser($id).
	 *
	 * @param $id User ID.
	 * @return The user name.
	 */
	public function idtouser($id)
	{
		return Utils::idtouser($id);
	}
}
?>
