<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

define('SHORTEN_SHORTER', 1); //!< Shortens a string to $len or less
define('SHORTEN_LONGER',  2); //!< Shortens a string to the nearest word ending after $len
define('SHORTEN_EXACT',   3); //!< Shortens a string to exactly $len

class Savant3_Plugin_frac extends Savant3_Plugin
{
	/**
	 * Call Utils::idtouser($id).
	 *
	 * @param $id User ID.
	 * @return The user name.
	 */
	public function idtouser($id)
	{
		return Utils::idtouser($id);
	}
	
	/**
	 * Call Utils::createuri($path).
	 *
	 * @param $path Path, usually controller/action/arguments, or even an array.
	 * @return The user name.
	 */
	public function createuri($path)
	{
		return Utils::createuri($path);
	}

	/**
	 * Shortens a string according to the given parameters.
	 *
	 * @param $text The text to shorten
	 * @param $len The maximal length.
	 * @param $break The word delimeter when using SHORTEN_LONGER mode.
	 * @param $post A string to append after the cut.
	 * @param $mode The shortening mode to apply.
	 */
	public function shorten($text, $len, $break = " ", $post = "...", $mode=SHORTEN_SHORTER)
	{
		if (strlen($text) < $len) return $text;

		switch ($mode)
		{
			case SHORTEN_SHORTER:
				$end = strrpos(substr($text,0,$len), $break);
				return substr($text,0,$end) . $post;
			case SHORTEN_LONGER:
				$end = strpos($text, $break, $len);
				return substr($text, 0, $end) . $post;
			case SHORTEN_EXACT:
				return substr($text,0,$len) . $post;
		}
	}
}
?>
