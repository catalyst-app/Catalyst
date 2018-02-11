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
	protected $token;
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
	 * Create a new object to represent an image
	 * 
	 * @param string $folder Folder in which the image is contained
	 * @param string $token The parent object's file token
	 * @param string|null $path The path to the image, or null if default
	 * @param bool $nsfw If the image is mature or explicit
	 */
	public function __construct(string $folder, string $token, ?string $path, bool $nsfw=false) {
		$this->setFolder($folder);
		$this->setToken($token);
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
	public function getToken() : string {
		return $this->token;
	}

	/**
	 * @param string $token
	 */
	public function setToken(string $token) : void {
		$this->token = $token;
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
			return REAL_ROOTDIR.$this->getFolder()."/"."default.png";
		} else {
			return REAL_ROOTDIR.$this->getFolder()."/".$this->getFileToken().$this->getPath();
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
}

