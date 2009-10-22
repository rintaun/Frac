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
 * Abstract Controller class. All controllers must extend this.
 */
abstract class Controller
{

	public $vars = array(); // the variable that store template variables.

	/**
	 * A controller MUST implement an index() function.
	 *
	 * @param $args Arguments from the path.
	 */
	abstract public function index($args);
}
