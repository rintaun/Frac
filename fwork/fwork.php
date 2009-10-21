<?php
/*
 * Fwork
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FWORK_")) die("This file cannot be invoked directly.");

require_once(dirname(__FILE__) . "/icontroller.php");
require_once(dirname(__FILE__) . "/../lib/Doctrine/Doctrine.php");
require_once(dirname(__FILE__) . "/../lib/Savant3/Savant3.php");

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
		Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_TBLNAME_FORMAT, $config["database"]["prefix"] . "%s");
	    Doctrine::loadModels(dirname(__FILE__) . "/../models");
	    
		// we should now connect to the database
		$this->dbconnection = Doctrine_Manager::connection($config["database"]["dsn"]);
	}
	
	/**
	 * Serve a page with Fwork.
	 *
	 * @param $path An array of path data, obtained by array_slice(explode("/", $_SERVER["PATH_INFO"]), 1)
	 */
	public function serve($path)
	{
		$controllerprovider = $path[0];
		$controllername = ucfirst($path[0]) . "Controller";
		if(!file_exists(dirname(__FILE__) . "/../controllers/" . $controllerprovider . ".php"))
		{
			die("Cannot find controller file"); // handle this properly later
		} else {
			require_once(dirname(__FILE__) . "/../controllers/" . $controllerprovider . ".php");
		}
		
		if(!class_exists($controllername))
		{
			die("Cannot find controller class"); // handle this properly later
		}
		
		if(!in_array("IController", class_implements($controllername)))
		{
			die("Controller class does not implement IController"); // not very good! D:
		}
		
		$controller = new $controllername();
		
		$action = isset($path[1]) ? $path[1] : "index";
		
		$controller->{$action}(isset($path[2]) ? array_slice($path, 2) : array());
		
		if(!file_exists(dirname(__FILE__) . "/../themes/" . "fraculous" . "/" . $controllerprovider . "/" . $action . ".php"))
		{
			die("View not found"); // handle this properly later
		} else {
			require_once(dirname(__FILE__) . "/../themes/" . "fraculous" . "/" . $controllerprovider . "/" . $action . ".php");
		}
	}
	
	/**
	 * Destructor for Fwork.
	 * Cleans up things.
	 */
	public function __destruct()
	{
		Doctrine_Manager::getInstance()->closeConnection($this->dbconnection);
	}
}
