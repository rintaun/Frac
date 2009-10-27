<?php

// gets anime information and data via http://cal.syoboi.jp/
class AnimeData
{
	public function __construct()
	{
	}

	public static function httpget($url)
	{
		if (parse_url($url) === false) return false;
		if (ini_get("allow_url_fopen") == 1) 		// if allow_url_fopen is on, then just get the contents and return them.
			return file_get_contents($url);
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
			$search = array($matches[1],$matches[2]);
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

		// this title should be in japanese, so lets look pull up the article on japanese wikipedia
		$result = self::httpget("http://ja.wikipedia.org/wiki/".urlencode($title));
		if ($result === false) return false;
		
		// now pull the ENGLISH wikipedia link out of the page
		if (!preg_match("/interwiki-en\"><a href=\"(.*)\">/", $result, $matches))
			return false;

		// now pull up the english wikipedia article...
		$result = self::httpget($matches[1]);
		if ($result === false)
			return false;

		// and pull the ANN link out of it. XD
		if (!preg_match("/<a href=\"(.*animenewsnetwork.*encyclopedia.*)\" class/", $result, $matches))
			return false;

		// and finally, pull up animenewsnetwork to get the description in english. WAY TOO COMPLICATED? LOLOLOL
		$result = self::httpget($matches[1]);
		if ($result === false)
			return false;

		//ok now pull out the description... lol.....
		if (!preg_match("/Plot Summary:<\/STRONG>\s+<SPAN>(.*)<\/SPAN>/", $result, $matches))
			return false;

		return $matches[1];
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
			$times[$node->getElementsByTagName('Count')->item(0)->nodeValue][] = array(
				'channel' => $node->getElementsByTagName('ChID')->item(0)->nodeValue,
				'airtime' => $node->getElementsByTagName('StTime')->item(0)->nodeValue
			);			
		}
		return $times;
	}
}

if (($result = AnimeData::description(1718)) !== false)
	echo $result;
else	echo "false";

?>
