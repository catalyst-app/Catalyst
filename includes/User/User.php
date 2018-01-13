<?php

namespace Catalyst\User;

class User implements \Serializable {
	private $id;

	private $cache = [];

	public function __construct(int $id) {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT 1 FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $id);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			throw new \InvalidArgumentException("User ID ".$id." does not exist in the database.");
		}

		$stmt->closeCursor();

		$this->id = $id;
	}

	public static function idExists(int $id) : bool {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT 1 FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $id);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			return false;
		}
		return true;
	}

	public static function isLoggedIn() : bool {
		return isset($_SESSION["logged_in"]) && ($_SESSION["logged_in"] === true) && !is_null($_SESSION["user"]) && $_SESSION["user"] instanceof self;
	}

	public static function isLoggedOut() : bool {
		return !self::isLoggedIn();
	}

	public static function getCurrentUser() : self {
		if (!self::isLoggedIn()) {
			throw new \LogicException("Cannot get current user when the user is not logged in.");
		}
		return $_SESSION["user"];
	}

	public static function getPermissionScope() : array {
		if (self::isLoggedIn()) {
			$perms = ["all", "logged_in"];

			$currentUser = self::getCurrentUser();

			if ($currentUser->isArtist()) {
				$perms[] = "artist";
			}
			if (!$currentUser->isArtist()) {
				$perms[] = "not-artist";
			}
			if ($currentUser->isNsfw()) {
				$perms[] = "nsfw";
			}
			
			return $perms;
		} else {
			return ["all", "logged_out"];
		}
	}

	public static function getAlreadyLoggedInHTML() : string {
		return implode("\n", [
			'			<div class="section">',
			'				<p class="flow-text">You are already logged in.  <a href="'.ROOTDIR.'">Return home</a></p>',
			'			</div>',
		]);
	}

	public static function getNotLoggedInHTML() : string {
		return implode("\n", [
			'			<div class="section">',
			'				<p class="flow-text">You must log in to access this page.  <a href="'.ROOTDIR.'Login">Login</a></p>',
			'			</div>',
		]);
	}

	public static function getInvalidHTML() : string {
		return implode("\n", [
			'			<div class="section">',
			'				<p class="flow-text">This account does not exist or has been removed.</p>',
			'			</div>',
		]);
	}

	public function getColor() : array {
		if (array_key_exists("COLOR", $this->cache)) {
			return \Catalyst\Color::getArrFromHex($this->cache["COLOR"]);
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `COLOR` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$hex = $this->cache["COLOR"] = bin2hex($stmt->fetchAll()[0]["COLOR"]);

		$stmt->closeCursor();

		return \Catalyst\Color::getArrFromHex($hex);
	}

	public function getColorHex() : string {
		if (array_key_exists("COLOR", $this->cache)) {
			return $this->cache["COLOR"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `COLOR` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$hex = $this->cache["COLOR"] = bin2hex($stmt->fetchAll()[0]["COLOR"]);

		$stmt->closeCursor();

		return $hex;
	}

	public function emailIsVerified() : bool {
		if (array_key_exists("EMAIL_VERIFIED", $this->cache)) {
			return $this->cache["EMAIL_VERIFIED"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `EMAIL_VERIFIED` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID AND `EMAIL` IS NOT NULL;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		if ($stmt->rowCount() === 0) {
			return $this->cache["EMAIL_VERIFIED"] = true;
		}

		$result = $this->cache["EMAIL_VERIFIED"] = (bool)($stmt->fetchAll()[0]["EMAIL_VERIFIED"]);

		$stmt->closeCursor();

		return $result;
	}

	public function getEmail() : ?string {
		if (array_key_exists("EMAIL", $this->cache)) {
			return $this->cache["EMAIL"];
		}
		
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `EMAIL` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["EMAIL"] = $stmt->fetchAll()[0]["EMAIL"];

		$stmt->closeCursor();

		return $result;
	}

	public function getEmailToken() : string {
		if (array_key_exists("EMAIL_TOKEN", $this->cache)) {
			return $this->cache["EMAIL_TOKEN"];
		}
		
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `EMAIL_TOKEN` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["EMAIL_TOKEN"] = $stmt->fetchAll()[0]["EMAIL_TOKEN"];

		$stmt->closeCursor();

		return $result;
	}

	public function getNickname() : string {
		if (array_key_exists("NICK", $this->cache)) {
			return $this->cache["NICK"];
		}
		
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `NICK` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["NICK"] = htmlspecialchars($stmt->fetchAll()[0]["NICK"]);

		$stmt->closeCursor();

		return $result;
	}

	public function getUsername() : string {
		if (array_key_exists("USERNAME", $this->cache)) {
			return $this->cache["USERNAME"];
		}
		
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `USERNAME` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["USERNAME"] = $stmt->fetchAll()[0]["USERNAME"];

		$stmt->closeCursor();

		return $result;
	}

	public function isNsfw() : bool {
		if (array_key_exists("NSFW", $this->cache)) {
			return $this->cache["NSFW"];
		}
		
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `NSFW` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["NSFW"] = (bool)($stmt->fetchAll()[0]["NSFW"]);

		$stmt->closeCursor();

		return $result;
	}

	public function getProfilePictureNsfw() : bool {
		if (array_key_exists("PICTURE_NSFW", $this->cache)) {
			return $this->cache["PICTURE_NSFW"];
		}
		
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `PICTURE_NSFW` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["PICTURE_NSFW"] = (bool)($stmt->fetchAll()[0]["PICTURE_NSFW"]);

		$stmt->closeCursor();

		return $result;
	}

	public function getArtistPageId() : ?int {
		if (array_key_exists("ARTIST_PAGE_ID", $this->cache)) {
			return $this->cache["ARTIST_PAGE_ID"];
		}
		
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ARTIST_PAGE_ID` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["ARTIST_PAGE_ID"] = $stmt->fetchAll()[0]["ARTIST_PAGE_ID"];

		$stmt->closeCursor();

		return $result;
	}

	public function getArtistPage() : ?\Catalyst\Artist\Artist {
		if (array_key_exists("ARTIST_PAGE", $this->cache)) {
			return $this->cache["ARTIST_PAGE"];
		}

		return (is_null($this->getArtistPageId()) ? null : ($this->cache["ARTIST_PAGE"] = new \Catalyst\Artist\Artist($this->getArtistPageId())));
	}

	public function getFileToken() : string {
		if (array_key_exists("FILE_TOKEN", $this->cache)) {
			return $this->cache["FILE_TOKEN"];
		}
		
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `FILE_TOKEN` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["FILE_TOKEN"] = $stmt->fetchAll()[0]["FILE_TOKEN"];

		$stmt->closeCursor();

		return $result;
	}

	public function getProfilePicture() : string {
		if (array_key_exists("PICTURE_LOC", $this->cache)) {
			return $this->cache["PICTURE_LOC"];
		}
		
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `PICTURE_LOC` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$loc = $stmt->fetchAll()[0]["PICTURE_LOC"];

		$result = $this->cache["PICTURE_LOC"] = ($loc ? $this->getFileToken().$loc : "default.png");

		$stmt->closeCursor();

		return $result;
	}

	public function getId() : int {
		return $this->id;
	}

	public function getWishlist() : array {
		if (array_key_exists("WISHLIST", $this->cache)) {
			return $this->cache["WISHLIST"];
		}
		
		$stmt = $GLOBALS["dbh"]->prepare("
			SELECT
				`".DB_TABLES["user_wishlists"]."`.`COMMISSION_TYPE_ID`
			FROM
				`".DB_TABLES["user_wishlists"]."`
			INNER JOIN `".DB_TABLES["commission_types"]."` ON
					`".DB_TABLES["commission_types"]."`.`ID` = `".DB_TABLES["user_wishlists"]."`.`COMMISSION_TYPE_ID`
			WHERE
				`".DB_TABLES["user_wishlists"]."`.`USER_ID` = :USER_ID
				AND
				`".DB_TABLES["commission_types"]."`.`DELETED` = 0;"
		);
		$stmt->bindParam(":USER_ID", $this->id);
		$stmt->execute();

		return $result = $this->cache["WISHLIST"] = array_column($stmt->fetchAll(), "COMMISSION_TYPE_ID");
	}

	public function getWishlistAsObjects() : array {
		return array_map(function($in) { return (new \Catalyst\CommissionType\CommissionType($in)); }, $this->getWishlist());
	}

	public function idIsOnWishlist(int $id) : bool {
		return in_array($id, $this->getWishlist());
	}

	public function isOnWishlist(\Catalyst\CommissionType\CommissionType $type) : bool {
		return $this->idIsOnWishlist($type->getId());
	}

	public function isArtist() : bool {
		return !is_null($this->getArtistPageId());
	}

	public function getProfilePicturePath() : string {
		return ROOTDIR.\Catalyst\Form\FileUpload::FOLDERS[\Catalyst\Form\FileUpload::PROFILE_PHOTO]."/".$this->getProfilePicture();
	}

	public function getNavbarDropdown(int $bar) : string {
		if ($bar == \Catalyst\Page\Navigation\Navbar::NAVBAR) {
			return \Catalyst\Page\UniversalFunctions::getStrictCircleImageHTML($this->getProfilePicturePath(), $this->getProfilePictureNsfw(), "valign").$this->getNickname();
		} else {
			return "My Account";
		}
	}

	public function getSidenavHTML() : string {
		return '<li class="center">'.\Catalyst\Page\UniversalFunctions::getStrictCircleImageHTML($this->getProfilePicturePath(), $this->getProfilePictureNsfw()).'<h5>'.$this->getNickname().'</h5><p class="grey-text">'.$this->getUsername().'</p></li>';
	}

	public function clearCache(?string $toClear=null) {
		if (is_null($toClear)) {
			$this->cache = [];
		} else {
			unset($this->cache[$toClear]);
		}
	}

	public static function getIdFromUsername(string $username) : int {
		if (!preg_match("/^([A-Za-z0-9._-]){2,64}$/", $username)) {
			return -1;
		}
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ID` FROM `".DB_TABLES["users"]."` WHERE `USERNAME` = :USERNAME AND `SUSPENDED` = 0 AND `DEACTIVATED` = 0;");
		$stmt->bindParam(":USERNAME", $username);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			return -1;
		} else {
			$result = $stmt->fetchAll()[0]["ID"];
			$stmt->closeCursor();
			return $result;
		}
	}

	public function serialize() : string {
		return $this->id;
	}

	public function unserialize($data) {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `FILE_TOKEN`,`USERNAME`,`EMAIL`,`EMAIL_VERIFIED`,`ARTIST_PAGE_ID`,`PICTURE_LOC`,`PICTURE_NSFW`,`NSFW`,`COLOR`,`NICK` FROM `".DB_TABLES["users"]."` WHERE `ID` = :ID AND  `DEACTIVATED` = 0 AND `SUSPENDED` = 0;");
		$stmt->bindParam(":ID", $data);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			throw new \InvalidArgumentException("User ID ".$data." does not exist in the database.");
		}

		$user = $stmt->fetchAll()[0];

		$stmt->closeCursor();

		$this->cache = [
			"FILE_TOKEN" => $user["FILE_TOKEN"],
			"USERNAME" => $user["USERNAME"],
			"EMAIL" => $user["EMAIL"],
			"EMAIL_VERIFIED" => ((bool)($user["EMAIL_VERIFIED"]) || is_null($user["EMAIL"])),
			"ARTIST_PAGE_ID" => $user["ARTIST_PAGE_ID"],
			"PICTURE_LOC" => ($user["PICTURE_LOC"] ? $user["FILE_TOKEN"].$user["PICTURE_LOC"] : "default.png"),
			"PICTURE_NSFW" => (bool)($user["PICTURE_NSFW"]),
			"NSFW" => (bool)($user["NSFW"]),
			"COLOR" => bin2hex($user["COLOR"]),
			"NICK" => htmlspecialchars($user["NICK"])
		];

		$this->id = $data;
	}
}
