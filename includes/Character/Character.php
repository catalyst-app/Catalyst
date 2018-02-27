<?php

namespace Catalyst\Character;

use \Catalyst\Database\{Column, DatabaseModelTrait, JoinClause, OrderByClause, SelectQuery, Tables, WhereClause};
use \Catalyst\Images\{Folders, HasImageSetTrait, HasImageTrait, Image};
use \InvalidArgumentException;

/**
 * Represents a Character in the database
 */
class Character {
	use DatabaseModelTrait, HasImageTrait, HasImageSetTrait;

	/**
	 * The character's ID
	 * @var int
	 */
	private $id;

	/**
	 * This is used as not to repeatedly hammer the database
	 * @var array
	 */
	private $cache = [];

	/**
	 * Create a new Character object, DELETED or not
	 * 
	 * @param int $id
	 * @throws InvalidArgumentException bad ID passed
	 */
	public function __construct(int $id) {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("USER_ID", self::getTable()));
		$stmt->addColumn(new Column("CHARACTER_TOKEN", self::getTable()));
		$stmt->addColumn(new Column("NAME", self::getTable()));
		$stmt->addColumn(new Column("DESCRIPTION", self::getTable()));
		$stmt->addColumn(new Column("COLOR", self::getTable()));
		$stmt->addColumn(new Column("PUBLIC", self::getTable()));

		$stmt->addColumn(new Column("CAPTION", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("CREDIT", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("PATH", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("NSFW", Tables::CHARACTER_IMAGES));

		$joinClause = new JoinClause();

		$joinClause->setType(JoinClause::LEFT);

		$joinClause->setJoinTable(Tables::CHARACTER_IMAGES);

		$joinClause->setLeftColumn(new Column("ID", self::getTable()));
		$joinClause->setRightColumn(new Column("CHARACTER_ID", Tables::CHARACTER_IMAGES));

		$stmt->addAdditionalCapability($joinClause);

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("ID", self::getTable()), '=', $id]);
		$stmt->addAdditionalCapability($whereClause);

		$orderByClause = new OrderByClause();
		$orderByClause->setColumn(new Column("SORT", Tables::CHARACTER_IMAGES));
		$orderByClause->setOrder("ASC");
		$stmt->addAdditionalCapability($orderByClause);

		$stmt->execute();

		$results = $stmt->getResult();

		if (!count($results)) {
			throw new InvalidArgumentException("Character ID ".$id." does not exist in the database.");
		}

		$images = [];

		for ($i=0; $i < count($results); $i++) { 
			if (is_null($results[$i]["PATH"])) {
				break;
			}
			$images[] = new Image(
				Folders::CHARACTER_IMAGE,
				$results[$i]["CHARACTER_TOKEN"],
				$results[$i]["PATH"],
				(bool)$results[$i]["NSFW"],
				trim($results[$i]["CAPTION"].($results[$i]["CREDIT"] ? ("\n**Artist:** ".$results[$i]["CREDIT"]) : ''))
			);
		}

		$this->cache = [
			"USER_ID" => $results[0]["USER_ID"],
			"USER" => new \Catalyst\User\User($results[0]["USER_ID"]),
			"CHARACTER_TOKEN" => $results[0]["CHARACTER_TOKEN"],
			"NAME" => $results[0]["NAME"],
			"DESCRIPTION" => $results[0]["DESCRIPTION"],
			"COLOR" => bin2hex($results[0]["COLOR"]),
			"PUBLIC" => (bool)($results[0]["PUBLIC"])
		];

		$this->setImageSet($images);

		$this->id = $id;
	}

	public static function getIdFromToken(string $token) : int {
		if (!preg_match("/".\Catalyst\Tokens::CHARACTER_TOKEN_REGEX."/", $token)) {
			return -1;
		}
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ID` FROM `".DB_TABLES["characters"]."` WHERE `CHARACTER_TOKEN` = :CHARACTER_TOKEN AND `DELETED` = 0;");
		$stmt->bindParam(":CHARACTER_TOKEN", $token);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			return -1;
		} else {
			$result = $stmt->fetchAll()[0]["ID"];
			$stmt->closeCursor();
			return $result;
		}
	}

	public function getId() : int {
		return $this->id;
	}

	public static function getTable() : string {
		return Tables::CHARACTERS;
	}

	public function getName() : string {
		if (array_key_exists("NAME", $this->cache)) {
			return $this->cache["NAME"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `NAME` FROM `".DB_TABLES["characters"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["NAME"] = $stmt->fetchAll()[0]["NAME"];

		$stmt->closeCursor();

		return $result;
	}

