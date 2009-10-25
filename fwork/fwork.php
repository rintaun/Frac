<?php
/*
 * Fwork
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FWORK_")) $this->error("This file cannot be invoked directly.");

require_once(dirname(__FILE__) . "/controller.php");
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
	 * Provider for Savant.
	 */
	private $savant;
	
	/**
	 * Constructor for Fwork.
	 * Prepares everything.
	 *
	 * @param $config Configuration data, obtained from config.php
	 */
	public function __construct($config)
	{	
		// LOL start the session
		session_start();

		Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
		Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_TBLNAME_FORMAT, $config["database"]["prefix"] . "%s");
		Doctrine::loadModels(dirname(__FILE__) . "/../models");
	    
		// we should now connect to the database
		$this->dbconnection = Doctrine_Manager::connection($config["database"]["dsn"]);
		
		// Create an instance of Savant3
		$this->savant = new Savant3();
		
		// Get our plugin, because savant is retarded.
		$this->savant->frac = $this->savant->plugin("frac");
		
		// Savant's Path prefix defaults to ./ so we have to first
		// change it to the theme folder.
		$this->savant->setPath("template", dirname(__FILE__) . "/../themes/" . "fraculous" . "/");
		
		// Give Savant our basepath
		$this->savant->basepath = dirname($_SERVER["SCRIPT_NAME"]);
		
		// Give Savant our themepath
		$this->savant->themepath = $this->savant->basepath . "/themes/" . "fraculous";

		// check if we're logged in; relevant before error messages.
		if ($_SESSION['loggedin'] === true)
		{
			$this->savant->loggedin = true;
			// since we're logged in, let's get and store the logged in user's Staff object for reference.
			// this should be set in the session at login. ...but make sure because rofflwaffls ;A;
			if (isset($_SESSION['staffid']))
			{
				$this->user = Doctrine::getTable('Staff')->find($_SESSION['staffid']);
				$this->savant->nickname = $this->user->nickname;
			}
			// if it's not set, something went wrong. make them login again.
			else
			{
				unset($_SESSION['loggedin']);
				$this->savant->loggedin = false;
			}
		}
		else $this->savant->loggedin = false;
		
	}
	
	/**
	 * Serve an error with Fwork.
	 *
	 * This is internal ONLY. There is no reason a Controller should/would throw an Error like this.
	 *
	 * @param $error Error thrown.
	 */
	private function error($error)
	{
		// do not use controllers for this, doing so is inefficient
		header("HTTP/1.1 404 Not Found");
		$this->savant->error = $error;
		$this->savant->pagename = "Error";
		$this->savant->view = "error.php";
		$this->savant->display("layout.php");
	}
	
	/**
	 * Serve a page with Fwork.
	 *
	 * A few notes about this:
	 * - If no action is explicitly specified, $controller->index($args) will be called.
	 * - $args is an array of the path data sans controller name and action.
	 * - Models will be autoloaded by Doctrine, i.e. $staff = new Staff(); inside a controller is fine, providing that a Staff model exists.
	 *
	 * @param $path An array of path data, obtained by explode("/", $_GET["q"])
	 */
	public function serve($path)
	{
		// load the controller
		$controllerprovider = $path[0];
		$controllername = ucfirst($path[0]) . "Controller";

		// send the name of the controller to savant
		$this->savant->controller = $controllerprovider;

		if(!file_exists(dirname(__FILE__) . "/../controllers/" . $controllerprovider . ".php"))
		{
			$this->error("Cannot find controller file");
			return;
		} else {
			require_once(dirname(__FILE__) . "/../controllers/" . $controllerprovider . ".php");
		}
		
		if(!class_exists($controllername))
		{
			$this->error("Cannot find controller class");
			return;
		}
		
		if(!in_array("Controller", class_parents($controllername)))
		{
			$this->error("Controller class does not extend Controller");
			return;
		}

		$controller = new $controllername();
		
		$action = isset($path[1]) && !empty($path[1]) ? $path[1] : "index";
		
		$controller->{$action}(isset($path[2]) ? array_slice($path, 2) : array());

		// if the controller has changed the view that it needs to use, use it.
		if (!empty($controller->useView)) $action = $controller->useView;
		
		// load the view
		if(!file_exists(dirname(__FILE__) . "/../themes/" . "fraculous" . "/" . $controllerprovider . "/" . $action . ".php"))
		{
			$this->error("View not found");
			return;
		} else {
			// check if we're logged in again, just to make sure; this could have changed in the controller.
			if ($_SESSION['loggedin'] === true)
			{
				$this->savant->loggedin = true;
				if ((!isset($this->user)) && (isset($_SESSION['staffid']))) $this->user = Doctrine::getTable('Staff')->find($_SESSION['staffid']);
				$this->savant->nickname = $this->user->nickname;				
			}
			else $this->savant->loggedin = false;

			$vars = $controller->vars;
			$vars["pagename"] = isset($vars["pagename"]) ? $vars["pagename"] : ucfirst($controllerprovider);
			foreach($vars as $key => $value)
			{
				$this->savant->{$key} = $value;
			}
			$this->savant->view = $controllerprovider . "/" . $action . ".php";
			$this->savant->display("layout.php");
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
