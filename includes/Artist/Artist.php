<?php

namespace Catalyst\Artist;

use \Catalyst\Database\{Column, DatabaseModelTrait, Tables};
use \Catalyst\Database\Query\{SelectQuery};
use \Catalyst\Database\QueryAddition\{WhereClause};
use \Catalyst\Images\{Folders, HasImageTrait, Image};
use \Catalyst\Integrations\HasSocialChipsTrait;
use \Catalyst\Message\MessagableTrait;
use \Catalyst\Page\Navigation\Navbar;
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
			"TOS" => json_decode($results[0]["TOS"]),
			"IMG" => $results[0]["IMG"],
			"COLOR" => bin2hex($results[0]["COLOR"])
		];

		$this->id = $id;
	}

	/**
	 * Get the object's unique ID
	 * 
	 * @return int
	 */
	public function getId() : int {
		return $this->id;
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
	 * Initialize the image for the artist
	 */
	public function initializeImage() : void {
		$this->setImage(new Image(Folders::ARTIST_IMAGE, $this->getToken(), $this->getImagePath()));
	}

	/**
	 * Get the page's user id
	 * 
	 * @return int
	 */
	public function getUserId() : int {
		if (array_key_exists("USER_ID", $this->cache)) {
			return $this->cache["USER_ID"];
		}
		
		return $this->cache["USER_ID"] = $this->getColumnFromDatabase("USER_ID");
	}

	/**
	 * Get the artist's token
	 * 
	 * @return string
	 */
	public function getToken() : string {
		if (array_key_exists("TOKEN", $this->cache)) {
			return $this->cache["TOKEN"];
		}
		
		return $this->cache["TOKEN"] = $this->getColumnFromDatabase("TOKEN");
	}

	/**
	 * Get the artist's name
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
	 * Get the artist's description
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
	 * Get the artist's current Terms of Service
	 * 
	 * @return string
	 */
	public function getCurrentTos() : string {
		if (array_key_exists("TOS", $this->cache)) {
			$str = '';

			$str .= "*Effective as of ".$this->cache["TOS"][0][0]."*";
			$str .= "\n";
			$str .= $this->cache["TOS"][0][1];

			return $str;
		}

		$this->cache["TOS"] = json_decode($this->getColumnFromDatabase("TOS"));

		$str = '';
		
		$str .= "*Effective as of ".$this->cache["TOS"][0][0]."*";
		$str .= "\n";
		$str .= $this->cache["TOS"][0][1];

		return $str;
	}

	/**
	 * Get the artist's Terms of ServiceS
	 * 
	 * @return string[][]
	 */
	public function getAllTos() : array {
		if (array_key_exists("TOS", $this->cache)) {
			return $this->cache["TOS"];
		}

		return $this->cache["TOS"] = json_decode($this->getColumnFromDatabase("TOS"));
	}

	/**
	 * Get the page's URL
	 * 
	 * @return string
	 */
	public function getUrl() : string {
		if (array_key_exists("URL", $this->cache)) {
			return $this->cache["URL"];
		}

		return $this->cache["URL"] = $this->getColumnFromDatabase("URL");
	}

	/**
	 * Get the path to the artist's image, WITHOUT token
	 * 
	 * @return null|string
	 */
	public function getImagePath() : ?string {
		if (array_key_exists("IMG", $this->cache)) {
			return $this->cache["IMG"];
		}

		return $this->cache["IMG"] = $this->getColumnFromDatabase("IMG");
	}

	/**
	 * Color of the artist page
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
}