	public function getColor() : string {
		if (array_key_exists("COLOR", $this->cache)) {
			return $this->cache["COLOR"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `COLOR` FROM `".DB_TABLES["characters"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["COLOR"] = bin2hex($stmt->fetchAll()[0]["COLOR"]);

		$stmt->closeCursor();

		return $result;
	}

	public function isPublic() : bool {
		if (array_key_exists("PUBLIC", $this->cache)) {
			return $this->cache["PUBLIC"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `PUBLIC` FROM `".DB_TABLES["characters"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["PUBLIC"] = (bool)($stmt->fetchAll()[0]["PUBLIC"]);

		$stmt->closeCursor();

		return $result;
	}

	public function getOwnerId() : int {
		if (array_key_exists("USER_ID", $this->cache)) {
			return $this->cache["USER_ID"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `USER_ID` FROM `".DB_TABLES["characters"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["USER_ID"] = ($stmt->fetchAll()[0]["USER_ID"]);

		$stmt->closeCursor();

		return $result;
	}

	public function getOwner() : \Catalyst\User\User {
		if (array_key_exists("USER", $this->cache)) {
			return $this->cache["USER"];
		}

		$result = $this->cache["USER"] = new \Catalyst\User\User($this->getOwnerId());

		return $result;
	}

	public function getToken() : string {
		if (array_key_exists("CHARACTER_TOKEN", $this->cache)) {
			return $this->cache["CHARACTER_TOKEN"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `CHARACTER_TOKEN` FROM `".DB_TABLES["characters"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["CHARACTER_TOKEN"] = ($stmt->fetchAll()[0]["CHARACTER_TOKEN"]);

		$stmt->closeCursor();

		return $result;
	}

	public function getDescription() : string {
		if (array_key_exists("DESCRIPTION", $this->cache)) {
			return $this->cache["DESCRIPTION"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `DESCRIPTION` FROM `".DB_TABLES["characters"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["DESCRIPTION"] = ($stmt->fetchAll()[0]["DESCRIPTION"]);

		$stmt->closeCursor();

		return $result;
	}

	public function getImages() : array {
		if (array_key_exists("IMAGES", $this->cache)) {
			return $this->cache["IMAGES"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `CAPTION`, `PATH`, `NSFW`, `PRIMARY` FROM `".DB_TABLES["character_images"]."` WHERE `CHARACTER_ID` = :CHARACTER_ID ORDER BY `SORT` ASC;");
		$stmt->bindParam(":CHARACTER_ID", $this->id);
		$stmt->execute();

		$result = $this->cache["IMAGES"] = (
				$stmt->rowCount() == 0
			?
				[[null, "", false, true]]
			:
				array_map(function($in) {
					return [$this->getToken().$in["PATH"], $in["CAPTION"], (bool)$in["NSFW"], (bool)$in["PRIMARY"]];
				}, $stmt->fetchAll())
		);

		$stmt->closeCursor();

		return $result;
	}

	public function getPrimaryImage() : array {
		$imagesThatArePrimary = array_values(array_filter($this->getImages(), function($in) { return $in[3]; }));
		return (count($imagesThatArePrimary) ? $imagesThatArePrimary[0] : ["default.png", "", false, true]);
	}

	public function getPrimaryImagePath() : ?string {
		$imagesThatArePrimary = array_values(array_filter($this->getImages(), function($in) { return $in[3]; }));
		if (count($imagesThatArePrimary)) {
			return str_replace($this->getToken(), "", $imagesThatArePrimary[0][0]); // TODO: REMOVE THE STR REPLACE
		} else {
			return null;
		}
	}

	public function isPrimaryImageNsfw() : bool {
		$imagesThatArePrimary = array_values(array_filter($this->getImages(), function($in) { return $in[3]; }));
		if (count($imagesThatArePrimary)) {
			return $imagesThatArePrimary[0][2];
		} else {
			return false;
		}
	}

	public function visibleToMe() : bool {
		if ($this->isPublic()) {
			return true;
		}
		if (\Catalyst\User\User::isLoggedIn() && $_SESSION["user"]->getId() == $this->getOwnerId()) {
			return true;
		}

		if (\Catalyst\User\User::isLoggedIn() && $_SESSION["user"]->isArtist()) {
			$aid = $_SESSION["user"]->getArtistPageId();

			
		}

		return false;
	}

	public static function getCharactersFromUser(\Catalyst\User\User $user) : array {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ID` FROM `".DB_TABLES["characters"]."` WHERE `USER_ID` = :USER_ID AND `DELETED` = 0 ORDER BY `ID` DESC;");
		$uid = $user->getId();
		$stmt->bindParam(":USER_ID", $uid);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			return [];
		}

		$arr = $stmt->fetchAll();
		$stmt->closeCursor();
		return array_map(function($in) {
			return new self($in["ID"]);
		}, $arr);
	}

	public static function getPublicCharactersFromUser(\Catalyst\User\User $user) : array {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ID` FROM `".DB_TABLES["characters"]."` WHERE `USER_ID` = :USER_ID AND `PUBLIC` = 1 AND `DELETED` = 0 ORDER BY `ID` DESC;");
		$uid = $user->getId();
		$stmt->bindParam(":USER_ID", $uid);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			return [];
		}

		$arr = $stmt->fetchAll();
		$stmt->closeCursor();
		return array_map(function($in) {
			return new self($in["ID"]);
		}, $arr);
	}

	public function initializeImage() : void {
		$this->setImage(new Image(Folders::CHARACTER_IMAGE, $this->getToken(), $this->getPrimaryImagePath(), $this->isPrimaryImageNsfw()));
	}

	public function initializeImageSet() : void {
		$images = [];

		$stmt = new SelectQuery();

		$stmt->setTable(Tables::CHARACTER_IMAGES);

		$stmt->addColumn(new Column("CAPTION", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("CREDIT", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("PATH", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("NSFW", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("PRIMARY", Tables::CHARACTER_IMAGES));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("CHARACTER_ID", Tables::CHARACTER_IMAGES), '=', $this->id]);
		$stmt->addAdditionalCapability($whereClause);

		$orderByClause = new OrderByClause();
		$orderByClause->setColumn(new Column("SORT", Tables::CHARACTER_IMAGES));
		$orderByClause->setOrder("ASC");
		$stmt->addAdditionalCapability($orderByClause);

		$stmt->execute();

		foreach ($stmt->getResult() as $row) {
			$images[] = new Image(Folders::CHARACTER_IMAGE, $this->getToken(), $row["PATH"], $row["NSFW"], trim($row["CAPTION"].($row["CREDIT"] ? ("\n**Artist:** ".$row["CREDIT"]) : '')));
		}

		$this->setImageSet($images);
	}
}
