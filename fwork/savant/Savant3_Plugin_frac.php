<?php
/*
 * Frac
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

define('SHORTEN_SHORTER', 1); // shortens a string to $len or less
define('SHORTEN_LONGER',  2); // shortens a string to the nearest word ending after $len
define('SHORTEN_EXACT',   3); // shortens a string to exactly $len

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

	public function shorten($text, $len, $break = " ", $post="...", $mode=SHORTEN_SHORTER)
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
