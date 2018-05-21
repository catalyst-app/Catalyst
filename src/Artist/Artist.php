<?php

namespace Catalyst\Artist;

use \Catalyst\Database\{AbstractDatabaseModel, Column, Tables};
use \Catalyst\Database\Query\{InsertQuery, SelectQuery};
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Images\{Folders, HasImageTrait, Image};
use \Catalyst\Integrations\HasSocialChipsTrait;
use \Catalyst\Message\MessagableTrait;
use \Catalyst\Page\Navigation\Navbar;
use \Catalyst\Page\Values;
use \Catalyst\Tokens;
use \Catalyst\User\User;
use \InvalidArgumentException;

/**
 * Represents an artist in the database
 *
 * @method int getUserId()
 * @method void setUserId(int $userId)
 * @method string getToken()
 * @method void setToken(string $token)
 * @method string getName()
 * @method void setName(string $name)
 * @method null|string getUrl()
 * @method void setUrl(null|string $url)
 * @method string getDescription()
 * @method void setDescription(string $description)
 * @method string[][] getAllTos()
 * @method void setAllTos(string[][] $tos)
 * @method null|string getImagePath()
 * @method void setImagePath(null|string $imagePath)
 * @method string getColor()
 * @method void setColor(string $color)
 * @method bool getDeleted()
 * @method void setDeleted(bool $deleted)
 */
class Artist extends AbstractDatabaseModel {
	use HasImageTrait, HasSocialChipsTrait, MessagableTrait;

	/**
	 * @return array
	 */
	public static function getPrefetchColumns() : array {
		return [
			"USER_ID",
			"TOKEN",
			"NAME",
			"URL",
			"DESCRIPTION",
			"TOS",
			"IMG",
			"COLOR",
		];
	}

	/**
	 * Table in which all information is stored
	 * 
	 * @return string
	 */
	public static function getTable() : string {
		return Tables::ARTIST_PAGES;
	}

	/**
	 * The folder containing the image
	 * @return string
	 */
	public static function getImageFolder() : string {
		return Folders::ARTIST_IMAGE;
	}

	/**
	 * Initialize the image for the artist
	 */
	public function initializeImage() : void {
		$this->setImage(new Image(self::getImageFolder(), $this->getToken(), $this->getImagePath()));
	}

	/**
	 * Get the artist's current Terms of Service
	 * 
	 * @return string
	 */
	public function getCurrentTos() : string {
		$str = '';
		
		$str .= "*Effective as of ".$this->getAllTos()[0][0]."*";
		$str .= "\n";
		$str .= $this->getAllTos()[0][1];

		return $str;
	}

	/**
	 * Get the artist's current Terms of Service, without the date bit
	 * 
	 * @return string
	 */
	public function getCurrentTosWithoutDate() : string {
		return $this->getAllTos()[0][1];
	}

	/**
	 * Get the HTML for the dropdown in the navbar
	 * 
	 * @param int $bar
	 * @return string html
	 */
	public function getNavbarDropdown(int $bar) : string {
		if ($bar == Navbar::NAVBAR) {
			$str = "";
			$str .= $this->getImage()->getStrictCircleHtml(["valign"]); // valign needed to make it play nice
			$str .= htmlspecialchars($this->getName());
			return $str;
		} else {
			return "Artist Page";
		}
	}

	/**
	 * Get an ID from the URL, -1 if none
	 * 
	 * @return int
	 */
	public static function getIdFromUrl(string $url) : int {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));

		$whereClause = new WhereClause();

		$whereClause->addToClause([new Column("URL", self::getTable()), '=', $url]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DELETED", self::getTable()), '=', 0]);
		
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		if (count($stmt->getResult()) == 0) {
			return -1;
		}

		return $stmt->getResult()[0]["ID"];
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

	/**
	 * @return array values to fill upon deletion
	 */
	public static function getDeletedValues() : array {
		return [
			"NAME" => "Deleted artist page",
			"URL" => Tokens::generateDeletedArtistUrl(),
			"DESCRIPTION" => "Deleted artist page",
			"TOS" => json_encode([date("l, F jS, Y"), "Page deleted."]),
			"IMG" => null,
			"COLOR" => hex2bin(Values::DEFAULT_COLOR),
			"DELETED" => true,
		];
	}

	/**
	 * Also sets ARTIST_PAGE_ID on User = null
	 */
	public function additionalDeletion() : void {
		$user = new User($this->getUserId());
		$user->setArtistPageId(null);
	}

	/**
	 * Create an artist page
	 *
	 * @param array $values
	 * @return self
	 */
	public static function create(array $values) : self {
		// per array_merge docs:
		// If the input arrays have the same string keys, then the latter value
		//  for that key will overwrite the previous one
		$values = array_merge([
			"TOKEN" => Tokens::generateArtistToken(),
			"IMG" => null,
			"COLOR" => hex2bin(Values::DEFAULT_COLOR),
		], $values);

		$stmt = new InsertQuery();

		$stmt->setTable(self::getTable());

		foreach (["USER_ID", "TOKEN", "NAME", "URL", "DESCRIPTION", "TOS", "IMG", "COLOR"] as $column) {
			$stmt->addColumn(new Column($column, self::getTable()));
			$stmt->addValue($values[$column]);
		}

		$stmt->execute();

		$artist = new self($stmt->getResult(), $values);

		$user = new User($artist->getUserId());
		$user->setArtistPageId($artist->getId());

		return $artist;
	}

	/**
	 * @return array
	 */
	public static function getModifiableProperties() : array {
		return [
			"UserId" => ["USER_ID", null, null],
			"Token" => ["TOKEN", null, null],
			"Name" => ["NAME", null, null],
			"Url" => ["URL", null, null],
			"Description" => ["DESCRIPTION", null, null],
			"AllTos" => ["TOS", "json_decode", "json_encode"],
			"ImagePath" => ["IMG", null, null],
			"Color" => ["COLOR", "bin2hex", "hex2bin"],
			"Deleted" => ["DELETED", "boolval", null],
		];
	}
}
