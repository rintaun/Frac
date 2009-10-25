<?php
/*
 * Frac
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

define('SHORTEN_SHORTER', 1);
define('SHORTEN_LONGER',  2);
define('SHORTEN_EXACT',   3);

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

	public function shorten($text, $len, $post="...", $mode=SHORTEN_SHORTER)
	{
		if (!is_numeric($len)) $len = 10;
//		if ($post == null) $post = "...";
//		if ($mode == null) $mode = SHORTEN_SHORTER;

		if (strlen($text) < $len) return $text;

		switch ($mode)
		{
			case SHORTEN_SHORTER:
				$end = strrpos(substr($text,0,$len), " ");
				return substr($text,0,$end) . $post;
			case SHORTEN_LONGER:
				$end = strpos($text, " ", $len);
				return substr($text, 0, $end) . $post;
			case SHORTEN_EXACT:
				return substr($text,0,$len) . $post;
		}
	}
}
?>
