<?php

namespace Catalyst\Page;

use Catalyst\Controller;

/**
 * Used to hold data about what scripts and other resources are used
 */
class Resources {
	const PRODUCTION = 0;
	const DEVEL = 1;
	const ALWAYS = 2;

	/**
	 * JS libraries n such
	 */
	public const SCRIPTS = [
		// use minified versions in production
		[self::DEVEL, "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js", 'crossorigin="anonymous"'],
		[self::PRODUCTION, "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js", 'crossorigin="anonymous"'],
		
		[self::DEVEL, "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.1/js/materialize.js", "defer", 'crossorigin="anonymous"'],
		[self::PRODUCTION, "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.1/js/materialize.min.js", "defer", 'crossorigin="anonymous"'],
		
		[self::DEVEL, "https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.7/draggable.bundle.legacy.js", "defer", 'crossorigin="anonymous"'],
		[self::PRODUCTION, "https://cdnjs.cloudflare.com/ajax/libs/draggable/1.0.0-beta.7/draggable.bundle.legacy.min.js", "defer", 'crossorigin="anonymous"'],

		[self::DEVEL, "https://cdnjs.cloudflare.com/ajax/libs/jsencrypt/2.3.1/jsencrypt.js", "defer", 'crossorigin="anonymous"'],
		[self::PRODUCTION, "https://cdnjs.cloudflare.com/ajax/libs/jsencrypt/2.3.1/jsencrypt.min.js", "defer", 'crossorigin="anonymous"'],

		[self::DEVEL, "https://cdnjs.cloudflare.com/ajax/libs/aes-js/3.1.1/index.js", "defer", 'crossorigin="anonymous"'],
		[self::PRODUCTION, "https://cdnjs.cloudflare.com/ajax/libs/aes-js/3.1.1/index.min.js", "defer", 'crossorigin="anonymous"'],

		[self::DEVEL, "https://cdnjs.cloudflare.com/ajax/libs/sjcl/1.0.7/sjcl.js", "defer", 'crossorigin="anonymous"'],
		[self::PRODUCTION, "https://cdnjs.cloudflare.com/ajax/libs/sjcl/1.0.7/sjcl.min.js", "defer", 'crossorigin="anonymous"'],

		// not vital, easter egg
		[self::DEVEL, "https://cdn.rawgit.com/namuol/cheet.js/master/cheet.js", "defer", 'crossorigin="anonymous"'],
		[self::PRODUCTION, "https://cdn.rawgit.com/namuol/cheet.js/master/cheet.min.js", "defer", 'crossorigin="anonymous"'],
		
		// crossorigin doesn't like to behave here
		// already comes minified
		[self::ALWAYS, "https://google.com/recaptcha/api.js", "defer"],
		
		// we want this to load firstestest
		[self::DEVEL, ROOTDIR."js/modules/error_handler.js?{commit}"],
		
		[self::DEVEL, ROOTDIR."js/modules/markdown_parser.js?{commit}", "defer"],
		[self::DEVEL, ROOTDIR."js/modules/ajax_progress.js?{commit}", "defer"],
		[self::DEVEL, ROOTDIR."js/modules/console_message.js?{commit}", "defer"],
		[self::DEVEL, ROOTDIR."js/modules/color_functions.js?{commit}", "defer"],
		[self::DEVEL, ROOTDIR."js/modules/input_functions.js?{commit}", "defer"],
		[self::DEVEL, ROOTDIR."js/modules/polyfills.js?{commit}", "defer"],
		[self::DEVEL, ROOTDIR."js/modules/totp_preview.js?{commit}", "defer"],
		[self::DEVEL, ROOTDIR."js/modules/onload.js?{commit}"],

		[self::PRODUCTION, ROOTDIR."js/dist/packed.min.js?{commit}"],

		[self::ALWAYS, "https://googletagmanager.com/gtag/js?id=UA-112460506-1", "defer"],
	];

	/**
	 * Get the scripts and attributes
	 * @return string[][]
	 */
	public static function getScripts() : array {
		$scripts = [];
		foreach (self::SCRIPTS as $script) {
			switch ($script[self::PRODUCTION]) {
				case self::PRODUCTION: // production only
					if (Controller::isDevelMode()) {
						continue 2;
					}
					break;
				case self::DEVEL: // devel only
					if (!Controller::isDevelMode()) {
						continue 2;
					}
					break;
				case self::ALWAYS:
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
	public const STYLES = [
		// materialize main
		[self::DEVEL, "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.1/css/materialize.css"],
		[self::PRODUCTION, "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.1/css/materialize.min.css"],

		// icon set + robotos
		[self::ALWAYS, "https://fonts.googleapis.com/css?family=Material+Icons"],
		[self::ALWAYS, "https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i"],
		
		// overall styles and such, mostly just small things
		[self::DEVEL, ROOTDIR."css/overall.css?{commit}"],
		[self::PRODUCTION, ROOTDIR."css/overall.min.css?{commit}"],
		
		// used for styling, uses rewrite color-######.css to get the approprite styles
		[self::ALWAYS, ROOTDIR."css/color-".PAGE_COLOR.".css?{commit}"],
	];

	/**
	 * Get the styles
	 * @return string[]
	 */
	public static function getStyles() : array {
		$styles = [];
		foreach (self::STYLES as $style) {
			switch ($style[self::PRODUCTION]) {
				case self::PRODUCTION: // production only
					if (Controller::isDevelMode()) {
						continue 2;
					}
					break;
				case self::DEVEL: // devel only
					if (!Controller::isDevelMode()) {
						continue 2;
					}
					break;
				case self::ALWAYS:
				break;
			}
			$styles[] = str_replace("{commit}", Controller::getCommit(), $style[1]);
		}
		return $styles;
	}
}
