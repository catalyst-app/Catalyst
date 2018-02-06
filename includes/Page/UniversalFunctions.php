<?php

namespace Catalyst\Page;

class UniversalFunctions {
	public static function createHeading(string $contents) : string {
		return implode("\n", [
			'<div class="section">',
			self::createHeadingWithoutSection($contents),
			'</div>',
			'<div class="divider"></div>',
			''
		]);
	}
	public static function createHeadingWithoutSection(string $contents) : string {
		return implode("\n", [
			'<h1 class="center header hide-on-small-only">'.$contents.'</h1>',
			'<h3 class="center header hide-on-med-and-up">'.$contents.'</h3>',
			''
		]);
	}

	// https://github.com/mingalevme/utils/blob/master/src/Filesize.php
	const UNIT_PREFIXES_POWERS = [
		'B' => 0,
		''  => 0,
		'K' => 1,
		'k' => 1,
		'M' => 2,
		'G' => 3,
		'T' => 4,
		'P' => 5,
		'E' => 6,
		'Z' => 7,
		'Y' => 8,
	];

	public static function getRequestURI() : string {
		return $_SERVER["REQUEST_SCHEME"]."://".((isset($_SERVER["HTTP_X_FORWARDED_HOST"])) ? $_SERVER["HTTP_X_FORWARDED_HOST"] : $_SERVER["SERVER_NAME"].(($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ? "" : ":".$_SERVER["SERVER_PORT"])).$_SERVER["REQUEST_URI"];
	}

	// https://github.com/mingalevme/utils/blob/master/src/Filesize.php
	public static function dehumanize(string $size) {
		if (preg_match('/\d+\.\d+B/', $size)) {
			throw new \Exception("Invalid size format or unknown/unsupported units");
		}
		
		if (preg_match('/\d+kiB/', $size)) {
			throw new \Exception("Invalid size format or unknown/unsupported units");
		}
		
		$supportedUnits = implode('', array_keys(self::UNIT_PREFIXES_POWERS));
		$regexp = "/^(\d+(?:\.\d+)?)(([{$supportedUnits}])((?<!B)(B|iB))?)?$/";
		
		if ((bool) preg_match($regexp, $size, $matches) === false) {
			throw new \Exception("Invalid size format or unknown/unsupported units");
		}
		
		$prefix = isset($matches[3]) ? $matches[3] : 'B';
		
		$base = 1024;
		
		if (strpos($matches[1], '.') !== false) {
			return intval(floatval($matches[1]) * pow($base, self::UNIT_PREFIXES_POWERS[$prefix]));
		} else {
			return intval($matches[1]) * pow($base, self::UNIT_PREFIXES_POWERS[$prefix]);
		}
	}

	public static function getStrictCircleImageHTML(string $filename, bool $nsfw, string $additionalClasses="") : string {
		if ($nsfw && (\Catalyst\User\User::isLoggedOut() || !$_SESSION["user"]->isNsfw())) {
			return '<div class="img-strict-circle'.(strlen($additionalClasses) ? ' ' : '').$additionalClasses.'" style="background-image: url(\''.ROOTDIR.'img/nsfw.png\');"></div>';
		}
		if (!empty(ROOTDIR)) {
			$pos = strpos($filename, ROOTDIR);
			$realfile = $filename;
			if ($pos !== false) {
			    $realfile = substr_replace($filename, REAL_ROOTDIR, $pos, strlen(ROOTDIR));
			}
		} else {
			$realfile = $filename;
		}
		if (!file_exists($filename)) {
			return '';
		}
		return '<div class="img-strict-circle'.(strlen($additionalClasses) ? ' ' : '').$additionalClasses.(min(array_slice(getimagesize($realfile), 0, 2)) < 100 ? " render-pixelated" : "").'" style="background-image: url(\''.$filename.'\');"></div>';
	}

	public static function renderImageCard(string $filename, bool $nsfw, string $title, string $caption, string $link) : string {
		if ($nsfw && (\Catalyst\User\User::isLoggedOut() || !$_SESSION["user"]->isNsfw())) {
			return self::renderImageCard(ROOTDIR.'img/nsfw.png', false, $title, $caption, $link);
		}
		if (!empty(ROOTDIR)) {
			$pos = strpos($filename, ROOTDIR);
			$realfile = $filename;
			if ($pos !== false) {
			    $realfile = substr_replace($filename, REAL_ROOTDIR, $pos, strlen(ROOTDIR));
			}
		} else {
			$realfile = $filename;
		}
		if (!file_exists($filename)) {
			return implode("", [
				'<a href="'.(empty($link) ? $filename : $link).'" class="col s12 card hoverable">',
				'<div class="card-image">',
				'</div>',
				(!empty($caption) || !empty($title) ? '<div class="card-content black-text">' : ''),
				(!empty($title) ? '<p class="card-title">'.htmlspecialchars($title).'</p>' : ''),
				(!empty($caption) ? '<p>'.str_replace("\n", "</p><p>", htmlspecialchars($caption)).'</p>' : ''),
				(!empty($caption) || !empty($title) ? '</div>' : ''),
				'</a>'
			]);
		}
		return implode("", [
			'<a href="'.(empty($link) ? $filename : $link).'" class="col s12 card hoverable">',
			'<div class="card-image">',
			'<img class="z-depth-2'.(min(array_slice(getimagesize($realfile), 0, 2)) < 100 ? " render-pixelated" : "").'" src="'.$filename.'">',
			'</div>',
			(!empty($caption) || !empty($title) ? '<div class="card-content black-text">' : ''),
			(!empty($title) ? '<p class="card-title">'.htmlspecialchars($title).'</p>' : ''),
			(!empty($caption) ? '<p>'.str_replace("\n", "</p><p>", htmlspecialchars($caption)).'</p>' : ''),
			(!empty($caption) || !empty($title) ? '</div>' : ''),
			'</a>'
		]);
	}

	public static function renderImageCardRawHTML(string $filename, bool $nsfw, string $html) : string {
		if ($nsfw && (\Catalyst\User\User::isLoggedOut() || !$_SESSION["user"]->isNsfw())) {
			return self::renderImageCardRawHTML(ROOTDIR.'img/nsfw.png', false, $html);
		}
		if (!empty(ROOTDIR)) {
			$pos = strpos($filename, ROOTDIR);
			$realfile = $filename;
			if ($pos !== false) {
			    $realfile = substr_replace($filename, REAL_ROOTDIR, $pos, strlen(ROOTDIR));
			}
		} else {
			$realfile = $filename;
		}
		if (!file_exists($filename)) {
			return implode("", [
				'<div class="col s12 card hoverable">',
				'<div class="card-image">',
				'</div>',
				'<div class="card-content black-text">',
				$html,
				'</div>',
				'</div>',
			]);
		}
		return implode("", [
			'<div class="col s12 card hoverable">',
			'<div class="card-image">',
			'<img class="z-depth-2'.(min(array_slice(getimagesize($realfile), 0, 2)) < 100 ? " render-pixelated" : "").'" src="'.$filename.'">',
			'</div>',
			'<div class="card-content black-text">',
			$html,
			'</div>',
			'</div>',
		]);
	}
	
	public static function renderImageCardWithRibbon(string $filename, bool $nsfw, string $title, string $caption, string $link, string $ribbon, string $ribbonColor) : string {
		if ($nsfw && (\Catalyst\User\User::isLoggedOut() || !$_SESSION["user"]->isNsfw())) {
			return self::renderImageCard(ROOTDIR.'img/nsfw.png', false, $title, $caption, $link);
		}
		if (!empty(ROOTDIR)) {
			$pos = strpos($filename, ROOTDIR);
			$realfile = $filename;
			if ($pos !== false) {
			    $realfile = substr_replace($filename, REAL_ROOTDIR, $pos, strlen(ROOTDIR));
			}
		} else {
			$realfile = $filename;
		}
		if (!file_exists($filename)) {
			return implode("", [
				'<a href="'.(empty($link) ? $filename : $link).'" class="col s12 card hoverable">',
				'<div class="ribbon" style="background-color: #'.$ribbonColor.'">'.$ribbon.'</div>',
				'<div class="card-image">',
				'</div>',
				(!empty($caption) || !empty($title) ? '<div class="card-content black-text">' : ''),
				(!empty($title) ? '<p class="card-title">'.htmlspecialchars($title).'</p>' : ''),
				(!empty($caption) ? '<p>'.str_replace("\n", "</p><p>", htmlspecialchars($caption)).'</p>' : ''),
				(!empty($caption) || !empty($title) ? '</div>' : ''),
				'</a>'
			]);
		}
		return implode("", [
			'<a href="'.(empty($link) ? $filename : $link).'" class="col s12 card hoverable">',
			'<div class="ribbon" style="background-color: #'.$ribbonColor.'">'.$ribbon.'</div>',
			'<div class="card-image">',
			'<img class="z-depth-2'.(min(array_slice(getimagesize($realfile), 0, 2)) < 100 ? " render-pixelated" : "").'" src="'.$filename.'">',
			'</div>',
			(!empty($caption) || !empty($title) ? '<div class="card-content black-text">' : ''),
			(!empty($title) ? '<p class="card-title">'.htmlspecialchars($title).'</p>' : ''),
			(!empty($caption) ? '<p>'.str_replace("\n", "</p><p>", htmlspecialchars($caption)).'</p>' : ''),
			(!empty($caption) || !empty($title) ? '</div>' : ''),
			'</a>'
		]);
	}

	public static function zeropad($num, $lim) {
		return (strlen($num) >= $lim) ? $num : self::zeropad("0" . $num, $lim);
	}
}
