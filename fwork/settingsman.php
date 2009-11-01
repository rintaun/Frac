<?php
/*
 * Fwork
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FWORK_")) die("This file cannot be invoked directly.");

/**
 * Settings manager.
 */
final class SettingsMan extends Singleton implements arrayaccess
{
	protected $vars = array();
	
	protected function __construct()
	{
		$vars = Doctrine_Query::create()
			->select("")
			->from("Setting")
			->fetchArray();
		foreach($vars as $v)
		{
			$this->vars[$v["name"]] = $v["value"];
		}
	}
	
	public static function getInstance()
	{
		$c = get_class();
		if(!isset(self::$instances[$c])) self::$instances[$c] = new $c;
		return self::$instances[$c];
	}
	
	/**
	 * Part of arrayaccess, see www.php.net/arrayaccess.
	 */
	public function offsetSet($offset, $value) {
		if(!isset($this->vars[$offset]))
		{
			$setting = new Setting();
			$setting["name"] = $offset;
			$setting["value"] = $value;
			$setting->save();
		} else {		
			Doctrine_Query::create()
				->update("Setting")
				->set("value", "?", $value)
				->where("name = ?", $offset)
				->execute();
		}
		$this->vars[$offset] = $value;
	}
    
	/**
	 * Part of arrayaccess, see www.php.net/arrayaccess.
	 */    
	public function offsetExists($offset) {
		return isset($this->vars[$offset]);
	}

	/**
	 * Part of arrayaccess, see www.php.net/arrayaccess.
	 */
	public function offsetUnset($offset) {
		unset($this->vars[$offset]);
		Doctrine_Query::create()
			->delete("Settings")
			->where("name = ?", $offset)
			->execute();
	}
	
	/**
	 * Part of arrayaccess, see www.php.net/arrayaccess.
	 */
	public function offsetGet($offset)
	{
		return $this->vars[$offset];
	}
}
