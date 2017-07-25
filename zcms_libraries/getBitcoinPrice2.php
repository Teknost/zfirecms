<?php

function getBitcoinPriceBlock2() {
// Gather Price Info
$url = 'https://coinbase.com/api/v1/prices/spot_rate?currency=USD';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
//if (curl_errno($ch) || !$response) {
if (curl_errno($ch)) {
	throw new Exception('Could not retrieve Bitcoin Price.');
}
curl_close($ch);
$price = round(json_decode($response)->amount);



// Render as Array
$content['header'] = "<span>";
$content['header'] .= zcmsGetLogo('1.25em',1,'bitcoin');
$content['header'] .= "<span>Bitcoin: </span>";
$content['header'] .= "<span style='color: #55FF55; font-weight: bold;'>$" . $price . "</span></span>";

$content['body'] = "";

return $content;

}
?>
