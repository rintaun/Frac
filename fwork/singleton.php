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
 * Singleton class. Provides automatic(-ish) creation of a singleton.
 */
abstract class Singleton
{
	/**
	 * Instances of singletons. This MUST be declared protected as all Singletons are final classes, and thus you cannot inherit $instances.
	 */
	protected static $instances = array();
	
	/**
	 * Return an instance of the singleton.
	 *
	 * TODO: Review this -- get_called_class() works only for PHP >= 5.3.0
	 */
	final public static function getInstance()
	{
		$c = get_called_class();
		if(!isset(self::$instances[$c])) self::$instances[$c] = new $c;
		return self::$instances[$c];
	}
}
