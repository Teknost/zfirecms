<?php 

function renderLogo($width = '100%', $opacity = 0.25, $variation = 'sitelogo') {
	$svgcontent=simplexml_load_file('zcms_icons/' . $variation . '.svg') or die("Error: Cannot load SVG: " . $variation);
	// Modify the content of the svg... attributes and shit	
	$viewbox = $svgcontent->attributes()->viewBox;
	if ($viewbox=='') {
		$viewbox = $svgcontent->attributes()->viewbox;
	}
	echo "<svg viewbox='" . $viewbox . "' ";
	echo "width='" . $width . "' ";
	echo "height='" . $width . "' ";
	echo "class='icon' style='width:".$width.";height:".$width.";opacity:". $opacity . ";position:relative;z-index:200;left:0px;top:0px;pointer-events:none;vertical-align:text-bottom;padding:0 4px 0 0;'>\n";
	echo $svgcontent->children('http://www.w3.org/2000/svg')->asXML() . "\n";
	echo "</svg>";
}


?>
