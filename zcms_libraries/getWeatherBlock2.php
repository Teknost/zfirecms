<?php

// Grab the weather
function getWeatherBlock2($location='TN/Memphis', $days=3) {

		$wu_api_key = 'YOUR_KEY_HERE';
		$wu_ref_key = '7eca00ae6a4ab627';
		
		// Gather info
		$feed_url = "http://TESTapi.wunderground.com/api/" . $wu_api_key . "/conditions/forecast/alerts/q/" . $location . ".xml";
		$feed_favicon = getFavIcon($feed_url);
		$xml = new SimpleXMLElement($feed_url, LIBXML_NOCDATA, TRUE);
		$name = $xml->current_observation->display_location->city . " Weather";
		$forecast = $xml->forecast->simpleforecast->forecastdays;
		$days = min($forecast->forecastday->count(), $days);
		for ($i=0; $i < $days; $i++) {
			$day[$i] = $forecast->forecastday[$i]->date->weekday_short;
			//$temps[$i] = $forecast->forecastday[$i]->high->fahrenheit . "&deg;F &nbsp;/&nbsp; " . $forecast->forecastday[$i]->low->fahrenheit . "&deg;F";
			$highs[$i] = $forecast->forecastday[$i]->high->fahrenheit;
			$lows[$i] = $forecast->forecastday[$i]->low->fahrenheit;
			$icons[$i] = $forecast->forecastday[$i]->icon->__toString();
			$icon_titles[$i] = $forecast->forecastday[$i]->conditions;
		}
		
		
				
		// Render as Array
		$content['header'] = "<span>";
		$content['header'] .= "<a style='text-decoration:none;color:#BBBBBB;' href='http://www.wunderground.com/weather-forecast/" . $location . "?apiref=" . $wu_ref_key . "'>";
		$content['header'] .= "<img class='icon' style='vertical-align:text-bottom;padding:0 4px 0 0;' src='". $feed_favicon . "' />" . $name . "</a>";
		$content['header'] .= "</span>";
		$content['body'] = "<div class='weather'>";
		$content['body'] .= "<a style='display:inline-block;text-decoration:none;color:#BBBBBB;' href='http://www.wunderground.com/weather-forecast/" . $location . "?apiref=" . $wu_ref_key . "'>";
		
		if ($xml->alerts) {
			foreach ($xml->alerts->alert as $a) {
				$content['body'] .= "<p title='" . trim($a->message->__toString()) . "'>" . $a->description . "</p>\n";
			}
		}
		$content['body'] .= "\t\t\t\t<span class='current' title='" . $xml->current_observation->weather . "'>\n";
		$content['body'] .= zcmsGetLogo('1.7em',1,$xml->current_observation->icon->__toString());
		$content['body'] .= "\t\t\t\t" . $xml->current_observation->temp_f . "&deg;F\n";
		$content['body'] .= "</span>\n";
		$content['body'] .= "\t\t\t\t<table><tbody>\n";
		for ($i=0; $i < $days; $i++) {
				$content['body'] .= "\t\t\t\t" . '<tr title="' . $icon_titles[$i] . '"><td>' . $day[$i] . ':</td><td>';
				$content['body'] .= zcmsGetLogo('1.25em',1,$icons[$i]);
				$content['body'] .= '</td><td>';
				$content['body'] .= ($highs[$i] > 32) ? $highs[$i] . '&deg;F' : "<span style='color:#8888FF;'>" . $highs[$i] . '&deg;F</span>';
				$content['body'] .= '&nbsp;/&nbsp;';
				$content['body'] .= ($lows[$i] > 32) ? $lows[$i] . '&deg;F' : "<span style='color:#8888FF;'>" . $lows[$i] . '&deg;F</span>';
				//echo $temps[$i];
				$content['body'] .= '</td></tr>' . "\n";
		}
		$content['body'] .= "\t\t\t\t</tbody></table>\n";
		$content['body'] .=  '</a></div>';
		
		return $content;
}

?>
