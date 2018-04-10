<?php

namespace Catalyst\Page\Header;

use Catalyst\Controller;

/**
 * Used to hold data about what scripts and other resources should belong in the <head> element
 */
class Header {
	/**
	 * JS libraries n such
	 */
	protected const SCRIPTS = [
		// jQuery may be able to drop once materialize does
		["https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js", 'crossorigin="anonymous"'],
		// migrate to 1.0.0 alpha?
		["https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.js", 'crossorigin="anonymous"'],
		// best i've found so far, works well
		["https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.6/draggable.bundle.legacy.js", 'crossorigin="anonymous"'],
		// not vital
		["https://cdn.rawgit.com/namuol/cheet.js/master/cheet.min.js", "defer", 'crossorigin="anonymous"'],
		// crossorigin doesn't like to behave here
		["https://google.com/recaptcha/api.js", "defer"],
		
		[ROOTDIR."js/modules/error_handler.js?{commit}"],
		
		[ROOTDIR."js/modules/markdown_parser.js?{commit}", "defer"],
		[ROOTDIR."js/modules/ajax_progress.js?{commit}", "defer"],
		[ROOTDIR."js/modules/console_message.js?{commit}", "defer"],
		[ROOTDIR."js/modules/color_functions.js?{commit}", "defer"],
		[ROOTDIR."js/modules/input_functions.js?{commit}", "defer"],
		[ROOTDIR."js/modules/polyfills.js?{commit}", "defer"],
		[ROOTDIR."js/modules/totp_preview.js?{commit}", "defer"],
		[ROOTDIR."js/modules/onload.js?{commit}"],
	];

	/**
	 * Get the scripts and attributes
	 * @return string[][]
	 */
	public static function getScripts() : array {
		$scripts = self::SCRIPTS;
		foreach ($scripts as &$script) {
			$script[0] = str_replace("{commit}", Controller::getCommit(), $script[0]);
		}
		return $scripts;
	}

	/**
	 * CSS-es
	 */
	protected const STYLES = [
		// materialize main
		"https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.css",
		// icon set
		"https://fonts.googleapis.com/icon?family=Material+Icons",
		// roboto
		"https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i",
		// overall styles and such, mostly just small things
		ROOTDIR."css/overall.css?{commit}",
		// used for styling, takes ?hex=###### as a URL param
		ROOTDIR."css/color-".PAGE_COLOR.".css?{commit}",
	];

	/**
	 * Get the styles
	 * @return string[]
	 */
	public static function getStyles() : array {
		$styles = self::STYLES;
		foreach ($styles as &$style) {
			$style = str_replace("{commit}", Controller::getCommit(), $style);
		}
		return $styles;
	}
}
