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

	/**
	 * This variable allows an action in a controller to change what View is used to display it. 
	 *
	 * A few notes on this:
	 * - a value of "" will display the view associated automatically to a controller, i.e. UserController will display user.
	 * - a value of null will not display a controller. Should only be used in conjunction with Utils::redirect().
	 * - a value of anything else will cause Fwork to attempt to display the set view.
	 */
	public $view = "";

	/**
	 * A controller MUST implement an index() function.
	 *
	 * @param $args Arguments from the path.
	 */
	abstract public function index($args);

	public function error($message)
	{
		// this should be changed.
		// it is just so that error messages work at all for right now.

		$this->useView=array("","error");
		$this->vars['error'] = $message;
	}
}
