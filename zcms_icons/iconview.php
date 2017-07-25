<?php 
/* ****************************************************************************
 ZCMS 0.2
 2016.12.09 
 Greg Zapf
 
 SVG Icon Catalog Renderer
 
 This administrative helper recurses through all subdirectories and displays 
 SVG icons located therein.  User-access should be prohibited.

**************************************************************************** */
?>

<html>

<head>
<title>Icons Viewer</title>
<style>

path, g path {
	stroke: #333333;
}

path.sun, g path.sun {
	fill: #DDCC22;
}

path.cloud {
	fill: #BBBBBB;
}

path.water {
	fill: #8888FF;
}

span {
	width: 48px;
	height: 48px;
}

</style>

</head>

<body style="background: #222222; color: #DDDDDD;">

<div class="icon-set">
<?php 

function renderLogo($filename, $width = '100%', $opacity = 0.85) {
	$svgcontent=simplexml_load_file($filename) or die("Error: Cannot load SVG: " . $filename);
	// Modify the content of the svg... attributes, etc.
	$viewbox = $svgcontent->attributes()->viewBox;
	if ($viewbox=='') {
		$viewbox = $svgcontent->attributes()->viewbox;
	}
	echo "<svg viewbox='" . $viewbox . "' ";
	echo "width='" . $width . "' ";
	echo "height='" . $width . "' ";
	echo "style='width:".$width.";height:".$width.";opacity:". $opacity . ";position:relative;z-index:200;left:0px;top:0px;pointer-events:none;vertical-align:text-bottom;padding:0 4px 0 0;'>\n";
	echo $svgcontent->children('http://www.w3.org/2000/svg')->asXML() . "\n";
	echo "</svg>";
}


function processDir($path) {
	// Render all the SVGs.
	echo "<div><h2>" . $path . "</h2>";
	foreach (glob($path . '/*.svg') as $svgfilename) {
		echo "<span title='" . basename($svgfilename) . "'>";
		//echo "<h3>" . $svgfilename . "</h3>";
		renderLogo($svgfilename,'48px',1);
		echo "</span>"; 
	}
	echo "</div><hr />";
	// Do the same for child directories.
	foreach (glob($path . '/*' , GLOB_ONLYDIR) as $currentdir) {
		processDir($currentdir);
	}
}
	
// Obtain and render all local directory SVGs.

processDir(__DIR__);

?>

</div>

</body>
</html>
