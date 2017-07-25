<?php
/* ****************************************************************************
 ZCMS 0.2
 2017.01.08 
 Greg Zapf
 
 Primary Page Generator
 
 This component builds global data and triggers ZCMS page rendering.

**************************************************************************** */

// Toggle the top one to display errors inline for troubleshooting.
ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
// Limit the log size.
ini_set('log_errors_max_len', '20971520');

// Obtain and inject all library functions.
foreach (glob("zcms_libraries/*.php") as $phpfilename) {
	require_once $phpfilename;
}

// Memu is global, build here.
$menuhtml = "\t\t<ul>\n";
foreach (glob("zcms_pagesmain/*.php") as $menufilename) {
  //Write a link with menutitle and url.
	$menuxml=simplexml_load_file($menufilename) or die("Error: Cannot create object");
	$menuhtml .= "\t\t\t<li><a href='" . $menuxml->url . ".html'>" . $menuxml->menutitle . "</a></li>\n";
}
$menuhtml .= "\t\t</ul>\n";
			
// Style is global, build here.
$stylecode = "";
// Obtain and inject all CSS infrastructure.  Browser calls are slower than larger pages.
foreach (glob("zcms_infrastructure/*.css") as $cssfilename)	{
	$stylecode .= file_get_contents($cssfilename);
	$stylecode .= "\n";
}

// Footer is global, build here.
$footerhtml = "";
	// Obtain and inject all universal footers.
	foreach (glob("zcms_infrastructure/footer*.*") as $footerfilename)
	{
		$footerhtml .= file_get_contents($footerfilename);
		$footerhtml .= "\n";
	}


// Do a top level foreach loop and render the html inside of it... with ob_start etc...
// This is the main page renderer.  It may be replaced with one more similar to the content renderer.
// Current use of simplexml library means all content should be XHTML.  This may be replaced in the future for felxibility.
foreach (glob("zcms_pages*/*.php") as $pagefilename) {
	$pagexml=simplexml_load_file($pagefilename) or die("Error: Cannot read " . $pagefilename);
	
	// If due for a refresh, build the page.  (or build all when called direct... currently builds all with separate generator)
	$writefilename = $pagexml->url . '.html';
	$title = $pagexml->pagetitle;
	$body = $pagexml->section->asXML();
	$meta = $pagexml->metadescription;
	$oldtime = (file_exists($writefilename)) ? filemtime($writefilename) : 0;
 	$frequency = $pagexml->refresh * 60;
	$elapsed = time() - $oldtime;
	if($elapsed >= $frequency || $_GET['force'] == 1) {
		file_put_contents($writefilename, "\xEF\xBB\xBF". zcmsRenderPage($title, $body, $meta, $menuhtml, $stylecode, $footerhtml));
//		ob_start();
//		require "template.php";
//		file_put_contents($pagexml->url . '.html', ob_get_clean());
		echo 'Rendered page ' . $pagexml->menutitle . '<br />';
	} else {
		echo 'Skipped page ' . $pagexml->menutitle . '. Waiting ' . round(($frequency - $elapsed) / 60, 1) . ' more minutes.<br />';
	}
}

echo 'Boom.  Rendering complete. <a href="index.html">Go Back</a>';
file_put_contents('zcms_updated_time.txt', time());

?>
