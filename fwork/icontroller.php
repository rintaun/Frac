<?php
/*
 * Fwork
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FWORK_")) die("This file cannot be invoked directly.");

/**
 * Controller interface. All controllers must implement this.
 */
interface IController
{
	/**
	 * A controller MUST implement an index() function.
	 *
	 * @param $args Arguments from the path.
	 */
	public function index($args);
}
