<?php

namespace Catalyst;

class Color {
	public const HEX_MAP = [
		"e57373"=>["red", "lighten-2"],
		"ef5350"=>["red", "lighten-1"],
		"f44336"=>["red", ""],
		"e53935"=>["red", "darken-1"],
		"d32f2f"=>["red", "darken-2"],
		"c62828"=>["red", "darken-3"],
		"b71c1c"=>["red", "darken-4"],
		"ff8a80"=>["red", "accent-1"],
		"ff5252"=>["red", "accent-2"],
		"ff1744"=>["red", "accent-3"],
		"d50000"=>["red", "accent-4"],
		"f06292"=>["pink", "lighten-2"],
		"ec407a"=>["pink", "lighten-1"],
		"e91e63"=>["pink", ""],
		"d81b60"=>["pink", "darken-1"],
		"c2185b"=>["pink", "darken-2"],
		"ad1457"=>["pink", "darken-3"],
		"880e4f"=>["pink", "darken-4"],
		"ff80ab"=>["pink", "accent-1"],
		"ff4081"=>["pink", "accent-2"],
		"f50057"=>["pink", "accent-3"],
		"c51162"=>["pink", "accent-4"],
		"ba68c8"=>["purple", "lighten-2"],
		"ab47bc"=>["purple", "lighten-1"],
		"9c27b0"=>["purple", ""],
		"8e24aa"=>["purple", "darken-1"],
		"7b1fa2"=>["purple", "darken-2"],
		"6a1b9a"=>["purple", "darken-3"],
		"4a148c"=>["purple", "darken-4"],
		"ea80fc"=>["purple", "accent-1"],
		"e040fb"=>["purple", "accent-2"],
		"d500f9"=>["purple", "accent-3"],
		"aa00ff"=>["purple", "accent-4"],
		"9575cd"=>["deep-purple", "lighten-2"],
		"7e57c2"=>["deep-purple", "lighten-1"],
		"673ab7"=>["deep-purple", ""],
		"5e35b1"=>["deep-purple", "darken-1"],
		"512da8"=>["deep-purple", "darken-2"],
		"4527a0"=>["deep-purple", "darken-3"],
		"311b92"=>["deep-purple", "darken-4"],
		"b388ff"=>["deep-purple", "accent-1"],
		"7c4dff"=>["deep-purple", "accent-2"],
		"651fff"=>["deep-purple", "accent-3"],
		"6200ea"=>["deep-purple", "accent-4"],
		"7986cb"=>["indigo", "lighten-2"],
		"5c6bc0"=>["indigo", "lighten-1"],
		"3f51b5"=>["indigo", ""],
		"3949ab"=>["indigo", "darken-1"],
		"303f9f"=>["indigo", "darken-2"],
		"283593"=>["indigo", "darken-3"],
		"1a237e"=>["indigo", "darken-4"],
		"8c9eff"=>["indigo", "accent-1"],
		"536dfe"=>["indigo", "accent-2"],
		"3d5afe"=>["indigo", "accent-3"],
		"304ffe"=>["indigo", "accent-4"],
		"64b5f6"=>["blue", "lighten-2"],
		"42a5f5"=>["blue", "lighten-1"],
		"2196f3"=>["blue", ""],
		"1e88e5"=>["blue", "darken-1"],
		"1976d2"=>["blue", "darken-2"],
		"1565c0"=>["blue", "darken-3"],
		"0d47a1"=>["blue", "darken-4"],
		"82b1ff"=>["blue", "accent-1"],
		"448aff"=>["blue", "accent-2"],
		"2979ff"=>["blue", "accent-3"],
		"2962ff"=>["blue", "accent-4"],
		"4fc3f7"=>["light-blue", "lighten-2"],
		"29b6f6"=>["light-blue", "lighten-1"],
		"03a9f4"=>["light-blue", ""],
		"039be5"=>["light-blue", "darken-1"],
		"0288d1"=>["light-blue", "darken-2"],
		"0277bd"=>["light-blue", "darken-3"],
		"01579b"=>["light-blue", "darken-4"],
		"40c4ff"=>["light-blue", "accent-2"],
		"00b0ff"=>["light-blue", "accent-3"],
		"0091ea"=>["light-blue", "accent-4"],
		"4dd0e1"=>["cyan", "lighten-2"],
		"26c6da"=>["cyan", "lighten-1"],
		"00bcd4"=>["cyan", ""],
		"00acc1"=>["cyan", "darken-1"],
		"0097a7"=>["cyan", "darken-2"],
		"00838f"=>["cyan", "darken-3"],
		"006064"=>["cyan", "darken-4"],
		"18ffff"=>["cyan", "accent-2"],
		"00e5ff"=>["cyan", "accent-3"],
		"00b8d4"=>["cyan", "accent-4"],
		"4db6ac"=>["teal", "lighten-2"],
		"26a69a"=>["teal", "lighten-1"],
		"009688"=>["teal", ""],
		"00897b"=>["teal", "darken-1"],
		"00796b"=>["teal", "darken-2"],
		"00695c"=>["teal", "darken-3"],
		"004d40"=>["teal", "darken-4"],
		"64ffda"=>["teal", "accent-2"],
		"1de9b6"=>["teal", "accent-3"],
		"00bfa5"=>["teal", "accent-4"],
		"81c784"=>["green", "lighten-2"],
		"66bb6a"=>["green", "lighten-1"],
		"4caf50"=>["green", ""],
		"43a047"=>["green", "darken-1"],
		"388e3c"=>["green", "darken-2"],
		"2e7d32"=>["green", "darken-3"],
		"1b5e20"=>["green", "darken-4"],
		"69f0ae"=>["green", "accent-2"],
		"00e676"=>["green", "accent-3"],
		"00c853"=>["green", "accent-4"],
		"aed581"=>["light-green", "lighten-2"],
		"9ccc65"=>["light-green", "lighten-1"],
		"8bc34a"=>["light-green", ""],
		"7cb342"=>["light-green", "darken-1"],
		"689f38"=>["light-green", "darken-2"],
		"558b2f"=>["light-green", "darken-3"],
		"33691e"=>["light-green", "darken-4"],
		"b2ff59"=>["light-green", "accent-2"],
		"76ff03"=>["light-green", "accent-3"],
		"64dd17"=>["light-green", "accent-4"],
		"dce775"=>["lime", "lighten-2"],
		"d4e157"=>["lime", "lighten-1"],
		"cddc39"=>["lime", ""],
		"c0ca33"=>["lime", "darken-1"],
		"afb42b"=>["lime", "darken-2"],
		"9e9d24"=>["lime", "darken-3"],
		"827717"=>["lime", "darken-4"],
		"eeff41"=>["lime", "accent-2"],
		"c6ff00"=>["lime", "accent-3"],
		"aeea00"=>["lime", "accent-4"],
		"fff176"=>["yellow", "lighten-2"],
		"ffee58"=>["yellow", "lighten-1"],
		"ffeb3b"=>["yellow", ""],
		"fdd835"=>["yellow", "darken-1"],
		"fbc02d"=>["yellow", "darken-2"],
		"f9a825"=>["yellow", "darken-3"],
		"f57f17"=>["yellow", "darken-4"],
		"ffff8d"=>["yellow", "accent-1"],
		"ffff00"=>["yellow", "accent-2"],
		"ffea00"=>["yellow", "accent-3"],
		"ffd600"=>["yellow", "accent-4"],
		"ffd54f"=>["amber", "lighten-2"],
		"ffca28"=>["amber", "lighten-1"],
		"ffc107"=>["amber", ""],
		"ffb300"=>["amber", "darken-1"],
		"ffa000"=>["amber", "darken-2"],
		"ff8f00"=>["amber", "darken-3"],
		"ff6f00"=>["amber", "darken-4"],
		"ffe57f"=>["amber", "accent-1"],
		"ffd740"=>["amber", "accent-2"],
		"ffc400"=>["amber", "accent-3"],
		"ffab00"=>["amber", "accent-4"],
		"ffb74d"=>["orange", "lighten-2"],
		"ffa726"=>["orange", "lighten-1"],
		"ff9800"=>["orange", ""],
		"fb8c00"=>["orange", "darken-1"],
		"f57c00"=>["orange", "darken-2"],
		"ef6c00"=>["orange", "darken-3"],
		"e65100"=>["orange", "darken-4"],
		"ffd180"=>["orange", "accent-1"],
		"ffab40"=>["orange", "accent-2"],
		"ff9100"=>["orange", "accent-3"],
		"ff6d00"=>["orange", "accent-4"],
		"ff8a65"=>["deep-orange", "lighten-2"],
		"ff7043"=>["deep-orange", "lighten-1"],
		"ff5722"=>["deep-orange", ""],
		"f4511e"=>["deep-orange", "darken-1"],
		"e64a19"=>["deep-orange", "darken-2"],
		"d84315"=>["deep-orange", "darken-3"],
		"bf360c"=>["deep-orange", "darken-4"],
		"ff9e80"=>["deep-orange", "accent-1"],
		"ff6e40"=>["deep-orange", "accent-2"],
		"ff3d00"=>["deep-orange", "accent-3"],
		"dd2c00"=>["deep-orange", "accent-4"],
		"a1887f"=>["brown", "lighten-2"],
		"8d6e63"=>["brown", "lighten-1"],
		"795548"=>["brown", ""],
		"6d4c41"=>["brown", "darken-1"],
		"5d4037"=>["brown", "darken-2"],
		"4e342e"=>["brown", "darken-3"],
		"3e2723"=>["brown", "darken-4"],
		"bdbdbd"=>["grey", "lighten-1"],
		"9e9e9e"=>["grey", ""],
		"757575"=>["grey", "darken-1"],
		"616161"=>["grey", "darken-2"],
		"424242"=>["grey", "darken-3"],
		"212121"=>["grey", "darken-4"],
		"90a4ae"=>["blue-grey", "lighten-2"],
		"78909c"=>["blue-grey", "lighten-1"],
		"607d8b"=>["blue-grey", ""],
		"546e7a"=>["blue-grey", "darken-1"],
		"455a64"=>["blue-grey", "darken-2"],
		"37474f"=>["blue-grey", "darken-3"],
		"263238"=>["blue-grey", "darken-4"],
		"000000"=>["black", ""]
	];

