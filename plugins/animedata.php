<?php
/*
 * Frac
 * Copyright (c) 2009 Frac Development Team
 *
 * See COPYING for license conditions.
 */

if (!function_exists("gzdecode"))
{
	function gzdecode($data)
	{
		$g = tempnam('/tmp', 'ff');
		@file_put_contents($g, $data);
		ob_start();
		readgzfile($g);
		$d = ob_get_clean();
		return $d;
	}
}

// gets anime information and data via http://cal.syoboi.jp/
class AnimeData
{
	private function __construct()
	{
	}

	public static function httpget($url)
	{
		if (parse_url($url) === false) return false;
		if (ini_get("allow_url_fopen") == 1) 		// if allow_url_fopen is on, then just get the contents and return them.
		{
			return file_get_contents($url);
		}
		else if (function_exists("curl_exec"))		// if not, try curl...
		{
			$ch = curl_init();
			$timeout = 5; // set to zero for no timeout
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file_contents = curl_exec($ch);
			curl_close($ch);
			return $file_contents;
		}
		else return false;				// if both of those are out, i have nfi what else to try.
	}
	
	public static function search($title)
	{
		$title = urlencode($title);
		$result = self::httpget("http://cal.syoboi.jp/find?kw=".$title."&sd=1&r=0&rd=&v=0");
		if ($result === false) return false;
		
		// you know, i was going to try to do this with dom stuff,
		// but i think i can do it with regex much more easily.
		if (function_exists("preg_match_all"))
		{
			if (!preg_match_all("/<a href=\"\/tid\/(\d+)\">(.+)<\/a>/", $result, $matches))
				return false;
		}
		else return false;

		for ($i = 0; $i < count($matches[0]); $i++)
		{
			$search[] = array($matches[1][$i],$matches[2][$i]);
		}
		return $search;
	}

	public static function description($id)
	{
		if ((!is_numeric($id)) && (!is_int($id))) return false;
		$result = self::httpget("http://cal.syoboi.jp/db?Command=TitleLookup&TID=".$id);
		if ($result === false) return false;

		// ok, give in and use DOM for this...
		$doc = new DOMDocument();
		$doc->loadXML($result);

		// this is a fancy way of saying that if there was an error, fu	:)
		if ($doc->getElementsByTagName('Result')->item(0)->childNodes->item(0)->nodeValue != "200")
			return false;

		// all right, all this to get the synopsis of the series is going to seem a bit retarded, but here goes!
		// first we pull the official title out of the thing.
		$title = $doc->getElementsByTagName('Title')->item(0)->nodeValue;

		// anidb doesn't like "-" for some reason...
		$title = str_replace("-","",$title);

		// ok, now we need to look it up on anidb... =_=
		// if it redirects us to a project page, we'll get the description, but if it gives us a search result, then screw it.
		$headers = get_headers("http://anidb.net/perl-bin/animedb.pl?show=animelist&adb.search=".urlencode($title)."&do.search=search",1);
		if (!isset($headers['Location']))
			return false;

		// if it tries to redirect us, we can just get the same url and we'll get the right page
		$result = self::httpget("http://anidb.net/perl-bin/animedb.pl?show=animelist&adb.search=".urlencode($title)."&do.search=search");
		if ($result === false) return false;

		// FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF anidb LOVES gzip apparently. SO MUCH THAT I CANT MAKE IT STOP
		// IT HURTS, ANIDB, IT HURTS!
		if ($headers['Content-Encoding'][0] == "gzip")
			$result = gzdecode($result);

		// now pull the description out.
		// for some reason regular expressions weren't working, but i would have to do most of this anyway.
		$pos = strstr($result,"<div class=\"desc\">");
		$pos = substr($pos,18);
		$pos = explode("</div>",$pos);
		$pos = trim($pos[0]);
		// sometimes anidb puts crap in there that we don't care about... sort through it.
		$pos = explode("<br/>",$pos);
		$len = -1;
		$dkey = -1;
		foreach ($pos as $key => $text)
		{
			if (empty($text)) continue;
			if (strlen($text) > $len)
			{
				$len = strlen($text);
				$dkey = $key;
			}
		}

		// the longest one is probably the description ;p
		return $pos[$dkey];
	}
	
	public static function epcount($id)
	{
		$times = self::times($id);
		$curtime = time();
		$aired = 0;
		$earliest = $curtime;
		foreach ($times AS $ep => $entries)
			foreach ($entries AS $entry)
			{
				// set our default timezone, temporarily, to Tokyo.
				date_default_timezone_set("Asia/Tokyo");
				$airtime = strtotime($entry['airtime']);
				if ($airtime < $earliest) $earliest = $airtime;
				if ($airtime < $curtime) { $aired++; break; }
			}
		$total = count($times);
		return array('aired' => $aired, 'total' => $total, 'airtime' => date('D H:i:s +900', $earliest));
	}
	
	public static function times($id)
	{
		if ((!is_numeric($id)) && (!is_int($id))) return false;
		$result = self::httpget("http://cal.syoboi.jp/db?Command=ProgLookup&TID=".$id);
		if ($result === false) return false;

		// ok, give in and use DOM for this...
		$doc = new DOMDocument();
		$doc->loadXML($result);

		// this is a fancy way of saying that if there was an error, fu	:)
		if ($doc->getElementsByTagName('Result')->item(0)->childNodes->item(0)->nodeValue != "200")
			return false;

		$nodes = $doc->getElementsByTagName('ProgItem');
		for ($i = 0; $i < $nodes->length; $i++)
		{
			$node = $nodes->item($i);
			if (empty($node->getElementsByTagName('Count')->item(0)->nodeValue)) continue;
			$times[$node->getElementsByTagName('Count')->item(0)->nodeValue][] = array(
				'channel' => $node->getElementsByTagName('ChID')->item(0)->nodeValue,
				'airtime' => $node->getElementsByTagName('StTime')->item(0)->nodeValue
			);			
		}
		return $times;
	}
}
