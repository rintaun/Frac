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
	 * Instance of the singleton. This MUST be declared protected as all Singletons are final classes, and thus you cannot inherit $instance.
	 */
	protected static $instance;
	
	/**
	 * Constructor for the singleton.
	 *
	 * Override me!
	 */
	abstract protected function __construct();
	
	/**
	 * Return an instance of the singleton. This MUST be implemented as follows:
	 *
	 * <code>
	 * public static function getInstance()
	 * {
	 *     if(!isset(self::$instance)) self::$instance = new self();
	 *     return self::$instance;
	 * }
	 * </code>
	 *
	 * It cannot be implemented here as self will refer to Singleton and not the class extended.
	 */
	abstract public static function getInstance();
}