	public const COLOR_BY_CATEGORY = [
		'red'=>[
			'lighten-2'=>'e57373',
			'lighten-1'=>'ef5350',
			''=>'f44336',
			'darken-1'=>'e53935',
			'darken-2'=>'d32f2f',
			'darken-3'=>'c62828',
			'darken-4'=>'b71c1c',
			'accent-1'=>'ff8a80',
			'accent-2'=>'ff5252',
			'accent-3'=>'ff1744',
			'accent-4'=>'d50000'
		],
		'pink'=>[
			'lighten-2'=>'f06292',
			'lighten-1'=>'ec407a',
			''=>'e91e63',
			'darken-1'=>'d81b60',
			'darken-2'=>'c2185b',
			'darken-3'=>'ad1457',
			'darken-4'=>'880e4f',
			'accent-1'=>'ff80ab',
			'accent-2'=>'ff4081',
			'accent-3'=>'f50057',
			'accent-4'=>'c51162'
		],
		'purple'=>[
			'lighten-2'=>'ba68c8',
			'lighten-1'=>'ab47bc',
			''=>'9c27b0',
			'darken-1'=>'8e24aa',
			'darken-2'=>'7b1fa2',
			'darken-3'=>'6a1b9a',
			'darken-4'=>'4a148c',
			'accent-1'=>'ea80fc',
			'accent-2'=>'e040fb',
			'accent-3'=>'d500f9',
			'accent-4'=>'aa00ff'
		],
		'deep-purple'=>[
			'lighten-2'=>'9575cd',
			'lighten-1'=>'7e57c2',
			''=>'673ab7',
			'darken-1'=>'5e35b1',
			'darken-2'=>'512da8',
			'darken-3'=>'4527a0',
			'darken-4'=>'311b92',
			'accent-1'=>'b388ff',
			'accent-2'=>'7c4dff',
			'accent-3'=>'651fff',
			'accent-4'=>'6200ea'
		],
		'indigo'=>[
			'lighten-2'=>'7986cb',
			'lighten-1'=>'5c6bc0',
			''=>'3f51b5',
			'darken-1'=>'3949ab',
			'darken-2'=>'303f9f',
			'darken-3'=>'283593',
			'darken-4'=>'1a237e',
			'accent-1'=>'8c9eff',
			'accent-2'=>'536dfe',
			'accent-3'=>'3d5afe',
			'accent-4'=>'304ffe'
		],
		'blue'=>[
			'lighten-2'=>'64b5f6',
			'lighten-1'=>'42a5f5',
			''=>'2196f3',
			'darken-1'=>'1e88e5',
			'darken-2'=>'1976d2',
			'darken-3'=>'1565c0',
			'darken-4'=>'0d47a1',
			'accent-1'=>'82b1ff',
			'accent-2'=>'448aff',
			'accent-3'=>'2979ff',
			'accent-4'=>'2962ff'
		],
		'light-blue'=>[
			'lighten-2'=>'4fc3f7',
			'lighten-1'=>'29b6f6',
			''=>'03a9f4',
			'darken-1'=>'039be5',
			'darken-2'=>'0288d1',
			'darken-3'=>'0277bd',
			'darken-4'=>'01579b',
			'accent-2'=>'40c4ff',
			'accent-3'=>'00b0ff',
			'accent-4'=>'0091ea'
		],
		'cyan'=>[
			'lighten-2'=>'4dd0e1',
			'lighten-1'=>'26c6da',
			''=>'00bcd4',
			'darken-1'=>'00acc1',
			'darken-2'=>'0097a7',
			'darken-3'=>'00838f',
			'darken-4'=>'006064',
			'accent-2'=>'18ffff',
			'accent-3'=>'00e5ff',
			'accent-4'=>'00b8d4'
		],
		'teal'=>[
			'lighten-2'=>'4db6ac',
			'lighten-1'=>'26a69a',
			''=>'009688',
			'darken-1'=>'00897b',
			'darken-2'=>'00796b',
			'darken-3'=>'00695c',
			'darken-4'=>'004d40',
			'accent-2'=>'64ffda',
			'accent-3'=>'1de9b6',
			'accent-4'=>'00bfa5'
		],
		'green'=>[
			'lighten-2'=>'81c784',
			'lighten-1'=>'66bb6a',
			''=>'4caf50',
			'darken-1'=>'43a047',
			'darken-2'=>'388e3c',
			'darken-3'=>'2e7d32',
			'darken-4'=>'1b5e20',
			'accent-2'=>'69f0ae',
			'accent-3'=>'00e676',
			'accent-4'=>'00c853'
		],
		'light-green'=>[
			'lighten-2'=>'aed581',
			'lighten-1'=>'9ccc65',
			''=>'8bc34a',
			'darken-1'=>'7cb342',
			'darken-2'=>'689f38',
			'darken-3'=>'558b2f',
			'darken-4'=>'33691e',
			'accent-2'=>'b2ff59',
			'accent-3'=>'76ff03',
			'accent-4'=>'64dd17'
		],
		'lime'=>[
			'lighten-2'=>'dce775',
			'lighten-1'=>'d4e157',
			''=>'cddc39',
			'darken-1'=>'c0ca33',
			'darken-2'=>'afb42b',
			'darken-3'=>'9e9d24',
			'darken-4'=>'827717',
			'accent-2'=>'eeff41',
			'accent-3'=>'c6ff00',
			'accent-4'=>'aeea00'
		],
		'yellow'=>[
			'lighten-2'=>'fff176',
			'lighten-1'=>'ffee58',
			''=>'ffeb3b',
			'darken-1'=>'fdd835',
			'darken-2'=>'fbc02d',
			'darken-3'=>'f9a825',
			'darken-4'=>'f57f17',
			'accent-1'=>'ffff8d',
			'accent-2'=>'ffff00',
			'accent-3'=>'ffea00',
			'accent-4'=>'ffd600'
		],
		'amber'=>[
			'lighten-2'=>'ffd54f',
			'lighten-1'=>'ffca28',
			''=>'ffc107',
			'darken-1'=>'ffb300',
			'darken-2'=>'ffa000',
			'darken-3'=>'ff8f00',
			'darken-4'=>'ff6f00',
			'accent-1'=>'ffe57f',
			'accent-2'=>'ffd740',
			'accent-3'=>'ffc400',
			'accent-4'=>'ffab00'
		],
		'orange'=>[
			'lighten-2'=>'ffb74d',
			'lighten-1'=>'ffa726',
			''=>'ff9800',
			'darken-1'=>'fb8c00',
			'darken-2'=>'f57c00',
			'darken-3'=>'ef6c00',
			'darken-4'=>'e65100',
			'accent-1'=>'ffd180',
			'accent-2'=>'ffab40',
			'accent-3'=>'ff9100',
			'accent-4'=>'ff6d00'
		],
		'deep-orange'=>[
			'lighten-2'=>'ff8a65',
			'lighten-1'=>'ff7043',
			''=>'ff5722',
			'darken-1'=>'f4511e',
			'darken-2'=>'e64a19',
			'darken-3'=>'d84315',
			'darken-4'=>'bf360c',
			'accent-1'=>'ff9e80',
			'accent-2'=>'ff6e40',
			'accent-3'=>'ff3d00',
			'accent-4'=>'dd2c00'
		],
		'brown'=>[
			'lighten-2'=>'a1887f',
			'lighten-1'=>'8d6e63',
			''=>'795548',
			'darken-1'=>'6d4c41',
			'darken-2'=>'5d4037',
			'darken-3'=>'4e342e',
			'darken-4'=>'3e2723'
		],
		'grey'=>[
			'lighten-1'=>'bdbdbd',
			''=>'9e9e9e',
			'darken-1'=>'757575',
			'darken-2'=>'616161',
			'darken-3'=>'424242',
			'darken-4'=>'212121'
		],
		'blue-grey'=>[
			'lighten-2'=>'90a4ae',
			'lighten-1'=>'78909c',
			''=>'607d8b',
			'darken-1'=>'546e7a',
			'darken-2'=>'455a64',
			'darken-3'=>'37474f',
			'darken-4'=>'263238'
		],
		'black'=>[
			''=>'000000'
		]
	];

