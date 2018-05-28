<?php

namespace Catalyst\Images;

use \Catalyst\Tokens;
use \Catalyst\User\User;

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
	 * Image caption, optional
	 * @var string
	 */
	protected $caption;
	/**
	 * Get the name of the file, if this image is the DIRECT result of an upload (from ::upload or ::uploadMultiple)
	 * @var string
	 */
	protected $uploadName='';

	/**
	 * Maximum size an image can be if it is pixel art
	 * 
	 * _before any pixel artists yell at me_, I am NOT transforming the image
	 * This is used to change browser rendering so it doesn't antialias
	 */
	public const PIXEL_ART_MAX_SIZE = 150;

	/**
	 * Lenth of token used for midsection
	 * 
	 * Changing this will require updating max length in database
	 */
	public const FILE_DISTINGUISHER_LENGTH = 10;

	/**
	 * Create a new object to represent an image
	 * 
	 * @param string $folder Folder in which the image is contained
	 * @param string $fileToken The parent object's file token
	 * @param string|null $path The path to the image, or null if default
	 * @param bool $nsfw If the image is mature or explicit
	 */
	public function __construct(string $folder, string $fileToken, ?string $path, bool $nsfw=false, string $caption="") {
		if ($path == "default.png") {
			$path = null; // BC
		}
		$this->setFolder($folder);
		$this->setFileToken($fileToken);
		$this->setPath($path);
		$this->setNsfw($nsfw);
		$this->setCaption($caption);
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
		return $this->fileToken;
	}

	/**
	 * @param string $fileToken
	 */
	public function setFileToken(string $fileToken) : void {
		$this->fileToken = $fileToken;
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
	 * @return string
	 */
	public function getCaption() : string {
		return $this->caption;
	}

	/**
	 * @param string $caption
	 */
	public function setCaption(string $caption) : void {
		$this->caption = $caption;
	}

	/**
	 * @return string
	 */
	public function getUploadName() : string {
		return $this->uploadName;
	}

	/**
	 * @param string $uploadName
	 */
	protected function setUploadName(string $uploadName) : void {
		$this->uploadName = $uploadName;
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
	 * @return string[][] Path to the image, as [type,path], last type most legacy
	 */
	public function getFullPaths() : array {
		if ($this->isNsfw() && !User::isCurrentUserNsfw()) {
			return $this->getNsfwImagePaths();
		}
		if ($this->getFilesystemPaths() == $this->getNotFoundFilesystemPaths()) {
			return $this->getNotFoundPaths(); // NF note
		}
		if (is_null($this->getPath())) {
			return [
				["image/svg+xml", ROOTDIR.$this->getFolder()."/"."default.svg"],
				["image/webp", ROOTDIR.$this->getFolder()."/"."default.webp"],
				["image/png", ROOTDIR.$this->getFolder()."/"."default.png"],
			];
		} else {
			$result = [];
			foreach ($this->getFilesystemPaths() as $path) {
				$result[] = [$path[0], preg_replace('/'.preg_quote(REAL_ROOTDIR, '/').'/', ROOTDIR, $path[1], 1)];
			}
			return $result;
		}
	}

	/**
	 * Get the path to the fallback image.
	 * 
	 * @return string Path to the fallback image
	 */
	public function getFullPath() : string {
		$paths = array_values($this->getFullPaths());
		return $paths[count($paths)-1][1];
	}

	/**
	 * Get the filesystem's path to the image (REAL_ROOTDIR, not ROOTDIR)
	 * 
	 * @return string[][] FS paths to the image, as [type,path], last type most legacy
	 */
	public function getFilesystemPaths() : array {
		if (is_null($this->getPath())) {
			$path = REAL_ROOTDIR.$this->getFolder()."/"."default.png";
		} else {
			$path = REAL_ROOTDIR.$this->getFolder()."/".$this->getToken().$this->getPath();
		}
		// prevent warnings and shit
		if (file_exists($path)) {
			$mime = getimagesize($path)["mime"];
			if ($mime == "image/webp") {
				return [
					["image/webp", $path],
				];
			} else {
				$pathrev = strrev($path);
				$pathbase = substr($pathrev, strpos($pathrev, ".")+1);

				if ($this->getFolder() == Folders::GLOBAL_IMG ||
						$this->getFolder() == Folders::ABOUT_ICONS) { // we're an SVG!
					return [
						["image/svg+xml", strrev("gvs.".$pathbase)],
						["image/webp", strrev("pbew.".$pathbase)],
						[$mime, $path],
					];
				} else {
					$result = [
						["image/webp", strrev("pbew.sselssol_bmuht_".$pathbase)],
						["image/webp", strrev("pbew.yssol_bmuht_".$pathbase)],
						["image/jpeg", strrev("gepj.bmuht_".$pathbase)],
						[$mime, $path],
					];
					// BC with existing images
					// lossless webp thumb
					if (!file_exists($result[0][1])) { 
						unset($result[0]);
					}
					// lossy webp thumb
					if (!file_exists($result[1][1])) {
						unset($result[1]);
					}
					// jpeg thumb
					if (!file_exists($result[2][1])) {
						unset($result[2]);
					}
					uasort($result, function($a, $b) : int {
						return filesize($a[1]) <=> filesize($b[1]);
					});
					return $result;
				}
			}
		} else {
			return $this->getNotFoundFilesystemPaths();
		}
	}

	/**
	 * Get the path to the [NSFW] notice
	 * 
	 * @return string[][]
	 */
	public static function getNsfwImagePaths() : array {
		return [
			["image/svg+xml", ROOTDIR.Folders::GLOBAL_IMG.'/nsfw.svg'],
			["image/webp", ROOTDIR.Folders::GLOBAL_IMG.'/nsfw.webp'],
			["image/png", ROOTDIR.Folders::GLOBAL_IMG.'/nsfw.png'],
		];
	}

	/**
	 * Get the path to the image not found notice
	 * 
	 * @return string[][]
	 */
	public static function getNotFoundPaths() : array {
		return [
			["image/svg+xml", ROOTDIR.Folders::GLOBAL_IMG.'/not_found.svg'],
			["image/webp", ROOTDIR.Folders::GLOBAL_IMG.'/not_found.webp'],
			["image/png", ROOTDIR.Folders::GLOBAL_IMG.'/not_found.png'],
		];
	}

	/**
	 * Get the FS path to the image not found notice
	 * 
	 * @return string[][]
	 */
	public static function getNotFoundFilesystemPaths() : array {
		return [
			["image/svg+xml", REAL_ROOTDIR.Folders::GLOBAL_IMG.'/not_found.svg'],
			["image/webp", REAL_ROOTDIR.Folders::GLOBAL_IMG.'/not_found.webp'],
			["image/png", REAL_ROOTDIR.Folders::GLOBAL_IMG.'/not_found.png'],
		];
	}

	/**
	 * Determine if the image is pixel art
	 * 
	 * @return bool If the image is pixel art
	 */
	public function isPixelArt() : bool {
		$imageDimensions = getimagesize($this->getFullPath());
		if ($imageDimensions === false) { // doesn't exist ?
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
	 * @param string[] $additionalClasses Classes to add to the div
	 * @param string[] $additionalStyles Styles to add to the div
	 * @param string[] $additionalAttributes Attributes to add to the div
	 * @return string HTML div.img-strict-circle representing the image
	 */
	public function getStrictCircleHtml(array $additionalClasses=[], array $additionalStyles=[], array $additionalAttributes=[]) : string {
		$str = '';

		$paths = $this->getFullPaths();

		$preferredPath = $paths[0];
		$fallbackPath = $paths[count($paths)-1];

		$str .= '<div';
		$str .= ' class="img-strict-circle';
		if ($this->isPixelArt()) {
			$str .= " render-pixelated";
		}
		foreach ($additionalClasses as $class) {
			$str .= " ".htmlspecialchars($class);
		}
		$str .= '"';
		$str .= ' style="';
		foreach ($additionalStyles as $key => $value) {
			$str .= htmlspecialchars($key).":".htmlspecialchars($value).";";
		}

		$str .= 'background-image: url('.htmlspecialchars('"'.$preferredPath[1].'"').');';
		
		$str .= '"';

		if ($preferredPath != $fallbackPath) {
			$str .= ' onerror="';
			$str .= 'this.onerror=null;';
			$str .= 'this.src='.htmlspecialchars(json_encode($fallbackPath[1])).';';
			$str .= 'return false;';
			$str .= '"';
		}

		foreach ($additionalAttributes as $key => $value) {
			$str .= ' '.htmlspecialchars($key).'="'.htmlspecialchars($value).'"';
		}
		$str .= '></div>';
		return $str;
	}

	/**
	 * Get the image as a <picture> element
	 * 
	 * @param string[] $additionalClasses Classes to add to the tag
	 * @return string HTML picture representing the image
	 */
	public function getImgElementHtml(array $additionalClasses=[]) : string {
		if ($this->isPixelArt()) {
			$additionalClasses[] = "render-pixelated";
		}
		if (is_null($this->getPath())) {
			$additionalClasses[] = "default-img";
		}
		$str = '';

		$str .= '<picture';
		if (count($additionalClasses)) {
		 	$str .= ' class="'.htmlspecialchars(implode(" ", $additionalClasses)).'"';
		}
		$str .= '>';

		$paths = $this->getFullPaths();

		foreach ($paths as $path) {
			$str .= '<source';
			$str .= ' srcset="'.htmlspecialchars($path[1]).'"';
			$str .= ' type="'.htmlspecialchars($path[0]).'"';
			if (Controller::isDevelMode()) {
				$str .= ' data-size="'.htmlspecialchars(filesize($path[1])).'"';
			}
			$str .= '>';
		}

		$paths = array_values($paths);
		$fallbackPath = $paths[count($paths)-1];
		
		$str .= '<img'; // fallback for shit browsers >:/
		$str .= ' src="'.htmlspecialchars($fallbackPath[1]).'"';
		$str .= ' alt="'.htmlspecialchars($this->getFolder()).' path"';
		if (count($additionalClasses)) {
		 	$str .= ' class="'.htmlspecialchars(implode(" ", $additionalClasses)).'"';
		}
		$str .= ' />';

		$str .= '</picture>';
		
		return $str;
	}

	/**
	 * Render an image card with given parameters
	 * 
	 * Makes use of getCardFromRawHtml
	 * 
	 * @param string $title Card title
	 * @param string|null $caption Card caption, will use getCaption() if null
	 * @param bool $link If the card should link to something
	 * @param null|string $linkPath What the card should link to, null if image, no effect if $link=false
	 * @param array $ribbon Ribbon color and text, as [hex, text]
	 * @param bool $sendNsfw If there should be anything returned if the card is nsfw
	 * @return string the card html
	 */
	public function getCard(string $title="", ?string $caption=null, bool $link=false, ?string $linkPath=null, array $ribbon=[], bool $sendNsfw=false) : string {
		$html = '';

		if (is_null($caption)) {
			$caption = $this->getCaption();
		}

		if (!empty($title)) {
			$html .= '<p';
			$html .= ' class="card-title"';
			$html .= '>';

			$html .= htmlspecialchars($title);

			$html .= '</p>';
		}

		if (!empty($caption)) {
			$html .= '<p';
			$html .= ' class="raw-inline-markdown"';
			$html .= '>';

			$html .= htmlspecialchars($caption);

			$html .= '</p>';
		}

		$ribbonHtml = '';
		if (count($ribbon) == 2) {
			$ribbonHtml .= '<div';
			$ribbonHtml .= ' class="ribbon"';
			$ribbonHtml .= ' style="background-color: #'.$ribbon[0].'"';
			$ribbonHtml .= '>';
			$ribbonHtml .= htmlspecialchars($ribbon[1]);
			$ribbonHtml .= '</div>';
		}

		return $this->getCardFromRawHtml($html, $link, $linkPath, $ribbonHtml, $sendNsfw);
	}

	/**
	 * Render an image card with raw HTML
	 * 
	 * @param string $html HTML to use for the card
	 * @param bool $link If the card should be a link or not
	 * @param string|null $linkPath Null if the card should link to the image (default), or a link to link to
	 * @param bool $sendNsfw If the card should still return html if the item is NSFW
	 * @return string Card html
	 */
	public function getCardFromRawHtml(string $html, bool $link=false, ?string $linkPath=null, string $preHtml="", bool $sendNsfw=false) : string {
		if ($this->isNsfw() && !$sendNsfw && !User::isCurrentUserNsfw()) {
			return '';
		}
		$str = '';

		if ($link) {
			$str .= '<a';
			if (is_null($linkPath)) {
				$str .= ' target="_blank"';
				$str .= ' href="'.htmlspecialchars($this->getFullPath()).'"';
			} else {
				$str .= ' href="'.htmlspecialchars($linkPath).'"';
			}
		} else {
			$str .= '<div';
		}
		$str .= ' class="col s12 card hoverable"';
		$str .= '>';

		$str .= $preHtml;

		$str .= '<div';
		$str .= ' class="card-image"';
		$str .= '>';

		$str .= $this->getImgElementHtml(["z-depth-2"]);

		$str .= '</div>';

		if (!empty(trim($html))) {
			$str .= '<div class="card-content black-text">';
			$str .= $html;
			$str .= '</div>';
		}
		
		if ($link) {
			$str .= '</a>';
		} else {
			$str .= '</div>';
		}

		return $str;
	}

	/**
	 * Delete the image from disk (won't work for default)
	 */
	public function delete() : void {
		if (!is_null($this->getPath()) && file_exists(array_values($this->getFilesystemPaths())[0])) {
			if (array_values($this->getFilesystemPaths())[0] != REAL_ROOTDIR.$this->getFolder()."/"."default.png" && // no image
				$this->getFilesystemPaths() != $this->getNotFoundFilesystemPaths()) { // image not found
				array_map("unlink", $this->getFilesystemPaths());
			}
		}
		$this->setPath("deleted_image.png");
		$this->setNsfw(false);
		$this->setCaption("Deleted image");
	}

	/**
	 * Upload an image to the server with the given parameters
	 * 
	 * @param null|array $image An uploaded image object, from the $_FILES array
	 * @param string $folder Folder to place the uploaded image
	 * @param string $fileToken Token to use for the new image
	 * @return null|self The newly uploaded image, or null on failure
	 */
	public static function upload(?array $image, string $folder, string $fileToken) : ?self {
		if (is_null($image) || !array_key_exists("error",$image) || $image["error"] !== 0) {
			return null;
		}

		$mime = MIMEType::getFilepathMimeType($image["tmp_name"]);

		if (!MIMEType::isValidMimeType($mime)) {
			return null;
		}

		$suffix = ".".MIMEType::getExtensionFromMime($mime);

		$middle = Tokens::generateToken(self::FILE_DISTINGUISHER_LENGTH);

		while (file_exists(REAL_ROOTDIR.$folder."/".$fileToken.$middle.$suffix)) {
			$middle = Tokens::generateToken(self::FILE_DISTINGUISHER_LENGTH);
		}

		move_uploaded_file($image["tmp_name"], REAL_ROOTDIR.$folder."/".$fileToken.$middle.$suffix);

		$obj = new self($folder, $fileToken, $middle.$suffix);
		$obj->setUploadName($image["name"]);

		return $obj;
	}

	/**
	 * Upload a set of images to the server with the given parameters
	 * 
	 * @param array[] $images An uploaded images object, from the $_FILES array, should have multiple
	 * @param string $folder Folder to place the uploaded image
	 * @param string $fileToken Token to use for the new image
	 * @return self[] The newly uploaded image, or null on failure
	 */
	public static function uploadMultiple(array $images, string $folder, string $fileToken) : array {
		if (!array_key_exists("error",$images) || !is_array($images["error"])) {
			$upload = self::upload($images, $folder, $fileToken);
			if (is_null($upload)) {
				return [];
			} else {
				return [$upload];
			}
		}

		$arr = [];

		for ($i=0; $i < count($images["error"]); $i++) { 
			$image = [];
			foreach ($images as $key => $value) {
				$image[$key] = $value[$i];
			}
			$arr[] = self::upload($image, $folder, $fileToken);
		}

		return array_filter($arr);
	}

	/**
	 * Get the image for a new {item}
	 * 
	 * @return self
	 */
	public static function getNewItemImage() : self {
		return new self(Folders::GLOBAL_IMG, "", "new.png", false);
	}
}
