<?php

namespace Catalyst\Form;

class FileUpload {
	public const PROFILE_PHOTO = 0;
	public const CHARACTER_IMAGE = 1;
	public const ARTIST_IMAGE = 2;
	public const COMMISSION_TYPE_IMAGE = 3;

	public const FOLDERS = [
		self::PROFILE_PHOTO => "profile_pictures",
		self::CHARACTER_IMAGE => "character_images",
		self::ARTIST_IMAGE => "artist_images",
		self::COMMISSION_TYPE_IMAGE => "commission_type_images"
	];

	public const MIME_TO_EXT = [
		// JPEG
		"image/jpeg" => "jpg",
		// JPEG 2000
		"image/jp2" => "jp2",
		"image/jpx" => "jpx",
		"image/jpm" => "jpm",
		"image/mj2" => "mj2",
		// JPEG XR
		"image/vnd.ms-photo" => "jxr",
		"image/jxr" => "jxr",
		// WEBP
		"image/webp" => "webp",
		// GIF
		"image/gif" => "gif",
		// PNG
		"image/png" => "png",
		// APNG
		"image/apng" => "apng",
		// BMP
		"image/bmp" => "bmp",
		"image/x-bmp" => "bmp",
		"image/x-ms-bmp" => "bmp",
		// ICO
		"image/x-icon" => "ico",
		// XBM
		"image/xbm" => "xbm",
		"image/x-xbm" => "xbm",
		"image/x-xbitmap" => "xbm",
	];

	public static function uploadImage(?array $file, int $type, string $prefix) : ?string {
		if (is_null($file) || !isset($file["error"]) || $file["error"] !== 0) {
			return null;
		}

		$suffix = ".".self::MIME_TO_EXT[self::getMIME($file["tmp_name"])];

		if ($suffix == ".") {
			return null;
		}

		$middle = \Catalyst\Tokens::generateToken(10);

		while (file_exists(REAL_ROOTDIR.self::FOLDERS[$type]."/".$prefix.$middle.$suffix)) {
			error_log("a");
		    $middle = \Catalyst\Tokens::generateToken(10);
		}

		move_uploaded_file($file["tmp_name"], REAL_ROOTDIR.self::FOLDERS[$type]."/".$prefix.$middle.$suffix);

		return $middle.$suffix;
	}

	public static function uploadImages(?array $files, int $type, string $prefix) : ?array {
		if (is_null($files)) {
			return null;
		}

		$results = [];

		for ($i=0; $i < count($files["name"]); $i++) { 
			$results[] = self::uploadImage(array_merge(...array_map(function($val, $key) use ($i) { return [$key => $val[$i]]; }, $files, array_keys($files))), $type, $prefix);
		}

		$results = array_filter($results, function($in) { return !is_null($in); });

		if (empty($results)) {
			return null;
		}

		return $results;
	}

	public static function delete(?string $filename,int $type) {
		if (is_null($filename)) {
			return;
		}

		unlink(REAL_ROOTDIR.self::FOLDERS[$type]."/".$filename);
	}

	public static function getMIME(string $filename) : string {
		$info = finfo_open(FILEINFO_MIME_TYPE);
		$result = finfo_file($info, $filename);
		finfo_close($info);
		return $result;
	}
}
