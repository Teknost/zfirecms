<?php 
/* ****************************************************************************
 ZCMS 0.2
 2016.12.09 
 Greg Zapf
 
 SVG Icon builder
 
 This prepares an SVG file from /zcms_icons/ for inline rendering.

**************************************************************************** */

function zcmsGetLogo($width = '100%', $opacity = 0.25, $variation = 'sitelogo') {
	$svgcontent=simplexml_load_file('zcms_icons/' . $variation . '.svg') or die("Error: Cannot load SVG: " . $variation);
	// Modify the content of the svg... attributes and shit	
	$viewbox = $svgcontent->attributes()->viewBox;
	if ($viewbox=='') {
		$viewbox = $svgcontent->attributes()->viewbox;
	}
	
	$logo = "<svg viewbox='" . $viewbox . "' ";
	$logo .= "width='" . $width . "' ";
	$logo .= "height='" . $width . "' ";
	$logo .= "class='icon' style='width:".$width.";height:".$width.";opacity:". $opacity . ";position:relative;z-index:200;left:0px;top:0px;pointer-events:none;vertical-align:text-bottom;padding:0 4px 0 0;'>\n";
	$logo .= $svgcontent->children('http://www.w3.org/2000/svg')->asXML() . "\n";
	$logo .= "</svg>";
	
	return $logo;

}

?>
