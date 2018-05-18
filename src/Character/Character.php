<?php

namespace Catalyst\Character;

use \Catalyst\Database\{AbstractDatabaseModel, Column, Tables};
use \Catalyst\Database\Query\{InsertQuery, MultiInsertQuery, SelectQuery};
use \Catalyst\Database\QueryAddition\{JoinClause, OrderByClause, WhereClause};
use \Catalyst\Images\{Folders, HasImageSetTrait, HasImageTrait, Image};
use \Catalyst\Page\Values;
use \Catalyst\User\User;
use \Catalyst\Tokens;
use \InvalidArgumentException;

/**
 * Represents a Character in the database
 *
 * @method string getName()
 * @method void setName(string $name)
 * @method string getColor()
 * @method void setColor(string $color)
 * @method bool isPublic()
 * @method void setPublic(bool $public)
 * @method int getOwnerId()
 * @method void setOwnerId(int $ownerId)
 * @method string getToken()
 * @method void setToken(string $token)
 * @method string getDescription()
 * @method void setDescription(string $description)
 */
class Character extends AbstractDatabaseModel {
	use HasImageTrait, HasImageSetTrait;

	// want your character here?  Submit a PR or donate!
	const PREDEFINED_CHARACTER_SHORT_URLS = [
		"fauxil" => "sv2j6qy",
		"lykai" => "fwce5ym",
		"toish" => "53h1ggw",
	];

	/**
	 * Get a character's ID from their token
	 */
	public static function getIdFromToken(string $token) : int {
		if (array_key_exists(strtolower($token), self::PREDEFINED_CHARACTER_SHORT_URLS)) {
			return self::getIdFromToken(self::PREDEFINED_CHARACTER_SHORT_URLS[strtolower($token)]);
		}

		if (!preg_match("/".Tokens::CHARACTER_TOKEN_REGEX."/", $token)) {
			return -1;
		}
		
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());
		
		$stmt->addColumn(new Column("ID", self::getTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("CHARACTER_TOKEN", self::getTable()), "=", $token]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DELETED", self::getTable()), "=", 0]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		if (empty($stmt->getResult())) {
			return -1;
		} else {
			return $stmt->getResult()[0]["ID"];
		}
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
	 * Get the character's owner
	 * 
	 * @return User
	 */
	public function getOwner() : User {
		return $this->getDataFromCallableOrCache("OWNER_OBJ", function() : User {
			return new User($this->getOwnerId());
		});
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
		if (User::isLoggedIn() && $_SESSION["user"]->getId() == $this->getOwnerId()) {
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
			$whereClause->addToClause([new Column("ARTIST_PAGE_ID", Tables::COMMISSIONS), '=', $aid]);
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

	/**
	 * Initialize the primary image
	 */
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

	/**
	 * Initializes the list of images for the character.  Can be empty
	 */
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

	/**
	 * Get an array of all character objects for the user
	 * 
	 * @param User $user
	 * @return self[]
	 */
	public static function getCharactersFromUser(User $user) : array {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));

		$whereClause = new WhereClause();

		$whereClause->addToClause([new Column("USER_ID", self::getTable()), '=', $user->getId()]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DELETED", self::getTable()), '=', 0]);

		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		$characters = [];

		foreach ($stmt->getResult() as $character) {
			$characters[] = new self($character["ID"]);
		}

		return $characters;
	}

	/**
	 * Get an array of all public character objects for the user
	 * 
	 * @param User $user
	 * @return self[]
	 */
	public static function getPublicCharactersFromUser(User $user) : array {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));

		$whereClause = new WhereClause();
		
		$whereClause->addToClause([new Column("USER_ID", self::getTable()), '=', $user->getId()]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DELETED", self::getTable()), '=', 0]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("PUBLIC", self::getTable()), '=', 1]);

		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		$characters = [];

		foreach ($stmt->getResult() as $character) {
			$characters[] = new self($character["ID"]);
		}

		return $characters;
	}

	/**
	 * Create a character
	 *
	 * @param array $values
	 * @return self
	 */
	public static function create(array $values) : self {
		// per array_merge docs:
		// If the input arrays have the same string keys, then the latter value
		//  for that key will overwrite the previous one
		$values = array_merge([
			"CHARACTER_TOKEN" => Tokens::generateCharacterToken(),
			"NAME" => "",
			"DESCRIPTION" => "",
			"COLOR" => hex2bin(Values::DEFAULT_COLOR),
			"PUBLIC" => 1,
			"_images" => [], // preload with an array of Image
			"_image_meta" => [], // preload with properly formatted array
		], $values);

		$stmt = new InsertQuery();

		$stmt->setTable(self::getTable());

		foreach (["USER_ID", "CHARACTER_TOKEN", "NAME", "DESCRIPTION", "COLOR", "PUBLIC"] as $column) {
			$stmt->addColumn(new Column($column, self::getTable()));
			$stmt->addValue($values[$column]);
		}

		$stmt->execute();

		$characterId = $stmt->getResult();

		if (!empty($values["_images"])) {
			$stmt = new MultiInsertQuery();

			$stmt->setTable(Tables::CHARACTER_IMAGES);

			$stmt->addColumn(new Column("CHARACTER_ID", Tables::CHARACTER_IMAGES));
			$stmt->addColumn(new Column("CAPTION", Tables::CHARACTER_IMAGES));
			$stmt->addColumn(new Column("CREDIT", Tables::CHARACTER_IMAGES));
			$stmt->addColumn(new Column("PATH", Tables::CHARACTER_IMAGES));
			$stmt->addColumn(new Column("NSFW", Tables::CHARACTER_IMAGES));
			$stmt->addColumn(new Column("SORT", Tables::CHARACTER_IMAGES));

			foreach ($values["_images"] as $image) {
				$stmt->addValue($characterId);
				$stmt->addValue($values["_image_meta"][$image->getUploadName()]["caption"]);
				$stmt->addValue($values["_image_meta"][$image->getUploadName()]["info"]);
				$stmt->addValue($image->getPath());
				$stmt->addValue($values["_image_meta"][$image->getUploadName()]["nsfw"] ? 1 : 0);
				$stmt->addValue($values["_image_meta"][$image->getUploadName()]["sort"]);
			}

			$stmt->execute();
		}

		return new self($characterId, $values);
	}

	/**
	 * Get deleted values for when a character is delet
	 * @return array
	 */
	public static function getDeletedValues() : array {
		return [
			// "CHARACTER_TOKEN" => "", ommitted
			"NAME" => "Deleted character",
			"DESCRIPTION" => "Deleted character",
			"COLOR" => hex2bin(Values::DEFAULT_COLOR),
			"PUBLIC" => false,
			"DELETED" => true,
		];
	}

	/**
	 * Get modifiable properties for the model
	 *
	 * 	"Name" => ["COLUMN_NAME", function($value) {return $out;}, function($newValue) {return $out;}]
	 * @return array
	 */
	public static function getModifiableProperties() : array {
		return [
			"Name" => ["FILE_TOKEN", null, null],
			"Color" => ["COLOR", "bin2hex", "hex2bin"],
			"Public" => ["PUBLIC", "boolval", null],
			"OwnerId" => ["USER_ID", null, null],
			"Token" => ["CHARACTER_TOKEN", null, null],
			"Description" => ["DESCRIPTION", null, null],
		];
	}
}
