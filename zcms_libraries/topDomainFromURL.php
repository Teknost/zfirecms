<?php
// Per Wikipedia: "All ASCII ccTLD identifiers are two letters long, 
// and all two-letter top-level domains are ccTLDs."

function topDomainFromURL($url) {
	$url_parts = parse_url($url);
	$domain_parts = explode('.', $url_parts['host']);
	if (strlen(end($domain_parts)) == 2 ) {
		$top_domain_parts = array_slice($domain_parts, -3);
	} else {
		$top_domain_parts = array_slice($domain_parts, -2);
	}
	$top_domain = implode('.', $top_domain_parts);
	return $top_domain;
}

?>