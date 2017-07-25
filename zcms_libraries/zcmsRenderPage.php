<?php
/* ****************************************************************************
 ZCMS 0.2
 2017.01.08 
 Greg Zapf
 
 Page Renderer

 Called to build, and render each page to the root directory.  Different enough 
 from zcmsGetContent to justify separation, but similar concept...

**************************************************************************** */

function remove_utf8_bom($text)
{
    $bom = pack('H*','EFBBBF');
    $text = preg_replace("/^$bom/", '', $text);
    return $text;
}

function zcmsRenderPage($title, $body, $meta="", $menu="", $style ="", $footer = "", $template="pageTemplate") {
	//$title = str_replace("&", "&amp;", html_entity_decode($title));

  // Does template exist?
	$templatefile = "zcms_renderers/" . $template . ".php";
	if (file_exists($templatefile)) {
		$rendering = file_get_contents($templatefile);
		// Replace markers in rendering template with values and return.
		$inputs = array($title, $body, $meta, $menu, $style, $footer);
		$markers = array('$Title', '$Body', '$Meta', '$Menu', '$Style', '$Footer');
		$rendering = remove_utf8_bom(str_replace($markers, $inputs, $rendering));

		ob_start();
    eval('?> ' . $rendering . '<?php ');
		$fullrendering = ob_get_clean();

		return $fullrendering;

	} else {
		return "Template " . $template . " not found.";
	}
}

?>
