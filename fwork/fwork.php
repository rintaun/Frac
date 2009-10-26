<?php
/*
 * Fwork
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FWORK_")) die("This file cannot be invoked directly.");

require_once(dirname(__FILE__) . "/utils.php");
require_once(dirname(__FILE__) . "/controller.php");
require_once(dirname(__FILE__) . "/sesman.php");
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
		$session = SesMan::getInstance();

		Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
		Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_TBLNAME_FORMAT, $config["database"]["prefix"] . "%s");
		Doctrine::loadModels(dirname(__FILE__) . "/../models");
	    
		// we should now connect to the database
		Doctrine_Manager::connection($config["database"]["dsn"]);
		
		// Create an instance of Savant3
		$this->savant = new Savant3();
		
		// Savant's Path prefix defaults to ./ so we have to first
		// change it to the theme folder.
		$this->savant->setPath("template", dirname(__FILE__) . "/../themes/" . "fraculous" . "/");
		
		// Savant resources etc.
		$this->savant->setPath("resource", dirname(__FILE__) . "/savant");
		
		// Give Savant our basepath
		$this->savant->basepath = Utils::basepath();
		
		// Give Savant our themepath
		$this->savant->themepath = $this->savant->basepath . "/themes/" . "fraculous";

		// Get our plugin, because savant is retarded.
		$this->savant->frac = $this->savant->plugin("frac");		
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
		$session = SesMan::getInstance();
		// do not use controllers for this, doing so is inefficient
		header("HTTP/1.1 404 Not Found");
		$this->savant->session = $session;
		$this->savant->error = $error; // disregard this I suck cock for production mode
		$this->savant->pagename = "Error";
		$this->savant->view = "error.php";
		$this->savant->display("layout.php");
	}
	
	/**
	 * Serve a page with Fwork (to serve man~).
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
		$session = SesMan::getInstance();

		if (!isset($session['staffid'])) $path = array('staff','login'); // if they're not logged in, send them to login, PERIOD.
		
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
		$controller->session = $this->savant->session = $session;
		
		$action = isset($path[1]) && !empty($path[1]) ? $path[1] : "index";
		
		if(!method_exists($controller, $action) || $action[0] == "_") // we require you to be sensible and not have private/protected methods that are not prefixed with _
		{
			$this->error("Controller does not provide a behaviour for the provided action");
			return;
		}
		
		$controller->{$action}(isset($path[2]) ? array_slice($path, 2) : array());
		
		if($controller->useView !== null)
		{
			// load the view
			if(!file_exists(dirname(__FILE__) . "/../themes/" . "fraculous" . "/" . $controllerprovider . "/" . $action . ".php"))
			{
				$this->error("View not found");
				return;
			} else {
				$vars = $controller->vars;
				$vars["pagename"] = isset($vars["pagename"]) ? $vars["pagename"] : ucfirst($controllerprovider);
				$this->savant->assign($vars);
				$this->savant->view = $controllerprovider . "/" . $action . ".php";
				$this->savant->display("layout.php");
			}
		}
	}
	
	/**
	 * Destructor for Fwork.
	 * Cleans up things and destroys singletons.
	 */
	public function __destruct()
	{
		$dm = Doctrine_Manager::getInstance();
		unset($dm);
		
		$session = &SesMan::getInstance();
		unset($session);
	}
}
