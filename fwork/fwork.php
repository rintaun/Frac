<?php
/*
 * Fwork
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FWORK_")) die("This file cannot be invoked directly.");

require_once(dirname(__FILE__) . "../lib/Doctrine/Doctrine.php");

spl_autoload_register(array("Doctrine", "autoload"));

/**
 * Fwork core class.
 *
 * This class is the core of the Fwork framework.
 */
class Fwork
{
	
	/**
	 * Doctrine connection to the database.
	 */
	private $dbconnection;
	
	/**
	 * Constructor for Fwork.
	 * Prepares everything.
	 *
	 * @param $config Configuration data, obtained from config.php
	 */
	public function __construct($config)
	{   
		// we should now connect to the database
		$this->dbconnection = Doctrine_Manager::connection($config["database"])
	}
	
	/**
	 * Serve a page with Fwork.
	 *
	 * @param $path An array of path data, obtained by explode("/", $_SERVER["PATH_INFO"])
	 */
	public function serve($path) { }
	
	/**
	 * Destructor for Fwork.
	 *
	 * Currently also does nothing.
	 */
	public function __destruct() { }
}
