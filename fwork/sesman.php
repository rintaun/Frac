<?php
/*
 * Fwork
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FWORK_")) die("This file cannot be invoked directly.");

/**
 * Session manager singleton. Basically a simplistic wrapper around PHP's session_*.
 */
final class SesMan extends Singleton implements arrayaccess
{
	public static function getInstance()
	{
		$c = get_class();
		if(!isset(self::$instances[$c])) self::$instances[$c] = new $c;
		return self::$instances[$c];
	}	
	
	/**
	 * Create a session manager class (duh :)).
	 */
	protected function __construct()
	{
		session_start();
	}
	
	/**
	 * Part of arrayaccess, see www.php.net/arrayaccess.
	 */
	public function offsetSet($offset, $value) {
		$_SESSION[$offset] = $value;
	}
    
	/**
	 * Part of arrayaccess, see www.php.net/arrayaccess.
	 */    
	public function offsetExists($offset) {
		return isset($_SESSION[$offset]);
	}

	/**
	 * Part of arrayaccess, see www.php.net/arrayaccess.
	 */
	public function offsetUnset($offset) {
		unset($_SESSION[$offset]);
	}
	
	/**
	 * Part of arrayaccess, see www.php.net/arrayaccess.
	 */
	public function offsetGet($offset)
	{
		if (isset($_SESSION[$offset]))
			return $_SESSION[$offset];
		else
			return null;
	}
	
	/**
	 * Unset everything.
	 * This is awesome and not dangerous at all!
	 */
	public function flush()
	{
		session_unset();
	}

	/**
	 * Destroy the session manager.
	 * Does nothing.
	 */
	public function __destruct() { }
}
