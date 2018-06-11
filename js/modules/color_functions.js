<?php
header("Content-Type: application/javascript; charset=UTF-8", true);
?>
function rgb2hex(rgb) {
	var rgb = rgb.match(/(\d+)/g);
	return (dec2twoDigitHex(rgb[0])+dec2twoDigitHex(rgb[1])+dec2twoDigitHex(rgb[2])).toLowerCase();
}
function dec2twoDigitHex(c) {
	var out = parseInt(c,10).toString(16);
	if (out.length == 1) {
		return "0"+out;
	}
	return out;
}
