<?php

// This returns the icon for a website.  Caching is permanent, so clear zcms_contentcache *.zcms_ico to refresh.

	// DuckDuckGo Service
	//$engineString = 'http://icons.duckduckgo.com/ip2/' . $domain . '.ico';
	// Google Service (blocked?)
	//$engineString = 'http://www.google.com/s2/favicons?domain=' . $domain;
	// Better Idea Service
	//$engineString = 'https://icons.better-idea.org/icon?url=' . $top_domain . '&size=16..32..128';
	// No service (immature, only goes for /favicon.ico)
	//$engineString = 'http://' . $domain . '/favicon.ico';
  
function getFavIcon($url, $engine='better') {
	$domain = topDomainFromURL($url);
	
	// Check cache first.
	$cacheAlready = 0;
	$cacheFileName = "zcms_contentcache/" . $domain . '.zcms_ico';
	if (file_exists($cacheFileName)) {
			$iconResult = file_get_contents($cacheFileName);
			$cacheAlready = 1;
	} else {
		switch ($engine) {
			case 'duck':
				$engineString = 'https://icons.duckduckgo.com/ip2/' . $domain . '.ico';
				break;
			case 'google':
				$engineString = 'https://www.google.com/s2/favicons?domain=' . $domain;
				break;
			case 'better':
				$engineString = 'https://icons.better-idea.org/icon?url=' . $domain . '&size=16..32..128';
				break;
			case 'favicon':
				$engineString = 'https://' . $domain . '/favicon.ico';
				break;
			default:
				$engineString = 'https://' . $domain . '/favicon.ico';
				break;
		}
		$urlinit = 'https://' . topDomainFromURL($engineString);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_REFERER, $urlinit);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:51.0) Gecko/20100101 Firefox/51.0');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,10);
		curl_setopt($ch, CURLOPT_URL, $engineString);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		$output = curl_exec($ch);
		$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		curl_close($ch);
		
		$iconResult = 'data:' . $contentType . ';base64,' . base64_encode($output);
		
		// Failover to local rss.ico
		if (strlen($output) < 10) {
				$iconResult = 'data:image/x-icon;base64,' . base64_encode(file_get_contents('zcms_icons/rss.ico'));
		} elseif ($cacheAlready == 0){
			// Write cache file.
			file_put_contents($cacheFileName, $iconResult);
		}
	}
		
  return $iconResult;
}

?>
