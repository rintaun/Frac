<?php
/*
 * Fwork
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FWORK_")) die("This file cannot be invoked directly.");

require_once(dirname(__FILE__) . "/utils.php");
require_once(dirname(__FILE__) . "/controller.php");
require_once(dirname(__FILE__) . "/singleton.php");
require_once(dirname(__FILE__) . "/lib/Doctrine/Doctrine.php");
require_once(dirname(__FILE__) . "/lib/Savant3/Savant3.php");
if(file_exists(dirname(__FILE__) . "/../hooks/includes.php")) require_once(dirname(__FILE__) . "/../hooks/includes.php");

spl_autoload_register(array("Doctrine", "autoload"));

function __autoload($class_name)
{
	require_once(dirname(__FILE__) . "/../plugins/" . $class_name . ".php");
}

/**
 * Fwork core class.
 *
 * This class is the core of the Fwork framework.
 */
final class Fwork
{
	/**
	 * Provider for Savant.
	 */
	protected $savant;
	
	/**
	 * Constructor for Fwork.
	 * Prepares everything.
	 *
	 * @param $config Configuration data, obtained from config.php
	 */
	public function __construct($config)
	{
		// execute the hook if there is one
		if(file_exists(dirname(__FILE__) . "/../hooks/preconstruct.php")) require_once(dirname(__FILE__) . "/../hooks/preconstruct.php");
		
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
		
		// execute the hook if there is one
		if(file_exists(dirname(__FILE__) . "/../hooks/preconstruct.php")) require_once(dirname(__FILE__) . "/../hooks/postconstruct.php");
	}
	
	/**
	 * Serve an error with Fwork.
	 *
	 * This is internal ONLY. There is no reason a Controller should/would throw an Error like this.
	 *
	 * @param $error Error thrown.
	 */
	protected function error($error)
	{
		$session = SesMan::getInstance();
		// do not use controllers for this, doing so is inefficient
		header("HTTP/1.1 404 Not Found");
		$this->savant->session = $session;
		$this->savant->error = $error; // disregard this I suck cock for production mode
		$this->savant->pagename = "Error";
		$this->savant->view = "error.php";
		
		// execute the hook if there is one (yes, even here)
		if(file_exists(dirname(__FILE__) . "/../hooks/predisplay.php")) require_once(dirname(__FILE__) . "/../hooks/predisplay.php");
		
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
		// execute the hook if there is one
		if(file_exists(dirname(__FILE__) . "/../hooks/preserve.php")) require_once(dirname(__FILE__) . "/../hooks/preserve.php");
		
		$this->_serve($path); // invoke the super secret serve function! :O
		
		// execute the hook if there is one
		if(file_exists(dirname(__FILE__) . "/../hooks/postserve.php")) require_once(dirname(__FILE__) . "/../hooks/postserve.php");
	}
	
	/**
	 * This is the REAL serve function, and should only be invoked INTERNALLY from Fwork::serve.
	 */
	protected function _serve($path)
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
		
		if(!is_subclass_of($controllername, "Controller"))
		{
			$this->error("Controller class does not extend Controller");
			return;
		}

		$controller = new $controllername();
		$controller->session = $this->savant->session = SesMan::getInstance();
		
		$action = isset($path[1]) && !empty($path[1]) ? $path[1] : "index";
		
		if(!method_exists($controller, $action) || $action[0] == "_") // we require you to be sensible and not have protected/protected methods that are not prefixed with _
		{
			$this->error("Controller does not provide a behaviour for the provided action");
			return;
		}
		
		$controller->{$action}(isset($path[2]) ? array_slice($path, 2) : array());
		
		if($controller->view !== null)
		{
			// two formats possible for view: array(controller, action)
			// or just action (if same controller)
			if (is_array($controller->view))
			{
				$controllerprovider = $controller->view[0];
				$action = $controller->view[1];
			}
			elseif (!empty($controller->view))
				$action = $controller->view;
		
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

				// execute the hook if there is one
				if(file_exists(dirname(__FILE__) . "/../hooks/predisplay.php")) require_once(dirname(__FILE__) . "/../hooks/predisplay.php");

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
		// execute the hook if there is one
		if(file_exists(dirname(__FILE__) . "/../hooks/predestruct.php")) require_once(dirname(__FILE__) . "/../hooks/predestruct.php");
		
		$dm = Doctrine_Manager::getInstance();
		unset($dm);
		
		// execute the hook if there is one
		if(file_exists(dirname(__FILE__) . "/../hooks/postdestruct.php")) require_once(dirname(__FILE__) . "/../hooks/postdestruct.php");
	}
}
