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
 * Utility functions, a pseudo-Java-style package.
 */
final class Utils
{
	/** Disallow construction of a Utils class. */
	private function __construct() { }
	
	/**
	 * Get the base path.
	 *
	 * @return Returns the base path.
	 */
	public static function basepath()
	{
		return dirname($_SERVER["SCRIPT_NAME"]);
	}
	
	/**
	 * Redirect a page internally.
	 *
	 * @param $path Path, usually controller/action/arguments, or even an array.
	 */
	public static function redirect($path)
	{
		header("Location: " . self::basepath() . "/" . (is_array($path) ? implode("/", $path) : $path));
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
}
