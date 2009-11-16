<?php
/*
 * Fwork
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FWORK_")) die("This file cannot be invoked directly.");

/**
 * Utility functions, a pseudo-Java-style package.
 */
final class Utils
{
	/** Disallow construction of a Utils class. */
	protected function __construct() { }
	
	/**
	 * Get the base path.
	 *
	 * @return Returns the base path.
	 */
	public static function basepath()
	{
		$dirname = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
		return $dirname === '/' ? '' : $dirname;
	}
	
	/**
	 * Redirect a page internally.
	 *
	 * @param $path Path, usually controller/action/arguments, or even an array.
	 */
	public static function redirect($path)
	{
		$session = SesMan::getInstance();
		
		$session["redirected"] = true;
		header("Location: " . self::createuri($path));
	}

	/**
	 * Get a username from a user ID.
	 * 
	 * @param $id User ID.
	 * @return The user name.
	 */
	public static function idtouser($id)
	{
		return Doctrine::getTable("Staff")->find($id)->nickname;
	}
	
	/**
	 * Get a template name from a template ID.
	 * 
	 * @param $id template ID.
	 * @return The template name.
	 */
	public static function idtotemplate($id)
	{
		return Doctrine::getTable("Template")->find($id)->name;
	}
	
	/**
	 * Creates an absolute URI.
	 *
	 * @param $path Path, usually controller/action/arguments, or even an array.
	 * @return An absolute URI pointing to the given path.
	 */
	public static function createuri($path)
	{
		return self::basepath() . '/' . (is_array($path) ? implode("/", $path) : $path);
	} 
	
	/**
	 * Display an error and return to the last page.
	 *
	 * @param $error Error message to display.
	 */
	public static function error($message)
	{
		$session = SesMan::getInstance();
		$session['flash'] = array('type' => 'error', 'message' => $message);

		self::redirect($session['lastpage']);
	}
	
	/**
	 * Display a warning and return to the last page.
	 *
	 * @param $message Message to display.
	 */
	public static function warning($message)
	{
		$session = SesMan::getInstance();
		$session['flash'] = array('type' => 'warning', 'message' => $message);
	}
	
	/**
	 * Keikaku doori message.
	 *
	 * @param $message Message to display.
	 */
	public static function success($message, $view = null)
	{
		$session = SesMan::getInstance();
		$session['flash'] = array('type' => 'success', 'message' => $message);
	}
}
