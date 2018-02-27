<?php

namespace Catalyst\Character;

use \Catalyst\Database\{Column, DatabaseModelTrait, JoinClause, OrderByClause, SelectQuery, Tables, WhereClause};
use \Catalyst\Images\{Folders, HasImageSetTrait, HasImageTrait, Image};
use \Catalyst\User\User;
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

	/**
	 * Get the Character's ID
	 * 
	 * Specified in DatabaseModelTrait
	 * @return int
	 */
	public function getId() : int {
		return $this->id;
	}

	/**
	 * Get the table the object's data is stored in
	 * 
	 * Specified in DatabaseModelTrait
	 * @return string
	 */
	public static function getTable() : string {
		return Tables::CHARACTERS;
	}

	/**
	 * Get the character's name
	 * 
	 * @return string
	 */
	public function getName() : string {
		if (array_key_exists("NAME", $this->cache)) {
			return $this->cache["NAME"];
		}
		
		return $this->cache["NAME"] = $this->getColumnFromDatabase("NAME");
	}

	/**
	 * Get the color as a 6-character hex code
	 * 
	 * @return string
	 */
	public function getColor() : string {
		if (array_key_exists("COLOR", $this->cache)) {
			return $this->cache["COLOR"];
		}

		return $this->cache["COLOR"] = bin2hex($this->getColumnFromDatabase("COLOR"));
	}

	/**
	 * If the character is public
	 * 
	 * @return bool
	 */
	public function isPublic() : bool {
		if (array_key_exists("PUBLIC", $this->cache)) {
			return $this->cache["PUBLIC"];
		}

		return $this->cache["PUBLIC"] = (bool)($this->getColumnFromDatabase("PUBLIC"));
	}

	/**
	 * Get the character's owner's id
	 * 
	 * @return int
	 */
	public function getOwnerId() : int {
		if (array_key_exists("USER_ID", $this->cache)) {
			return $this->cache["USER_ID"];
		}

		return $this->cache["USER_ID"] = $this->getColumnFromDatabase("USER_ID");
	}

	/**
	 * Get the character's owner
	 * 
	 * @return User
	 */
	public function getOwner() : User {
		if (array_key_exists("USER", $this->cache)) {
			return $this->cache["USER"];
		}

		// cache for db calls
		return $this->cache["USER"] = new User($this->getOwnerId());
	}

	/**
	 * Get the character's unique token
	 * 
	 * @return string
	 */
	public function getToken() : string {
		if (array_key_exists("CHARACTER_TOKEN", $this->cache)) {
			return $this->cache["CHARACTER_TOKEN"];
		}

		return $this->cache["CHARACTER_TOKEN"] = $this->getColumnFromDatabase("CHARACTER_TOKEN");
	}

	/**
	 * Get the character's description, as markdown
	 * 
	 * @return string
	 */
	public function getDescription() : string {
		if (array_key_exists("DESCRIPTION", $this->cache)) {
			return $this->cache["DESCRIPTION"];
		}

		return $this->cache["DESCRIPTION"] = $this->getColumnFromDatabase("DESCRIPTION");
	}

	/**
	 * If the character is visible to the current user
	 * 
	 * @return bool
	 */
	public function visibleToMe() : bool {
		if ($this->isPublic()) {
			return true;
		}
		if (\Catalyst\User\User::isLoggedIn() && $_SESSION["user"]->getId() == $this->getOwnerId()) {
			return true;
		}

		if (User::isLoggedIn() && $_SESSION["user"]->isArtist()) {
			$aid = $_SESSION["user"]->getArtistPageId();

			$stmt = new SelectQuery();

			$stmt->setTable(Tables::COMMISSIONS);

			$stmt->addColumn(new Column("ID", Tables::COMMISSIONS));

			$joinClause = new JoinClause();
			$joinClause->setJoinTable(Tables::COMMISSION_TYPES);
			$joinClause->setLeftColumn(new Column("COMMISSION_TYPE_ID", Tables::COMMISSIONS));
			$joinClause->setRightColumn(new Column("ID", Tables::COMMISSION_TYPES));
			$stmt->addAdditionalCapability($joinClause);
			
			$whereClause = new WhereClause();
			$whereClause->addToClause([new Column("ARTIST_PAGE_ID", Tables::ARTIST_PAGES), '=', $aid]);
			$whereClause->addToClause(WhereClause::AND);
			$whereClause->addToClause([new Column("CHARACTER_ID_ARRAY", Tables::COMMISSIONS), 'LIKE', "%\"".$this->getId()."\"%"]);
			$stmt->addAdditionalCapability($whereClause);

			$stmt->execute();

			if (count($stmt->getResult())) {
				return true;
			}
		}

		return false;
	}

	public function initializeImage() : void {
		if (count($this->getImageSet()) === 0) {
			$this->setImage(new Image(
				Folders::CHARACTER_IMAGE,
				$this->getToken(),
				null
			));
		} else {
			$this->setImage($this->getImageSet()[0]);
		}
	}

	public function initializeImageSet() : void {
		$images = [];

		$stmt = new SelectQuery();

		$stmt->setTable(Tables::CHARACTER_IMAGES);

		$stmt->addColumn(new Column("CAPTION", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("CREDIT", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("PATH", Tables::CHARACTER_IMAGES));
		$stmt->addColumn(new Column("NSFW", Tables::CHARACTER_IMAGES));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("CHARACTER_ID", Tables::CHARACTER_IMAGES), '=', $this->id]);
		$stmt->addAdditionalCapability($whereClause);

		$orderByClause = new OrderByClause();
		$orderByClause->setColumn(new Column("SORT", Tables::CHARACTER_IMAGES));
		$orderByClause->setOrder("ASC");
		$stmt->addAdditionalCapability($orderByClause);

		$stmt->execute();

		foreach ($stmt->getResult() as $row) {
			$images[] = new Image(
				Folders::CHARACTER_IMAGE, 
				$this->getToken(), 
				$row["PATH"], 
				$row["NSFW"], 
				trim($row["CAPTION"].($row["CREDIT"] ? ("\n**Artist:** ".$row["CREDIT"]) : ''))
			);
		}

		$this->setImageSet($images);
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
}
