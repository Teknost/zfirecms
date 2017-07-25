<?php
/* ****************************************************************************
 ZfireCMS 0.2
 2016.01.14 
 Greg Zapf
 
 Main Page Template
 
 This is called by zcmsRenderPage to render each page.  The following variable 
 placeholders with $ prefixes should appear in the page below:
 Title, Body, Menu, Style, Meta, Footer

 Some PHP code is allowed, but the general concept is to use this as an HTML 
 template.

**************************************************************************** */
?>
<!DOCTYPE html>
<html>
<head>
<title>Zfire CMS - $Title</title>

<style>
	$Style
</style>

<meta name="description" content="$Meta">

<script>
function refreshContent() {
	// Dirty little AJAX call to kick the psuedo-cron process and then schedule reload of this page.
	var xmlhttp;
	xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if (xmlhttp.status == 200) {
				if (isNaN(xmlhttp.responseText)) {
				setTimeout('refreshContent();', 300000);
				} else {
					var cooldowntime = Number(xmlhttp.responseText);
					if (cooldowntime == 0) {
						window.location.reload(true);
					} else {
						setTimeout('refreshContent();', cooldowntime + 5000);
					}				
				}
			} else {
			setTimeout('refreshContent();', 30000);
			}
		}
	};
	xmlhttp.open('GET', 'zcms_regen.php', true);
	xmlhttp.send();
}

window.onload = setTimeout('refreshContent();',60000);
</script>

</head>

<body>

<header>
<div style="width:75px;height:75px;padding:0;margin:0.5em;position:relative;display: inline-block;float:left;">
<a href="/" style="width:75px;height:75px;display:inline-block;">
<?php renderLogo('100%', .95); ?>
</a>
</div>

	<nav>
		$Menu
	</nav>
	<H2>$Title</H2>

		<p style="width:99%;padding-top:0;">Zfire CMS: A new way to deliver dynamic content securely and with great speed.
	
		<span style="float:right;color:#AAAAAA;margin-right: 0.5em;">
		<time>
			<?php
			date_default_timezone_set('America/Chicago');
			echo 'Generated: ' . date('D, M d Y g:i A') ."\n";
			?>
		</time>
		</span>
	</p>
	
</header>
<main>

		$Body
</main>

<footer>
$Footer
</footer>

</body>
</html>
