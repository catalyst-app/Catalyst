<?php

namespace Catalyst\Artist;

use \Catalyst\Database\{Column, DatabaseModelTrait, Tables};
use \Catalyst\Database\Query\{SelectQuery};
use \Catalyst\Database\QueryAddition\{WhereClause};
use \Catalyst\Images\{Folders, HasImageTrait, Image};
use \Catalyst\Integrations\HasSocialChipsTrait;
use \Catalyst\Message\MessagableTrait;
use \InvalidArgumentException;

/**
 * Represents an artist in the database
 */
class Artist {
	use DatabaseModelTrait, HasImageTrait, HasSocialChipsTrait, MessagableTrait;

	/**
	 * Unique ID for the artist
	 */
	private $id;

	/**
	 * Used as not to hammer the database
	 */
	private $cache = [];

	/**
	 * Create a new artist object
	 * 
	 * @param int $id
	 */
	public function __construct(int $id) {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("USER_ID", self::getTable()));
		$stmt->addColumn(new Column("TOKEN", self::getTable()));
		$stmt->addColumn(new Column("NAME", self::getTable()));
		$stmt->addColumn(new Column("URL", self::getTable()));
		$stmt->addColumn(new Column("DESCRIPTION", self::getTable()));
		$stmt->addColumn(new Column("TOS", self::getTable()));
		$stmt->addColumn(new Column("IMG", self::getTable()));
		$stmt->addColumn(new Column("COLOR", self::getTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("ID", self::getTable()), '=', $id]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		$results = $stmt->getResult();

		if (!count($results)) {
			throw new InvalidArgumentException("Artist ID ".$id." does not exist in the database.");
		}

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

	public static function getTable() : string {
		return Tables::ARTIST_PAGES;
	}

	public function initializeImage() : void {
		$this->setImage(new Image(Folders::ARTIST_IMAGE, $this->getToken(), $this->getImg()));
	}

	public function getUserId() : int {
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

		$img = $stmt->fetchAll()[0]["IMG"];

		$result = $this->cache["IMG"] = $img;

		$stmt->closeCursor();

		return $result;
	}

	public function getColor() : string {
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

	/**
	 * Get the table used to store a Artist's social media items
	 * 
	 * @return string
	 */
	public function getSocialChipTable() : string {
		return Tables::ARTIST_SOCIAL_MEDIA;
	}

	/**
	 * Get the column which is a foreign key to $this->getId()
	 * 
	 * @return string
	 */
	public function getSocialChipIdColumn() : string {
		return "ARTIST_ID";
	}

	/**
	 * Part of IsMessagableTrait
	 * 
	 * @return string URL, relative to ROOTDIR/Message/New/, that can be used for messaging
	 */
	public function getMessageUrlPath() : string {
		return 'Artist/'.$this->getUrl();
	}

	/**
	 * Get a friendly name for the object, part of IsMessagableTrait
	 * 
	 * @return string The Artist's name
	 */
	public function getFriendlyName() : string {
		return $this->getName();
	}
}
