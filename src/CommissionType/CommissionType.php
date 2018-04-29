<?php

namespace Catalyst\CommissionType;

use \Catalyst\Artist\Artist;
use \Catalyst\Database\{Column, DatabaseModelTrait, Tables};
use \Catalyst\Database\Query\{SelectQuery};
use \Catalyst\Database\QueryAddition\{JoinClause, OrderByClause, WhereClause};
use \Catalyst\Images\{Folders, HasImageSetTrait, HasImageTrait, Image};
use \InvalidArgumentException;

/**
 * Represents a commision type
 */
class CommissionType {
	use DatabaseModelTrait, HasImageTrait, HasImageSetTrait;

	const QUICK_TOGGLE_BUTTONS = [
		["visible", "isVisible"],
		["quotes", "isAcceptingQuotes"],
		["requests", "isAcceptingRequests"],
		["trades", "isAcceptingTrades"],
		["commissions", "isAcceptingCommissions"],
	];

	/**
	 * The user's ID in the database
	 * @var int
	 */
	private $id;

	/**
	 * This is used as not to repeatedly hammer the database
	 * @var array
	 */
	private $cache = [];

	/**
	 * Create a new commission type object
	 * 
	 * @param int $id
	 */
	public function __construct(int $id) {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ARTIST_PAGE_ID", self::getTable()));
		$stmt->addColumn(new Column("TOKEN", self::getTable()));
		$stmt->addColumn(new Column("NAME", self::getTable()));
		$stmt->addColumn(new Column("BLURB", self::getTable()));
		$stmt->addColumn(new Column("DESCRIPTION", self::getTable()));
		$stmt->addColumn(new Column("SORT", self::getTable()));
		$stmt->addColumn(new Column("BASE_COST", self::getTable()));
		$stmt->addColumn(new Column("BASE_USD_COST", self::getTable()));
		$stmt->addColumn(new Column("ATTRS", self::getTable()));
		$stmt->addColumn(new Column("ACCEPTING_QUOTES", self::getTable()));
		$stmt->addColumn(new Column("ACCEPTING_REQUESTS", self::getTable()));
		$stmt->addColumn(new Column("ACCEPTING_TRADES", self::getTable()));
		$stmt->addColumn(new Column("ACCEPTING_COMMISSIONS", self::getTable()));
		$stmt->addColumn(new Column("VISIBLE", self::getTable()));

		$stmt->addColumn(new Column("CAPTION", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("COMMISSIONER", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("PATH", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("NSFW", Tables::COMMISSION_TYPE_IMAGES));

		$joinClause = new JoinClause();
		$joinClause->setType(JoinClause::LEFT);
		$joinClause->setJoinTable(Tables::COMMISSION_TYPE_IMAGES);
		$joinClause->setLeftColumn(new Column("ID", self::getTable()));
		$joinClause->setRightColumn(new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addAdditionalCapability($joinClause);

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("ID", self::getTable()), '=', $id]);
		$stmt->addAdditionalCapability($whereClause);

		$orderByClause = new OrderByClause();
		$orderByClause->setColumn(new Column("SORT", Tables::COMMISSION_TYPE_IMAGES));
		$orderByClause->setOrder("ASC");
		$stmt->addAdditionalCapability($orderByClause);

		$stmt->execute();

		$results = $stmt->getResult();

		if (count($results) == 0) {
			throw new InvalidArgumentException("Commission type ID ".$id." does not exist in the database.");
		}

		$images = [];

		for ($i=0; $i < count($results); $i++) { 
			if (is_null($results[$i]["PATH"])) {
				break;
			}
			$images[] = new Image(
				Folders::COMMISSION_TYPE_IMAGE,
				$results[$i]["TOKEN"],
				$results[$i]["PATH"],
				(bool)$results[$i]["NSFW"],
				trim($results[$i]["CAPTION"].($results[$i]["COMMISSIONER"] ? ("\n**Client:** ".$results[$i]["COMMISSIONER"]) : ''))
			);
		}

		$this->cache = [
			"ARTIST_PAGE_ID" => $results[0]["ARTIST_PAGE_ID"],
			"TOKEN" => $results[0]["TOKEN"],
			"NAME" => $results[0]["NAME"],
			"BLURB" => $results[0]["BLURB"],
			"DESCRIPTION" => $results[0]["DESCRIPTION"],
			"SORT" => (int)$results[0]["SORT"],
			"BASE_COST" => $results[0]["BASE_COST"],
			"BASE_USD_COST" => (float)$results[0]["BASE_USD_COST"],
			"ATTRS" => CommissionTypeAttribute::getObjectsFromString($results[0]["ATTRS"]),
			"ACCEPTING_QUOTES" => (bool)$results[0]["ACCEPTING_QUOTES"],
			"ACCEPTING_REQUESTS" => (bool)$results[0]["ACCEPTING_REQUESTS"],
			"ACCEPTING_TRADES" => (bool)$results[0]["ACCEPTING_TRADES"],
			"ACCEPTING_COMMISSIONS" => (bool)$results[0]["ACCEPTING_COMMISSIONS"],
			"VISIBLE" => (bool)$results[0]["VISIBLE"],
		];

		$this->setImageSet($images);

		$this->id = $id;
	}

	/**
	 * Get ID from token, if exists
	 * 
	 * @param string $token commission type token
	 * @return int ID if exists, -1 if not
	 */
	public static function getIdFromToken(string $token) : int {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("TOKEN", self::getTable()), '=', $token]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DELETED", self::getTable()), '=', 0]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("VISIBLE", self::getTable()), '=', 0]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		$result = $stmt->getResult();

		if (count($result) == 0) {
			return -1;
		}

		return $result[0]["ID"];
	}

	/**
	 * Get the commission type's ID
	 * 
	 * @return int
	 */
	public function getId() : int {
		return $this->id;
	}

	/**
	 * Get the table for the object, as specified in DatabaseModelTrait
	 *
	 * @return string
	 */
	public static function getTable() : string {
		return Tables::COMMISSION_TYPES;
	}

	/**
	 * Get the artist page's ID
	 *
	 * @return int
	 */
	public function getArtistPageId() : int {
		if (array_key_exists("ARTIST_PAGE_ID", $this->cache)) {
			return $this->cache["ARTIST_PAGE_ID"];
		}
		
		return $this->cache["ARTIST_PAGE_ID"] = $this->getColumnFromDatabase("ARTIST_PAGE_ID");
	}

	/**
	 * Get the artist's page as an object
	 *
	 * @return Artist
	 */
	public function getArtistPage() : Artist {
		if (array_key_exists("ARTIST_PAGE", $this->cache)) {
			return $this->cache["ARTIST_PAGE"];
		}
		
		return $this->cache["ARTIST_PAGE"] = new Artist($this->getArtistPageId());
	}

	/**
	 * @return string
	 */
	public function getToken() : string {
		if (array_key_exists("TOKEN", $this->cache)) {
			return $this->cache["TOKEN"];
		}

		return $this->cache["TOKEN"] = $this->getColumnFromDatabase("TOKEN");
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		if (array_key_exists("NAME", $this->cache)) {
			return $this->cache["NAME"];
		}

		return $this->cache["NAME"] = $this->getColumnFromDatabase("NAME");
	}

	/**
	 * @return string
	 */
	public function getBlurb() : string {
		if (array_key_exists("BLURB", $this->cache)) {
			return $this->cache["BLURB"];
		}

		return $this->cache["BLURB"] = $this->getColumnFromDatabase("BLURB");
	}

	/**
	 * @return string
	 */
	public function getDescription() : string {
		if (array_key_exists("DESCRIPTION", $this->cache)) {
			return $this->cache["DESCRIPTION"];
		}

		return $this->cache["DESCRIPTION"] = $this->getColumnFromDatabase("DESCRIPTION");
	}

	/**
	 * @return int
	 */
	public function getSort() : int {
		if (array_key_exists("SORT", $this->cache)) {
			return $this->cache["SORT"];
		}

		return $this->cache["SORT"] = (int)$this->getColumnFromDatabase("SORT");
	}

	/**
	 * @return string
	 */
	public function getBaseCost() : string {
		if (array_key_exists("BASE_COST", $this->cache)) {
			return $this->cache["BASE_COST"];
		}

		return $this->cache["BASE_COST"] = $this->getColumnFromDatabase("BASE_COST");
	}

	/**
	 * @return float
	 */
	public function getBaseUsdCost() : float {
		if (array_key_exists("BASE_USD_COST", $this->cache)) {
			return $this->cache["BASE_USD_COST"];
		}

		return $this->cache["BASE_USD_COST"] = (float)$this->getColumnFromDatabase("BASE_USD_COST");
	}

	/**
	 * @return CommissionTypeAttribute[]
	 */
	public function getAttributes() : array {
		if (array_key_exists("ATTRS", $this->cache)) {
			return $this->cache["ATTRS"];
		}

		return $this->cache["ATTRS"] = CommissionTypeAttribute::getObjectsFromString($this->getColumnFromDatabase("ATTRS"));
	}

	/**
	 * @return bool
	 */
	public function isAcceptingQuotes() : bool {
		if (array_key_exists("ACCEPTING_QUOTES", $this->cache)) {
			return $this->cache["ACCEPTING_QUOTES"];
		}

		return $this->cache["ACCEPTING_QUOTES"] = (bool)$this->getColumnFromDatabase("ACCEPTING_QUOTES");
	}

	/**
	 * @return bool
	 */
	public function isAcceptingRequests() : bool {
		if (array_key_exists("ACCEPTING_REQUESTS", $this->cache)) {
			return $this->cache["ACCEPTING_REQUESTS"];
		}

		return $this->cache["ACCEPTING_REQUESTS"] = (bool)$this->getColumnFromDatabase("ACCEPTING_REQUESTS");
	}

	/**
	 * @return bool
	 */
	public function isAcceptingTrades() : bool {
		if (array_key_exists("ACCEPTING_TRADES", $this->cache)) {
			return $this->cache["ACCEPTING_TRADES"];
		}

		return $this->cache["ACCEPTING_TRADES"] = (bool)$this->getColumnFromDatabase("ACCEPTING_TRADES");
	}

	/**
	 * @return bool
	 */
	public function isAcceptingCommissions() : bool {
		if (array_key_exists("ACCEPTING_COMMISSIONS", $this->cache)) {
			return $this->cache["ACCEPTING_COMMISSIONS"];
		}

		return $this->cache["ACCEPTING_COMMISSIONS"] = (bool)$this->getColumnFromDatabase("ACCEPTING_COMMISSIONS");
	}

	/**
	 * @return bool
	 */
	public function isVisible() : bool {
		if (array_key_exists("VISIBLE", $this->cache)) {
			return $this->cache["VISIBLE"];
		}

		return $this->cache["VISIBLE"] = (bool)$this->getColumnFromDatabase("VISIBLE");
	}

	/**
	 * Returns an array of CommissionTypeModifierGroup
	 * 
	 * @return CommissionTypeModifierGroup[]
	 */
	public function getModifiers() : array {
		if (array_key_exists("MODIFIERS", $this->cache)) {
			return $this->cache["MODIFIERS"];
		}

		$stmt = new SelectQuery();

		$stmt->setTable(Tables::COMMISSION_TYPE_MODIFIERS);

		$stmt->addColumn(new Column("ID", Tables::COMMISSION_TYPE_MODIFIERS));
		$stmt->addColumn(new Column("NAME", Tables::COMMISSION_TYPE_MODIFIERS));
		$stmt->addColumn(new Column("PRICE", Tables::COMMISSION_TYPE_MODIFIERS));
		$stmt->addColumn(new Column("USDEQ", Tables::COMMISSION_TYPE_MODIFIERS));
		$stmt->addColumn(new Column("GROUP", Tables::COMMISSION_TYPE_MODIFIERS));
		$stmt->addColumn(new Column("MULTIPLE", Tables::COMMISSION_TYPE_MODIFIERS));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_MODIFIERS), '=', $this->getId()]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DELETED", Tables::COMMISSION_TYPE_MODIFIERS), '=', 0]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		$rawModifiers = $stmt->getResult();
		// will be keyed by GROUP
		$modifiers = [];

