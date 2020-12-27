<?php

namespace Catalyst\Images;

use \Catalyst\Controller;
use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\Query\MultiInsertQuery;
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
	 * Stores list of images to add to the thumbnailing queue on shutdown
	 * @var self[]
	 */
	protected static $pendingThumbnailQueue = [];

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
		$this->path = $path;
		$this->nsfw = $nsfw;
		$this->caption = $caption;
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
	 * @return bool
	 */
	public function isNsfw() : bool {
		return $this->nsfw;
	}

	/**
	 * @return string
	 */
	public function getCaption() : string {
		return $this->caption;
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
		if ($this->getFilesystemPaths() == self::getNotFoundFilesystemPaths()) {
			return self::getNotFoundPaths(); // NF note
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
				$result[] = [$path[0], preg_replace('/'.preg_quote(REAL_ROOTDIR, '/').'/', ROOTDIR, $path[1], 1).""];
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

				if ($this->getFolder() == Folders::PLACEHOLDERS ||
						$this->getFolder() == Folders::ABOUT_ICONS ||
						$this->getFolder() == Folders::LOGO_WHITE ||
						$this->getFolder() == Folders::INTEGRATION_ICONS) { // we're an SVG!
					$result = [
						["image/svg+xml", strrev("gvs.".$pathbase)],
						["image/webp", strrev("pbew.".$pathbase)],
						[$mime, $path],
					];
					usort($result, function($a, $b) : int {
						return filesize($a[1]) <=> filesize($b[1]);
					});
					return $result;
				} else {
					$result = [
						["image/webp", strrev("pbew.bmuht_".$pathbase)],
						["image/jpeg", strrev("gpj.bmuht_".$pathbase)],
						[$mime, $path],
					];

					// webp thumb
					if (!file_exists($result[0][1])) {
						unset($result[0]);
					}
					// jpeg thumb
					if (!file_exists($result[1][1])) {
						unset($result[1]);
					}
					usort($result, function($a, $b) : int {
						return filesize($a[1]) <=> filesize($b[1]);
					});
					return $result;
				}
			}
		} else {
			return self::getNotFoundFilesystemPaths();
		}
	}

	/**
	 * Get the path to the [NSFW] notice
	 * 
	 * @return string[][]
	 */
	public static function getNsfwImagePaths() : array {
		return [
			["image/svg+xml", ROOTDIR.Folders::PLACEHOLDERS.'/nsfw.svg'],
			["image/webp", ROOTDIR.Folders::PLACEHOLDERS.'/nsfw.webp'],
			["image/png", ROOTDIR.Folders::PLACEHOLDERS.'/nsfw.png'],
		];
	}

	/**
	 * Get the path to the image not found notice
	 * 
	 * @return string[][]
	 */
	public static function getNotFoundPaths() : array {
		return [
			["image/svg+xml", ROOTDIR.Folders::PLACEHOLDERS.'/not_found.svg'],
			["image/webp", ROOTDIR.Folders::PLACEHOLDERS.'/not_found.webp'],
			["image/png", ROOTDIR.Folders::PLACEHOLDERS.'/not_found.png'],
		];
	}

	/**
	 * Get the FS path to the image not found notice
	 * 
	 * @return string[][]
	 */
	public static function getNotFoundFilesystemPaths() : array {
		return [
			["image/svg+xml", REAL_ROOTDIR.Folders::PLACEHOLDERS.'/not_found.svg'],
			["image/webp", REAL_ROOTDIR.Folders::PLACEHOLDERS.'/not_found.webp'],
			["image/png", REAL_ROOTDIR.Folders::PLACEHOLDERS.'/not_found.png'],
		];
	}

	/**
	 * Determine if the image is pixel art
	 * 
	 * @return bool If the image is pixel art
	 */
	public function isPixelArt() : bool {
		$img = $this->getFilesystemPaths()[0];
		if (strpos($img[1], "_thumb") !== false && $img[0] !== "image/gif") {
			// if its a thumbnail then it must NOT be pixel art, unless its a GIF, then we need to continue logic
			return false;
		}
		$imageDimensions = getimagesize($img[1]);
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

		if (strpos($paths[0][1], "_thumb.webp") !== false) {
			$additionalClasses[] = "primary-webp-thumb";
		}

		$str .= '<div';
		$str .= ' class="img-strict-circle';
		if ($this->isPixelArt()) {
			$str .= " render-pixelated";
		}
		foreach (array_unique($additionalClasses) as $class) {
			$str .= " ".htmlspecialchars($class);
		}
		$str .= '"';
		$str .= ' style="';
		foreach ($additionalStyles as $key => $value) {
			$str .= htmlspecialchars($key).":".htmlspecialchars($value).";";
		}

		$str .= 'background-image: url('.htmlspecialchars('"'.$paths[0][1].'"').');';
		
		$str .= '"';

		$str .= ' data-fallback-src="'.htmlspecialchars($paths[min(count($paths)-1, 1)][1]).'"';

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
			if (Controller::isDevelMode() && file_exists($path[1])) { // file_exists because rel paths
				$str .= ' data-size="'.filesize($path[1]).'"';
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
		if (!is_null($this->getPath())) {
			if ($this->getFilesystemPaths() != self::getNotFoundFilesystemPaths()) { // image not found
				foreach ($this->getFilesystemPaths() as $fsPath) {
					if (file_exists($fsPath[1])) {
						unlink($fsPath[1]);
					}
				}
			}
		}
		$this->path = "deleted_image.png";
		$this->nsfw = false;
		$this->caption = "Deleted image";
	}

	/**
	 * Write the pending operation queues (currently only thumbnailing)
	 */
	public static function writePendingOperationQueues() : void {
		self::writeThumbnailQueue();
	}

	/**
	 * Write the thumbnail queue to database to be processed later by a job
	 */
	public static function writeThumbnailQueue() : void {
		self::$pendingThumbnailQueue = array_filter(self::$pendingThumbnailQueue, function(self $in) : bool {
			return !is_null($in->getPath());
		});

		if (empty(self::$pendingThumbnailQueue)) {
			return;
		}

		$stmt = new MultiInsertQuery();

		$stmt->setTable(Tables::PENDING_THUMBNAIL_QUEUE);

		$stmt->addColumn(new Column("FOLDER", Tables::PENDING_THUMBNAIL_QUEUE));
		$stmt->addColumn(new Column("TOKEN", Tables::PENDING_THUMBNAIL_QUEUE));
		$stmt->addColumn(new Column("PATH", Tables::PENDING_THUMBNAIL_QUEUE));

		foreach (array_unique(self::$pendingThumbnailQueue, SORT_REGULAR) as $image) {
			$stmt->addValue($image->getFolder());
			$stmt->addValue($image->getToken());
			$stmt->addValue($image->getPath());
		}

		$stmt->execute();
	}

	/**
	 * Inserts the image into the queue to be thumbnailed
	 */
	public function queueForThumbnailing() : void {
		if ($this->getFilesystemPaths() == $this->getNotFoundFilesystemPaths() || is_null($this->getPath())) {
			return;
		}

		self::$pendingThumbnailQueue[] = $this;
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

		$obj->queueForThumbnailing();

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
		return new self(Folders::PLACEHOLDERS, "", "new.png", false);
	}

	/**
	 * Get the main logo
	 * 
	 * @return self
	 */
	public static function getLogoImage() : self {
		return new self(Folders::LOGO_WHITE, "", "logo.png", false);
	}

	/**
	 * Get the images of fauxil and lykai for the unimplemented page
	 * 
	 * @return self[]
	 */
	public static function getUnimplementedImages() : array {
		$images = [
			"computer",
			"e621",
			"game",
			"ok",
			"original",
			"other",
			"so",
		];
		$fauxImage = $images[array_rand($images)];
		if ($fauxImage == "e621") {
			$fauxImage .= "_".random_int(1, 3);
		}
		if ($fauxImage == "other") {
			$lykaiImage = "other";
		} else {
			do {
				$lykaiImage = $images[array_rand($images)];
			} while ($lykaiImage == "other"); // only show other if both are other
			if ($lykaiImage == "e621") {
				$lykaiImage .= "_".random_int(1, 3);
			}
		}
		return [
			new self(Folders::UNIMPLEMENTED_MEMES_FAUXIL, $fauxImage, ".png", false),
			new self(Folders::UNIMPLEMENTED_MEMES_LYKAI, $lykaiImage, ".png", false),
		];
	}
}
