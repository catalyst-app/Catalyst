<?php

namespace Catalyst\Images;

/**
 * Represents an image on the filesystem
 */
class Image {
	/**
	 * The image's folder
	 * @var string
	 */
	protected $folder;
	/**
	 * The parent's file token
	 * @var string
	 */
	protected $fileToken;
	/**
	 * Path to the image, either a shortened path or null
	 * @var string|null
	 */
	protected $path;
	/**
	 * If the image is mature or explicit
	 * @var bool
	 */
	protected $nsfw;

	/**
	 * Maximum size an image can be if it is pixel art
	 * 
	 * _before any pixel artists yell at me_, I am NOT transforming the image
	 * This is used to change browser rendering so it doesn't antialias
	 */
	public const PIXEL_ART_MAX_SIZE = 100;

	/**
	 * Create a new object to represent an image
	 * 
	 * @param string $folder Folder in which the image is contained
	 * @param string $fileToken The parent object's file token
	 * @param string|null $path The path to the image, or null if default
	 * @param bool $nsfw If the image is mature or explicit
	 */
	public function __construct(string $folder, string $fileToken, ?string $path, bool $nsfw=false) {
		$this->setFolder($folder);
		$this->setFileToken($fileToken);
		$this->setPath($path);
		$this->setNsfw($nsfw);
	}

	/**
	 * @return string
	 */
	public function getFolder() : string {
		return $this->folder;
	}

	/**
	 * @param string $folder
	 */
	public function setFolder(string $folder) : void {
		$this->folder = $folder;
	}

	/**
	 * @return string
	 */
	public function getFileToken() : string {
		return $this->token;
	}

	/**
	 * @param string $fileToken
	 */
	public function setFileToken(string $fileToken) : void {
		$this->token = $fileToken;
	}

	/**
	 * @return string|null
	 */
	public function getPath() : ?string {
		return $this->path;
	}

	/**
	 * @param string|null $path
	 */
	public function setPath(?string $path) : void {
		$this->path = $path;
	}

	/**
	 * @return bool
	 */
	public function isNsfw() : bool {
		return $this->nsfw;
	}

	/**
	 * @param bool $nsfw
	 */
	public function setNsfw(bool $nsfw) : void {
		$this->nsfw = $nsfw;
	}

	/**
	 * If the image is a default image
	 * 
	 * @return bool
	 */
	public function isDefault() : bool {
		return is_null($this->getPath());
	}

	/**
	 * Get the path to the image.  Uses ROOTDIR, not REAL_ROOTDIR
	 * 
	 * @return string Path to the image
	 */
	public function getFullPath() : string {
		if ($this->getFilesystemPath() == $this->getNotFoundFilesystemPath()) {
			return $this->getNotFoundPath(); // NF note
		}
		if (is_null($this->getPath())) {
			return ROOTDIR.$this->getFolder()."/"."default.png";
		} else {
			return ROOTDIR.$this->getFolder()."/".$this->getFileToken().$this->getPath();
		}
	}

	/**
	 * Get the filesystem's path to the image (REAL_ROOTDIR, not ROOTDIR)
	 * 
	 * @return string FS path to the image
	 */
	public function getFilesystemPath() : string {
		if (is_null($this->getPath())) {
			$path = REAL_ROOTDIR.$this->getFolder()."/"."default.png";
		} else {
			$path = REAL_ROOTDIR.$this->getFolder()."/".$this->getFileToken().$this->getPath();
		}
		// prevent warnings and shit
		if (file_exists($path)) {
			return $path;
		} else {
			return $this->getNotFoundFilesystemPath();
		}
	}

	/**
	 * Get the path to the [NSFW] notice
	 * 
	 * @return string
	 */
	public static function getNsfwImagePath() : string {
		return ROOTDIR.'img/nsfw.png';
	}

	/**
	 * Get the path to the image not found notice
	 * 
	 * @return string
	 */
	public static function getNotFoundPath() : string {
		return ROOTDIR.'img/not_found.png';
	}

	/**
	 * Get the FS path to the image not found notice
	 * 
	 * @return string
	 */
	public static function getNotFoundFilesystemPath() : string {
		return REAL_ROOTDIR.'img/not_found.png';
	}

	/**
	 * Determine if the image is pixel art
	 * 
	 * @return bool If the image is pixel art
	 */
	public static function isPixelArt() : bool {
		$imageDimensions = getimagesize($this->getFilesystemPath());
		if ($imageDimensions === false) {
			return false;
		}
		if (min($imageDimensions[0], $imageDimensions[1]) <= self::PIXEL_ART_MAX_SIZE) {
			return true;
		}
		return false;
	}

	/**
	 * Get the image's HTML as a strict circle
	 * 
	 * @return string HTML div.img-strict-circle representing the image
	 */
	public static function getStrictCircleHtml() : string {
		if ($this->isNsfw() && !User::isCurrentUserNsfw()) {
			return '<div class="img-strict-circle" style="background-image: url('.htmlspecialchars(json_encode($this->getNsfwImagePath())).');"></div>';
		}
		if ($this->isPixelArt()) {
			$additionalClass = " render-pixelated";
		} else {
			$additionalClass = "";
		}
		return '<div class="img-strict-circle '.$additionalClass.'" style="background-image: url('.htmlspecialchars(json_encode($this->getFullPath())).');"></div>';
	}
}

