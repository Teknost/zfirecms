<?php
/* ****************************************************************************
 ZCMS 0.2
 2016.12.09 
 Greg Zapf
 
 Content Renderer
 
 This will render a piece of content for further inclusion upstream.  It 
 relies on the simple HTML renderer files in /zcms/renderers/.  Including the 
 variable names at appropriate points in those renderers is optional for each 
 variable.  This function can be extended to provide more variables as needed.

**************************************************************************** */

function zcmsGetRendering($header, $body, $template='article', $freshness='static', $datestamp='Today', $author='Anonymous', $breadcrumb='<a href="/">Home</a>', $tags='') {
	
	// Does template exist?
	$templatefile = "zcms_renderers/" . $template . ".html";
	if (file_exists($templatefile)) {
		$rendering = file_get_contents($templatefile);
		
		// Clean/enhance inputs as needed.
		switch ($freshness) {
			case 'static':
				$freshnesstitle = "Static";
				break;
			case 'good':
				$freshnesstitle = "Fresh";
				break;
			case 'missed':
				$freshnesstitle = "Outdated";
				break;
			case 'stale':
				$freshnesstitle = "Stale";
				break;
			default:
				$freshnesstitle = "Broken";
		}
		$freshness = "<span style='float:right;' title='" . $freshnesstitle . "'>" . zcmsGetLogo('1.25em',0.35,'timely_'.$freshness) . "</span>";
		
		
		// Replace markers in rendering template with values and return.
		$inputs = array($header, $body, $template, $freshness, $datestamp, $author, $breadcrumb, $tags);
		$markers = array('$header', '$body', '$template', '$freshness', '$datestamp', '$author', '$breadcrumb', '$tags');
		$rendering = str_replace($markers, $inputs, $rendering);		
		return $rendering;
	} else {
		return "Template " . $template . " not found.";
	}
}

?>
