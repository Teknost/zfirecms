<?php
/* ****************************************************************************
 ZCMS 0.2
 2017.06.15 
 Greg Zapf
 
 Psuedo-cron
 
 This component is called by a simple AJAX function to evaluate readiness for 
 page generation by ZCMS.  By checking readiness via AJAX, the user's browser 
 will only automatically refresh when it knows freshly built pages are ready.

**************************************************************************** */

	$frequency = 60 * 60 * 12; // seconds, so hourly = 3600
	$oldtime = file_get_contents('zcms_updated_time.txt');
	$elapsed = time() - $oldtime;
	if($elapsed >= $frequency) {
		
		ob_start();
		require "gen4.php";
		ob_end_clean();		
		echo 0;
	} else {
		echo ($frequency - $elapsed) * 1000;
	}
?>
