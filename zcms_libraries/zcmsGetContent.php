<?php
/* ****************************************************************************
 ZCMS 0.2
 2016.12.09 
 Greg Zapf
 
 Content Processor
 
 Obtain a rendered content block, per that block's refresh rules.  If unable 
 to acquire a fresh rendering, fall back to cached version.

**************************************************************************** */

function zcmsBuildCacheString($head, $body, $fresh) {
	$head = str_replace("&", "&amp;", html_entity_decode($head));
	$body = str_replace("&", "&amp;", html_entity_decode($body));
	
	$cachescratch = "﻿<!DOCTYPE html>\n";
	$cachescratch .= "<zcmscontentcache>\n";
	$cachescratch .= "<freshness>" . $fresh . "</freshness>\n";
	$cachescratch .= "<header>" . $head . "</header>\n";
	$cachescratch .= "<body>" . $body . "</body>\n";
	$cachescratch .= "</zcmscontentcache>\n";
	return $cachescratch;
}

function zcmsGetContent($contentID, $renderer='article', $titleOverride='') {
	$contentxmlfilename = "zcms_content/".$contentID . ".php";
	$contentxml=simplexml_load_file($contentxmlfilename) or die("Error: Cannot find content ". $contentID);
	
	if ($contentxml->type == "static") {
		$article_content['header'] = zcmsGetLogo('1.25em',1);
		$article_content['header'] .= $contentxml->header->span->asXML();
		$article_content['body'] = $contentxml->body->div->asXML();
		$freshness = "static";
	} elseif ($contentxml->type == "dynamic") {
		// Check and initialize cache.
		$writefilename = "zcms_contentcache/" . $contentID . '.xml';
		$frequency = $contentxml->refresh * 60;
		if (file_exists($writefilename)) {
			$cachestring = file_get_contents($writefilename);
			$cachexml = simplexml_load_string($cachestring);
			$freshness = $cachexml->freshness;
			if (filemtime($contentxmlfilename) > filemtime($writefilename)) {
				$refreshdue = 1;
			} elseif ($freshness == 'good') {
				$refreshdue = time() - filemtime($writefilename) >= $frequency;
			} else {
				$refreshdue = 1;
			}
			
		} else {
			// Initialize the cache with defaults.
			$freshness = 'never';
			$cachestring = zcmsBuildCacheString('<span>' . zcmsGetLogo('1.25em',1) . $contentID . '</span>', '<div>Cannot initialize.</div>', $freshness);
			$cachexml = simplexml_load_string($cachestring);
			$refreshdue = 1;
		}
		
		// If due for a refresh, try to build new content.
		if($refreshdue) {
			try {
				// Replace with better paradigm to get rid of extraneous DIV, etc. and corresponding catch /div below.
				eval('?>' . $contentxml->body->div->asXML() . '<?php ');	
				$freshness = 'good';
			} catch (Exception $e) {
				// Refresh failed, so use the cache and adjust freshness.
				$article_content['header'] = $cachexml->header->span->asXML();
				$article_content['body'] = $cachexml->body->div->asXML();
				switch ($cachexml->freshness) {
					case 'good':
						$freshness = 'missed';
						break;
					case 'missed':
						$freshness = 'stale';
						break;
					default:
						$freshness = $cachexml->freshness;
				}
				$cachexml->freshness = $freshness;
				// close the opening div... replace this when better solution comes to mind.
				echo "</div>";
			}
		} else {
	  	// Retain the cache.
	  	$article_content['header'] = $cachexml->header->span->asXML();
	    $article_content['body'] = $cachexml->body->div->asXML();
		$freshness = $cachexml->freshness;
		}
		
		// Write the cache.
		$cachestring = zcmsBuildCacheString($article_content['header'], $article_content['body'], $freshness);
		//$cachexml = simplexml_load_string($cachestring);
		//$cachexml->asXML($writefilename);
		file_put_contents($writefilename, $cachestring);
	
	}
	
	//zcmsRenderArticle($article_content['header'], $article_content['body'], $freshness);
	$result = zcmsGetRendering($article_content['header'], $article_content['body'], $renderer, $freshness);
	echo $result;
}	
?>
