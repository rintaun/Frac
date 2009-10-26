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
 * Session manager singleton. Basically a simplistic wrapper around PHP's session_*.
 */
final class SesMan implements arrayaccess
{
	/** Instance of SesMan. */
	public static $instance;
	
	/**
	 * Create a session manager class (duh :)).
	 */
	private function __construct()
	{
		session_start();
	}
	
	/**
	 * Do not allow cloning of the session manager.
	 */
	private function __clone() { }
	
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
		return $_SESSION[$offset];
	}
	
	/**
	 * Get the base path.
	 *
	 * @return Returns the base path.
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new self();
		}
		
		return self::$instance;
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
