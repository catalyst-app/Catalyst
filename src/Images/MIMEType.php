<?php

namespace Catalyst\Images;

use \InvalidArgumentException;

/**
 * MIME utility class
 */
class MIMEType {
	/**
	 * Association of MIME => extension
	 */
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

	/**
	 * Get all known MIME types
	 * 
	 * @return string[] A list of MIME types
	 */
	public static function getMimeTypes() : array {
		return array_keys(self::MIME_TO_EXT);
	}

	/**
	 * Get a filepath's MIME type
	 * 
	 * @param string $filepath Path to the file
	 * @return string The file's mime type
	 */
	public static function getFilepathMimeType(string $filepath) : string {
		$info = finfo_open(FILEINFO_MIME_TYPE);
		$result = finfo_file($info, $filepath);
		finfo_close($info);
		return $result;
	}

	/**
	 * Determie if a MIME type is known/valid
	 * 
	 * @param string $mime MIME type to get test
	 * @return bool if the MIME type is known and valid
	 */
	public static function isValidMimeType(string $mime) : bool {
		return array_key_exists($mime, self::MIME_TO_EXT);
	}

	/**
	 * Get a MIME type's file extension
	 * 
	 * @param string $mime MIME type to get extension of
	 * @return string The type's extension
	 */
	public static function getExtensionFromMime(string $mime) : string {
		if (!array_key_exists($mime, self::MIME_TO_EXT)) {
			throw new InvalidArgumentException($mime." is not a known MIME type");
		}
		return self::MIME_TO_EXT[$mime];
	}
}
