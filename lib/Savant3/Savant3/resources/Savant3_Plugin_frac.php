<?php
/**
 * Fuck you AND your shitty docs.
 * <3 rintaun
 */

define('SHORTEN_SHORTER', 1);
define('SHORTEN_LONGER', 2);
define('SHORTEN_EXACT', 3);

class Savant3_Plugin_frac extends Savant3_Plugin
{
	public function frac()
	{
		// DO NOTHING LOL!
				echo "WTF";
	}
	public function shorten($text, $len, $post=null, $mode=null)
	{
		if (is_array($text)) $text = implode(" ",$text);
		if (!is_numeric($len)) $len = 100;
		if ($post == null) $post = "...";
		if ($mode == null) $mode = SHORTEN_SHORTER;
		
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
