<?php
/*
 * Fwork
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

if(!defined("IN_FWORK_")) die("This file cannot be invoked directly.");

/**
 * Internationalisation manager singleton. Cooler than gettext.
 */
final class I18NMan extends Singleton implements arrayaccess
{
	/** Code of the locale. **/
	protected $locale;
	
	/** Language strings. **/
	protected $lcdata = array();
	
	/**
	 * Set the language code.
	 *
	 * @param $code gettext-style language code, i.e. en_US for English (United States).
	 */
	public function bind($code)
	{
		if(file_exists(dirname(__FILE__) . "/../locale/" . $code . ".json"))
		{
			$this->locale = $code;
			$this->lcdata = json_decode(file_get_contents(dirname(__FILE__) . "/../locale/" . $code . ".json")));
		} elseif(file_exists(dirname(__FILE__) . "/../locale/en_US.json")) { // fallback on en_US
			$this->locale = "en_US";
			$this->lcdata = json_decode(file_get_contents(dirname(__FILE__) . "/../locale/en_US.json")));
		} else { // fallback on nothing
			$this->locale = null;
			$this->lcdata = array();
		}
	}
	
	/**
	 * Get the current locale.
	 *
	 * @return The current locale.
	 */
	public function locale()
	{
		return $this->locale;
	}
	
	/**
	 * Get the text from the locale data.
	 *
	 * @param $text Text to look up.
	 * @return Translated text.
	 */
	public function get($text)
	{
		if(in_array($text, $this->lcdata))
		{
			return $this->lcdata($text);
		}
		return $text;
	}
	
	/**
	 * Alias function for get($text).
	 */
	public function _($text)
	{
		return get($text);
	}
	
	/**
	 * Part of arrayaccess, see www.php.net/arrayaccess.
	 */
	public function offsetSet($offset, $value) {
		trigger_error("I18NMan does not support setting of language strings.", E_USER_ERROR);
	}
    
	/**
	 * Part of arrayaccess, see www.php.net/arrayaccess.
	 */    
	public function offsetExists($offset) {
		return isset($this->lcdata[$offset]);
	}

	/**
	 * Part of arrayaccess, see www.php.net/arrayaccess.
	 */
	public function offsetUnset($offset) {
		trigger_error("I18NMan does not support unsetting of language strings.", E_USER_ERROR);
	}
	
	/**
	 * Part of arrayaccess, see www.php.net/arrayaccess.
	 */
	public function offsetGet($offset)
	{
		if (isset($this->lcdata[$offset]))
			return $this->lcdata[$offset];
		else
			return null;
	}

}
