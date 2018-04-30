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
		// use minified versions in production
		[1, "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js", 'crossorigin="anonymous"'],
		[0, "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js", 'crossorigin="anonymous"'],
		
		[1, "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.js", 'crossorigin="anonymous"'],
		[0, "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js", 'crossorigin="anonymous"'],
		
		[1, "https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.6/draggable.bundle.legacy.js", 'crossorigin="anonymous"'],
		[0, "https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.6/draggable.bundle.legacy.min.js", 'crossorigin="anonymous"'],
		
		// not vital, easter egg
		[1, "https://cdn.rawgit.com/namuol/cheet.js/master/cheet.js", "defer", 'crossorigin="anonymous"'],
		[0, "https://cdn.rawgit.com/namuol/cheet.js/master/cheet.min.js", "defer", 'crossorigin="anonymous"'],
		
		// crossorigin doesn't like to behave here
		// already comes minified
		[2, "https://google.com/recaptcha/api.js", "defer"],
		
		// we want this to load firstestest
		[1, ROOTDIR."js/modules/error_handler.js?{commit}"],
		
		[1, ROOTDIR."js/modules/markdown_parser.js?{commit}", "defer"],
		[1, ROOTDIR."js/modules/ajax_progress.js?{commit}", "defer"],
		[1, ROOTDIR."js/modules/console_message.js?{commit}", "defer"],
		[1, ROOTDIR."js/modules/color_functions.js?{commit}", "defer"],
		[1, ROOTDIR."js/modules/input_functions.js?{commit}", "defer"],
		[1, ROOTDIR."js/modules/polyfills.js?{commit}", "defer"],
		[1, ROOTDIR."js/modules/totp_preview.js?{commit}", "defer"],
		[1, ROOTDIR."js/modules/onload.js?{commit}"],

		[0, ROOTDIR."js/dist/packed.min.js"],
	];

	/**
	 * Get the scripts and attributes
	 * @return string[][]
	 */
	public static function getScripts() : array {
		$scripts = [];
		foreach (self::SCRIPTS as $script) {
			switch ($script[0]) {
				case 0: // production only
					if (Controller::isDevelMode()) {
						continue 2;
					}
					break;
				case 1: // devel only
					if (!Controller::isDevelMode()) {
						continue 2;
					}
					break;
				case 2:
				break;
			}
			$script[1] = str_replace("{commit}", Controller::getCommit(), $script[1]);
			$scripts[] = array_slice($script, 1);
		}
		return $scripts;
	}

	/**
	 * CSS-es
	 */
	protected const STYLES = [
		// materialize main
		[1, "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.css"],
		[0, "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css"],

		// icon set + robotos
		[2, "https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i|Material+Icons"],
		
		// overall styles and such, mostly just small things
		[2, ROOTDIR."css/overall.css?{commit}"],
		
		// used for styling, uses rewrite color-######.css to get the approprite styles
		[2, ROOTDIR."css/color-".PAGE_COLOR.".css?{commit}"],
	];

	/**
	 * Get the styles
	 * @return string[]
	 */
	public static function getStyles() : array {
		$styles = [];
		foreach (self::STYLES as $style) {
			switch ($style[0]) {
				case 0: // production only
					if (Controller::isDevelMode()) {
						continue 2;
					}
					break;
				case 1: // devel only
					if (!Controller::isDevelMode()) {
						continue 2;
					}
					break;
				case 2:
				break;
			}
			$styles[] = str_replace("{commit}", Controller::getCommit(), $style[1]);
		}
		return $styles;
	}
}
