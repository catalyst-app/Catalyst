<?php

namespace Catalyst\Page\Header;

/**
 * Used to hold data about what scripts and other resources should belong in the <head> element
 */
class Header {
	/**
	 * JS libraries n such
	 */
	public const SCRIPTS = [
		// jQuery may be able to drop once materialize does
		["https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js", 'crossorigin="anonymous"'],
		// migrate to 1.0.0 alpha?
		["https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js", 'crossorigin="anonymous"'],
		// best i've found so far, works well
		["https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.6/draggable.bundle.min.js", 'crossorigin="anonymous"'],
		// not vital
		["https://cdn.rawgit.com/namuol/cheet.js/master/cheet.min.js", "defer", 'crossorigin="anonymous"'],
		// crossorigin doesn't like to behave here
		["https://www.google.com/recaptcha/api.js", "defer"],
		
		[ROOTDIR."js/modules/error_handler.js"],
		
		[ROOTDIR."js/modules/markdown_parser.js", "defer"],
		[ROOTDIR."js/modules/ajax_progress.js", "defer"],
		[ROOTDIR."js/modules/console_message.js", "defer"],
		[ROOTDIR."js/modules/color_functions.js", "defer"],
		[ROOTDIR."js/modules/input_functions.js", "defer"],
		[ROOTDIR."js/modules/polyfills.js", "defer"],
		[ROOTDIR."js/modules/totp_preview.js", "defer"],
		[ROOTDIR."js/modules/onload.js"],
	];

	/**
	 * CSS-es
	 */
	public const STYLES = [
		// materialize main
		"https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.css",
		// icon set
		"https://fonts.googleapis.com/icon?family=Material+Icons",
		// roboto
		"https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i",
		// overall styles and such, mostly just small things
		ROOTDIR."css/overall.css",
		// used for styling, takes ?hex=###### as a URL param
		ROOTDIR."css/color-".PAGE_COLOR.".css",
	];
}