		foreach ($rawModifiers as $modifier) {
			if (!array_key_exists($modifier["GROUP"], $modifiers)) {
				$modifiers[$modifier["GROUP"]] = new CommissionTypeModifierGroup($modifier["GROUP"]);
			}

			$modifierObject = new CommissionTypeModifier($modifier["ID"], $modifier["NAME"], $modifier["PRICE"], (float)$modifier["USDEQ"]);
			$modifiers[$modifier["GROUP"]]->addModifier($modifierObject);
		}

		return $this->cache["MODIFIERS"] = array_values($modifiers);
	}

	/**
	 * @return CommissionTypePaymentOption[]
	 */
	public function getPaymentOptions() : array {
		if (array_key_exists("PAYMENT_OPTIONS", $this->cache)) {
			return $this->cache["PAYMENT_OPTIONS"];
		}

		$stmt = new SelectQuery();

		$stmt->setTable(Tables::COMMISSION_TYPE_PAYMENT_OPTIONS);

		$stmt->addColumn(new Column("ID", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
		$stmt->addColumn(new Column("TYPE", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
		$stmt->addColumn(new Column("ADDRESS", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
		$stmt->addColumn(new Column("INSTRUCTIONS", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS), '=', $this->getId()]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DELETED", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS), '=', 0]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		$rawOptions = $stmt->getResult();

		$options = [];

		foreach ($rawOptions as $option) {
			$options[] = new CommissionTypePaymentOption($option["ID"], $option["TYPE"], $option["ADDRESS"], $option["INSTRUCTIONS"]);
		}

		return $this->cache["PAYMENT_OPTIONS"] = $options;
	}

	/**
	 * @return CommissionTypeStage[]
	 */
	public function getStages() : array {
		if (array_key_exists("STAGES", $this->cache)) {
			return $this->cache["STAGES"];
		}

		$stmt = new SelectQuery();

		$stmt->setTable(Tables::COMMISSION_TYPE_STAGES);

		$stmt->addColumn(new Column("ID", Tables::COMMISSION_TYPE_STAGES));
		$stmt->addColumn(new Column("STAGE", Tables::COMMISSION_TYPE_STAGES));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_STAGES), '=', $this->getId()]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DELETED", Tables::COMMISSION_TYPE_STAGES), '=', 0]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		$rawStages = $stmt->getResult();

		$stages = [];

		foreach ($rawStages as $stage) {
			$stages[] = new CommissionTypePaymentOption($stage["ID"], $stage["STAGE"]);
		}

		return $this->cache["STAGES"] = $stages;
	}

	/**
	 * @param Artist $artist
	 * @return self[]
	 */
	public static function getForArtist(Artist $artist) : array {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("ARTIST_PAGE_ID", self::getTable()), '=', $artist->getId()]);
		$stmt->addAdditionalCapability($whereClause);

		$orderByClause = new OrderByClause();
		$orderByClause->setColumn(new Column("SORT", self::getTable()));
		$orderByClause->setOrder("ASC");
		$stmt->addAdditionalCapability($orderByClause);

		$stmt->execute();

		$rows = $stmt->getResult();

		return array_map(function($in) { return new self($in["ID"]); }, $rows);
	}

	/**
	 * initialize image
	 */
	public function initializeImage() : void {
		if (count($this->getImageSet()) == 0) {
			$this->setImage(new Image(Folders::COMMISSION_TYPE_IMAGE, $this->getToken(), null, false));
		} else {
			$this->setImage($this->getImageSet()[0]);
		}
	}

	/**
	 * initialize all images
	 */
	public function initializeImageSet() : void {
		$stmt = new SelectQuery();

		$stmt->setTable(Tables::COMMISSION_TYPE_IMAGES);

		$stmt->addColumn(new Column("CAPTION", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("COMMISSIONER", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("PATH", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("NSFW", Tables::COMMISSION_TYPE_IMAGES));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_IMAGES), '=', $this->id]);
		$stmt->addAdditionalCapability($whereClause);

		$orderByClause = new OrderByClause();
		$orderByClause->setColumn(new Column("SORT", Tables::COMMISSION_TYPE_IMAGES));
		$orderByClause->setOrder("ASC");
		$stmt->addAdditionalCapability($orderByClause);

		$stmt->execute();

		$results = $stmt->getResult();

		$images = [];

		for ($i=0; $i < count($results); $i++) { 
			if (is_null($results[$i]["PATH"])) {
				break;
			}
			$images[] = new Image(
				Folders::COMMISSION_TYPE_IMAGE,
				$results[$i]["TOKEN"],
				$results[$i]["PATH"],
				(bool)$results[$i]["NSFW"],
				trim($results[$i]["CAPTION"].($results[$i]["COMMISSIONER"] ? ("\n**Client:** ".$results[$i]["COMMISSIONER"]) : ''))
			);
		}

		$this->setImageSet($images);
	}
}
