<?php

namespace Catalyst\Artist;

class Artist {
	private $id;

	private $cache = [];

	public function __construct(int $id) {
		$stmt = $GLOBALS["dbh"]->prepare("
			SELECT
				`USER_ID`, `TOKEN`, `NAME`, `URL`, `DESCRIPTION`, `TOS`, `IMG`, `COLOR`
			FROM
				`".DB_TABLES["artist_pages"]."`
			WHERE
				`ID` = :ID;");
		$stmt->bindParam(":ID", $id);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			throw new \InvalidArgumentException("Artist ID ".$id." does not exist in the database.");
		}

		$results = $stmt->fetchAll();
		$stmt->closeCursor();

		$this->cache = [
			"USER_ID" => $results[0]["USER_ID"],
			"TOKEN" => $results[0]["TOKEN"],
			"NAME" => $results[0]["NAME"],
			"URL" => $results[0]["URL"],
			"DESCRIPTION" => $results[0]["DESCRIPTION"],
			"TOS" => $results[0]["TOS"],
			"IMG" => (is_null($results[0]["IMG"]) ? "default.png" : $results[0]["TOKEN"].$results[0]["IMG"]),
			"COLOR" => bin2hex($results[0]["COLOR"])
		];

		$this->id = $id;
	}

	public function getId() : int {
		return $this->id;
	}

	public function getUserId() : string {
		if (array_key_exists("USER_ID", $this->cache)) {
			return $this->cache["USER_ID"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `USER_ID` FROM `".DB_TABLES["artist_pages"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["USER_ID"] = $stmt->fetchAll()[0]["USER_ID"];

		$stmt->closeCursor();

		return $result;
	}

	public function getToken() : string {
		if (array_key_exists("TOKEN", $this->cache)) {
			return $this->cache["TOKEN"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `TOKEN` FROM `".DB_TABLES["artist_pages"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["TOKEN"] = $stmt->fetchAll()[0]["TOKEN"];

		$stmt->closeCursor();

		return $result;
	}

	public function getName() : string {
		if (array_key_exists("NAME", $this->cache)) {
			return $this->cache["NAME"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `NAME` FROM `".DB_TABLES["artist_pages"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["NAME"] = $stmt->fetchAll()[0]["NAME"];

		$stmt->closeCursor();

		return $result;
	}

	public function getDescription() : string {
		if (array_key_exists("DESCRIPTION", $this->cache)) {
			return $this->cache["DESCRIPTION"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `DESCRIPTION` FROM `".DB_TABLES["artist_pages"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["DESCRIPTION"] = $stmt->fetchAll()[0]["DESCRIPTION"];

		$stmt->closeCursor();

		return $result;
	}

	public function getTos() : string {
		if (array_key_exists("TOS", $this->cache)) {
			return $this->cache["TOS"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `TOS` FROM `".DB_TABLES["artist_pages"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["TOS"] = $stmt->fetchAll()[0]["TOS"];

		$stmt->closeCursor();

		return $result;
	}

	public function getUrl() : string {
		if (array_key_exists("URL", $this->cache)) {
			return $this->cache["URL"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `URL` FROM `".DB_TABLES["artist_pages"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["URL"] = $stmt->fetchAll()[0]["URL"];

		$stmt->closeCursor();

		return $result;
	}

	public function getImg() : string {
		if (array_key_exists("IMG", $this->cache)) {
			return $this->cache["IMG"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `IMG` FROM `".DB_TABLES["artist_pages"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["IMG"] = (is_null($results[0]["IMG"]) ? "default.png" : $this->getToken().$stmt->fetchAll()[0]["IMG"]);

		$stmt->closeCursor();

		return $result;
	}

	public function getColorHex() : string {
		if (array_key_exists("COLOR", $this->cache)) {
			return $this->cache["COLOR"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `COLOR` FROM `".DB_TABLES["artist_pages"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["COLOR"] = bin2hex($stmt->fetchAll()[0]["COLOR"]);

		$stmt->closeCursor();

		return $result;
	}

	public function getColor() : array {
		return \Catalyst\Color::getArrFromHex($this->getColorHex());
	}

	public function getPicturePath() : string {
		return ROOTDIR.\Catalyst\Form\FileUpload::FOLDERS[\Catalyst\Form\FileUpload::ARTIST_IMAGE]."/".$this->getImg();
	}

	public function getNavbarDropdown(int $bar) : string {
		if ($bar == \Catalyst\Page\Navigation\Navbar::NAVBAR) {
			return \Catalyst\Page\UniversalFunctions::getStrictCircleImageHTML($this->getPicturePath(), false, "valign").$this->getName();
		} else {
			return $this->getName();
		}
	}

	public static function getIdFromUrl(string $url) : int {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ID` FROM `".DB_TABLES["artist_pages"]."` WHERE `URL` = :URL AND `DELETED` = 0;");
		$stmt->bindParam(":URL", $url);
		$stmt->execute();

		if (!$stmt->rowCount()) {
			$stmt->closeCursor();
			return -1;
		}

		$id = $stmt->fetchAll()[0]["ID"];
		$stmt->closeCursor();
		return $id;
	}
}