	public const COLOR_BY_HEX = [
		'f44336' => [
			'e57373',
			'ef5350',
			'f44336',
			'e53935',
			'd32f2f',
			'c62828',
			'b71c1c',
			'ff8a80',
			'ff5252',
			'ff1744',
			'd50000'
		],
		'e91e63' => [
			'f06292',
			'ec407a',
			'e91e63',
			'd81b60',
			'c2185b',
			'ad1457',
			'880e4f',
			'ff80ab',
			'ff4081',
			'f50057',
			'c51162'
		],
		'9c27b0' => [
			'ba68c8',
			'ab47bc',
			'9c27b0',
			'8e24aa',
			'7b1fa2',
			'6a1b9a',
			'4a148c',
			'ea80fc',
			'e040fb',
			'd500f9',
			'aa00ff'
		],
		'673ab7' => [
			'9575cd',
			'7e57c2',
			'673ab7',
			'5e35b1',
			'512da8',
			'4527a0',
			'311b92',
			'b388ff',
			'7c4dff',
			'651fff',
			'6200ea'
		],
		'3f51b5' => [
			'7986cb',
			'5c6bc0',
			'3f51b5',
			'3949ab',
			'303f9f',
			'283593',
			'1a237e',
			'8c9eff',
			'536dfe',
			'3d5afe',
			'304ffe'
		],
		'2196f3' => [
			'64b5f6',
			'42a5f5',
			'2196f3',
			'1e88e5',
			'1976d2',
			'1565c0',
			'0d47a1',
			'82b1ff',
			'448aff',
			'2979ff',
			'2962ff'
		],
		'03a9f4' => [
			'4fc3f7',
			'29b6f6',
			'03a9f4',
			'039be5',
			'0288d1',
			'0277bd',
			'01579b',
			'40c4ff',
			'00b0ff',
			'0091ea'
		],
		'00bcd4' => [
			'4dd0e1',
			'26c6da',
			'00bcd4',
			'00acc1',
			'0097a7',
			'00838f',
			'006064',
			'18ffff',
			'00e5ff',
			'00b8d4'
		],
		'009688' => [
			'4db6ac',
			'26a69a',
			'009688',
			'00897b',
			'00796b',
			'00695c',
			'004d40',
			'64ffda',
			'1de9b6',
			'00bfa5'
		],
		'4caf50' => [
			'81c784',
			'66bb6a',
			'4caf50',
			'43a047',
			'388e3c',
			'2e7d32',
			'1b5e20',
			'69f0ae',
			'00e676',
			'00c853'
		],
		'8bc34a' => [
			'aed581',
			'9ccc65',
			'8bc34a',
			'7cb342',
			'689f38',
			'558b2f',
			'33691e',
			'b2ff59',
			'76ff03',
			'64dd17'
		],
		'cddc39' => [
			'dce775',
			'd4e157',
			'cddc39',
			'c0ca33',
			'afb42b',
			'9e9d24',
			'827717',
			'eeff41',
			'c6ff00',
			'aeea00'
		],
		'ffeb3b' => [
			'fff176',
			'ffee58',
			'ffeb3b',
			'fdd835',
			'fbc02d',
			'f9a825',
			'f57f17',
			'ffff8d',
			'ffff00',
			'ffea00',
			'ffd600'
		],
		'ffc107' => [
			'ffd54f',
			'ffca28',
			'ffc107',
			'ffb300',
			'ffa000',
			'ff8f00',
			'ff6f00',
			'ffe57f',
			'ffd740',
			'ffc400',
			'ffab00'
		],
		'ff9800' => [
			'ffb74d',
			'ffa726',
			'ff9800',
			'fb8c00',
			'f57c00',
			'ef6c00',
			'e65100',
			'ffd180',
			'ffab40',
			'ff9100',
			'ff6d00'
		],
		'ff5722' => [
			'ff8a65',
			'ff7043',
			'ff5722',
			'f4511e',
			'e64a19',
			'd84315',
			'bf360c',
			'ff9e80',
			'ff6e40',
			'ff3d00',
			'dd2c00'
		],
		'795548' => [
			'a1887f',
			'8d6e63',
			'795548',
			'6d4c41',
			'5d4037',
			'4e342e',
			'3e2723'
		],
		'9e9e9e' => [
			'bdbdbd',
			'9e9e9e',
			'757575',
			'616161',
			'424242',
			'212121'
		],
		'607d8b' => [
			'90a4ae',
			'78909c',
			'607d8b',
			'546e7a',
			'455a64',
			'37474f',
			'263238'
		],
		'000000' => [
			'000000'
		]
	];

