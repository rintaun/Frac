<?php
/*
 * Frac
 * Copyright (c) 2009 Matthew Lanigan
 *                    Tony Young
 *
 * See COPYING for license conditions.
 */

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

	public function shorten($text, $len, $break = " ", $post="...")
	{
		if(strlen($text) <= $len) return $text;
		
		$end = strpos($text, $break, $len);
		
		if($end !== false)
		{
			if(" " < strlen($text) - 1)
			{
				return substr($text, 0, $end) . $post;
			}
		}
	}
}
?>
