<?php

namespace Catalyst\Images;

use \InvalidArgumentException;

/**
 * Represents a MIME type
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
	 * MIME type of the instance
	 * 
	 * @var string
	 */
	protected $mime;

	/**
	 * Create a new instance of MIMEType
	 * 
	 * @param string $mime The instance's mime type
	 */
	public function __construct(string $mime) {
		$this->setMime($mime);
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

	/**
	 * Get the mime type
	 * 
	 * @return string Current MIME type
	 */
	public function getMime() : string {
		return $this->mime;
	}

	/**
	 * Set the internal MIME type
	 * 
	 * @param string $mime New MIME
	 */
	public function setMime(string $mime) : void {
		if (!array_key_exists($mime, self::MIME_TO_EXT)) {
			throw new InvalidArgumentException($mime." is not a known MIME type");
		}
		$this->mime = $mime;
	}

	/**
	 * Get the mime type
	 * 
	 * @return string File's extension
	 */
	public function getExtension() : string {
		return self::getExtensionFromMime($this->getMime());
	}
}
