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
	/** The variable that stores template variables, which are passed onto Savant. */
	public $vars = array(); 

	/** This variable allows an action in a controller to change what View is used to display it. A value of null means no view will be displayed. */
	public $useView = '';

	/**
	 * A controller MUST implement an index() function.
	 *
	 * @param $args Arguments from the path.
	 */
	abstract public function index($args);
}