	public static function getArrFromHex(string $hex) : array {
		if (!isset(self::HEX_MAP[$hex])) {
			throw new \InvalidArgumentException("Color ".$hex." is not known.");
		}

		return [
			"hex" => $hex,
			"base" => self::HEX_MAP[$hex][0],
			"mod" => self::HEX_MAP[$hex][1]
		];
	}

	public static function getRGB(string $hex) : array {
		if (strlen($hex) == 3) {
			$hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
		}
		$exploded = [$hex[0].$hex[1], $hex[2].$hex[3], $hex[4].$hex[5]];
		return array_map("hexdec", $exploded);
	}

	public static function lightenHex(string $hex, int $steps) : string {
		if (strlen($hex) == 3) {
			$hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
		}
		$exploded = [$hex[0].$hex[1], $hex[2].$hex[3], $hex[4].$hex[5]];
		foreach ($exploded as &$byte) {
			$val = hexdec($byte);
			$val = ($val + (255*$steps))/(1+$steps);
			$byte = dechex($val);
		}

		return implode("", $exploded);
	}

	public static function lightenHexByPercent(string $hex, int $percent) : string {
		if (strlen($hex) == 3) {
			$hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
		}
		$exploded = [$hex[0].$hex[1], $hex[2].$hex[3], $hex[4].$hex[5]];
		foreach ($exploded as &$byte) {
			$val = hexdec($byte);
			$val = ((100-$percent)*$val + $percent*255)/(100);
			$byte = dechex($val);
		}

		return implode("", array_map(function($a) { return \Catalyst\Page\UniversalFunctions::zeropad($a, 2); }, $exploded));
	}
}
