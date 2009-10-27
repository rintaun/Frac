<?php
/*
 * Fwork
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FWORK_")) die("This file cannot be invoked directly.");

/**
 * Singleton interface. Used to amend "Static function Singleton::getInstance() should not be abstract".
 */
interface ISingleton
{
	/**
	 * Return an instance of the singleton.
	 *
	 * This must be implemented in child classes, due to a lack of LSB for PHP < 5.3.0, and it must be this:
	 *
	 * <code>
	 * public static function getInstance()
	 * {
	 *     $c = get_class();
	 *     if(!isset(self::$instances[$c])) self::$instances[$c] = new $c;
	 *     return self::$instances[$c];
     * }
     * </code>
     *
     * This will be fixed in the future when PHP 5.3.0 becomes widely used.
	 */
	public static function getInstance();
}

/**
 * Singleton class. Provides automatic(-ish) creation of a singleton.
 */
abstract class Singleton implements ISingleton
{
	/**
	 * Instances of singletons. This MUST be declared protected as all Singletons are final classes, and thus you cannot inherit $instances.
	 */
	protected static $instances = array();
	
	/**
	 * Do not allow cloning of Singletons.
	 */
	protected function __clone() { }
}
