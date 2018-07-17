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
		[self::DEVEL, ROOTDIR."js/external/jquery.js", 'integrity="sha384-fJU6sGmyn07b+uD1nMk7/iSb4yvaowcueiQhfVgQuD98rfva8mcr1eSvjchfpMrH"'],
		[self::PRODUCTION, ROOTDIR."js/external/jquery.min.js", 'integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT"'],
		
		[self::DEVEL, ROOTDIR."js/external/materialize.js", "defer", 'integrity="sha384-VG5Q68Gg4u6g3utXGn2oq5XFI7UcM1XFkHF/oHazvrzpn0rTIo49NuN8Z1CUN4dY"'],
		[self::PRODUCTION, ROOTDIR."js/external/materialize.min.js", "defer", 'integrity="sha384-xkq5MpE6OTpCEKiI52Kl3j3oMebQHg4W08aWkvIoKktH8yYFrJu7HzAU+DqDcYf4"'],
		
		[self::DEVEL, ROOTDIR."js/external/draggable.bundle.legacy.js", "defer", 'integrity="sha384-ErJoNjNx+pdPxftdDZBVwZrZVswpDAdg6/qxkH8WOLIiwknpLC1eDFlT+c2EW/RS"'],
		[self::PRODUCTION, ROOTDIR."js/external/draggable.bundle.legacy.min.js", "defer", 'integrity="sha384-25is1maAa19fCBpiYi0r7GXEMigaUCj0Ba+CdwgaJORgbA5nOpiWNul6vbZwubws"'],

		[self::DEVEL, ROOTDIR."js/external/jsencrypt.js", "defer", 'integrity="sha384-4YOEy5TUNJLUt9LDoiwW93OD+NCDFwWPOgnE5i2SqPJdsSZqvbbJlrOv8J+yvYCI"'],
		[self::PRODUCTION, ROOTDIR."js/external/jsencrypt.min.js", "defer", 'integrity="sha384-uYiTH2ePRlFXtnfS6psKczK3AlqsDO3dpEHqz/GRNrGQi9QdEh8aFAZob9pSyce4"'],

		[self::DEVEL, ROOTDIR."js/external/index.js", "defer", 'integrity="sha384-gHK0hFb5OqUx3ZtJAk/ikyeR4ywEFNcJlrv0mtgA+qb43Dd6D7Fp2OugVsz4pB9z"'],
		[self::PRODUCTION, ROOTDIR."js/external/index.min.js", "defer", 'integrity="sha384-mePQ3qRBaScPjQsgsr06R9L2u8SUZJW2vdociBqfFfK/uHIsZeeRz+J61enIS16G"'],

		[self::DEVEL, ROOTDIR."js/external/sjcl.js", "defer", 'integrity="sha384-zJXJqc5ftX51ojskUi8aWFg0yrz9P61p6Wyg8n0cyFYsgWiAYtxnVWzh8d8BLcw0"'],
		[self::PRODUCTION, ROOTDIR."js/external/sjcl.min.js", "defer", 'integrity="sha384-X0mQP9Ee57CRKxDlf/55LrrCIwWvXucDZvxkZ/rO5WF2By9Lwva4rEFd2LZLogOs"'],

		// not vital, easter egg
		[self::DEVEL, ROOTDIR."js/external/cheet.js", "defer", 'integrity="sha384-hsul02hGCXZOC202Z9Grc2qcn5Et4FLWdJgXYBtTNG3Ux1YPr85TQY1G2/I0YQrd"'],
		[self::PRODUCTION, ROOTDIR."js/external/cheet.min.js", "defer", 'integrity="sha384-8sTXxKn53rkirkXu5gKBBpFxoK/zmAefVSPu6IvC29DIKRkU94ep9TNs6tgyxde4"'],
		
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
		[self::DEVEL, ROOTDIR."css/external/materialize.css", 'integrity="sha384-CsHVDMfHOe63t98dIA4pZzTHRqWzuod8uWMGv24HKx71CwknxEicG5oB1sxO1XWs"'],
		[self::PRODUCTION, ROOTDIR."css/external/materialize.min.css", 'integrity="sha384-3rkQ4BmYtCRaaZ87FvrusoClA6BcjM3PXF+Fk7NJMisMw8+w+EnTR7e/1xSJY4dC"'],
		
		// overall styles and such, mostly just small things
		[self::DEVEL, ROOTDIR."css/overall.css?{commit}"],
		[self::PRODUCTION, ROOTDIR."css/overall.min.css?{commit}"],
		
		// used for styling, uses rewrite color-######.css to get the approprite styles
		[self::ALWAYS, ROOTDIR."css/color-".PAGE_COLOR.".css?{commit}"],

		// icon set + robotos
		[self::ALWAYS, "https://fonts.googleapis.com/css?family=Material+Icons", 'crossorigin="anonymous"'],
		[self::ALWAYS, "https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i", 'crossorigin="anonymous"'],
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
			$style[1] = str_replace("{commit}", Controller::getCommit(), $style[1]);
			$styles[] = array_slice($style, 1);
		}
		return $styles;
	}

	/**
	 * Send Link headers with page resources that may be helpful, especially for HTTP/2
	 */
	public static function pushPageResources() : void {
		if (!headers_sent() &&
			!(array_key_exists("SCRIPT_FILENAME", $_SERVER) && strpos(strrev($_SERVER["SCRIPT_FILENAME"]), strrev(".js")) === 0) &&
			!(array_key_exists("SCRIPT_FILENAME", $_SERVER) && strpos(strrev($_SERVER["SCRIPT_FILENAME"]), strrev(".css")) === 0)) {
			$preconnects = []; // domains to preconnect to

			$preconnects[] = "https://cdnjs.cloudflare.com";
			$preconnects[] = "https://fonts.googleapis.com";
			$preconnects[] = "https://fonts.gstatic.com";
			$preconnects[] = "https://cdn.jsdelivr.net";
			$preconnects[] = "https://www.gstatic.com";
			$preconnects[] = "https://google.com";
			$preconnects[] = "https://cdn.rawgit.com";

			if (!isset($_SERVER['HTTP_DNT']) || $_SERVER['HTTP_DNT'] != 1) {
				$preconnects[] = "https://googletagmanager.com";
				$preconnects[] = "https://www.google-analytics.com";
			}

			$scripts = [];
			$deferredScripts = [];

			foreach (self::getScripts() as $script) {
				$preload = strpos($script[0], "http") !== 0;

				if (in_array("defer", $script)) {
					$deferredScripts[] = [$script[0], $preload ? "preload" : "prefetch"];
				} else {
					$scripts[] = [$script[0], $preload ? "preload" : "prefetch"];
				}
			}

			$styles = [];

			foreach (self::getStyles() as $style) {
				$preload = strpos($style[0], "http") !== 0;

				$styles[] = [$style[0], $preload ? "preload" : "prefetch"];
			}


			foreach ($preconnects as $domain) {
				header("Link: <".$domain.">; rel=preconnect", false);
			}

			foreach ($styles as $style) {
				header("Link: <".$style[0].">; rel=".$style[1]."; as=style; type=text/css", false);
			}

			foreach ($scripts as $script) {
				header("Link: <".$script[0].">; rel=".$script[1]."; as=script; type=application/javascript", false);
			}

			foreach ($deferredScripts as $script) {
				header("Link: <".$script[0].">; rel=".$script[1]."; as=script; type=application/javascript", false);
			}
		}
	}
}
