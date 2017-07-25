<?php

// This generates a content block of items from an RSS or Atom feed.

function getFeedBlock2($feed_url, $name='', $max=5) {
		// Gather feed info
		$feed_favicon = getFavIcon($feed_url);

		$xml = new SimpleXMLElement($feed_url, LIBXML_NOCDATA, TRUE);
		
		if(isset($xml->channel)) { //RSS Style
			$name = ($name=='') ? $xml->channel->title : $name;
			$max = min($xml->channel->item->count(), $max);
			for ($i=0; $i < $max; $i++) {
				$details[$i] = truncateString($xml->channel->item[$i]->description);
				$href[$i] = $xml->channel->item[$i]->link;
				$title[$i] = $xml->channel->item[$i]->title;
			}
		} else { // Atom Style
			$name = ($name=='') ? $xml->title : $name;
			$max = min($xml->entry->count(), $max);
			for ($i=0; $i < $max; $i++) {
				if($xml->entry[$i]->summary) {
					$details[$i] = truncateString($xml->entry[$i]->summary);
				} else {
					$details[$i] = truncateString($xml->entry[$i]->content);
				}	
				$href[$i] = $xml->entry[$i]->link->attributes()->href;
				$title[$i] = $xml->entry[$i]->title;
			}
		}

		
		// Render as Array
		$content['header'] = "<span>";
		$content['header'] .= "<img class='icon' style='vertical-align:text-bottom;padding:0 4px 0 0;' src='". $feed_favicon . "' />" . $name;
		$content['header'] .= "</span>";
		$content['body'] = "<div><ul>";
		for ($i=0; $i < $max; $i++) {
				$content['body'] .=  '<li title="' . $details[$i] . '">';
				$content['body'] .=  '<a href="' . $href[$i] . '">' . $title[$i] . '</a></li>';
		}
		$content['body'] .=  '</ul></div>';
		
		return $content;
}

?>
