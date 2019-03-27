<?php

namespace Catalyst\User;

use \Catalyst\Database\{AbstractDatabaseModel, Column, Tables};
use \Catalyst\Database\Query\{InsertQuery, SelectQuery};
use \Catalyst\Database\QueryAddition\{MultipleOrderByClause, OrderByClause};
use \Catalyst\Images\{DBImage, Folders, HasDBImageSetTrait, HasImageTrait, Image};
use \Catalyst\Page\Values;
use \Catalyst\User\User;
use \Catalyst\Tokens;
use \DateTime;
use \Exception;
use \InvalidArgumentException;

/**
 * @method string getPatreonId()
 * @method void setPatreonId(string $patreonId)
 * @method string getName()
 * @method void setName(string $name)
 * @method bool isCurrent()
 * @method void setCurrent(bool $current)
 * @method int getPledgedCents()
 * @method void setPledgedCents(int $pledgedCents)
 * @method int getTotalCents()
 * @method void setTotalCents(int $totalCents)
 * @method null|DateTime getSince()
 * @method void setSince(null|DateTime $since)
 * @method string getDescription()
 * @method void setDescription(string $description)
 * @method string getSocialChips()
 * @method void setSocialChips(string $socialChips)
 * @method string getImageLoc()
 * @method void setImageLoc(string $imageLoc)
 */
class Patron extends AbstractDatabaseModel {
	use HasImageTrait;

	public const TIER_DEAD = 0;
	public const TIER_BASE = 1;
	public const TIER_BRONZE = 2;
	public const TIER_SILVER = 3;
	public const TIER_GOLD = 4;
	public const TIER_PLATINUM = 5;

	/**
	 * Get the table the object's data is stored in
	 * 
	 * Specified in DatabaseModelTrait
	 * @return string
	 */
	public static function getTable() : string {
		return Tables::PATRONS;
	}

	/**
	 * The folder containing the image
	 * @return string
	 */
	public static function getImageFolder() : string {
		return Folders::PATRON_ICONS;
	}

	/**
	 * @return int
	 */
	public function getPledgeLevel() : int {
		if (!$this->isCurrent()) {
			return self::TIER_DEAD;
		} else if ($this->getPledgedCents() < 500) {
			return self::TIER_BASE;
		} else if ($this->getPledgedCents() < 2000) {
			return self::TIER_BRONZE;
		} else if ($this->getPledgedCents() < 5000) {
			return self::TIER_SILVER;
		} else if ($this->getPledgedCents() < 10000) {
			return self::TIER_GOLD;
		} else {
			return self::TIER_PLATINUM;
		}
	}

	/**
	 * Initialize the primary image
	 */
	public function initializeImage() : void {
		$this->setImage(new Image(
			self::getImageFolder(),
			"",
			$this->getImageLoc(),
			false
		));
	}

	/**
	 * Get an array of all
	 * 
	 * @return self[]
	 */
	public static function getAll() : array {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));

		$orderClause = new MultipleOrderByClause();

		$orderClause->addClause(new OrderByClause(new Column("CURRENT", self::getTable()), "DESC"));
		$orderClause->addClause(new OrderByClause(new Column("SINCE", self::getTable()), "ASC"));
		$orderClause->addClause(new OrderByClause(new Column("TOTAL_CENTS", self::getTable()), "DESC"));
		$orderClause->addClause(new OrderByClause(new Column("PLEDGED_CENTS", self::getTable()), "DESC"));

		$stmt->addAdditionalCapability($orderClause);

		$stmt->execute();

		$patrons = [];

		foreach ($stmt->getResult() as $character) {
			$patrons[] = new self($character["ID"]);
		}

		return $patrons;
	}
	/**
	 * Create a patron
	 *
	 * @param array $values
	 * @return self
	 */
	public static function create(array $values) : self {
		$stmt = new InsertQuery();

		$stmt->setTable(self::getTable());

		foreach (["PATREON_ID","NAME","CURRENT","PLEDGED_CENTS","TOTAL_CENTS","SINCE","DESCRIPTION","SOCIAL_CHIPS","IMAGE_LOC"] as $column) {
			$stmt->addColumn(new Column($column, self::getTable()));
			$stmt->addValue($values[$column]);
		}

		$stmt->execute();

		$patron = new self($stmt->getResult(), $values);

		return $patron;
	}

	/**
	 * Get deleted values for when is delet
	 * @return array
	 */
	public function getDeletedValues() : array {
		return [];
	}

	/**
	 * @return array
	 */
	public static function getPrefetchColumns() : array {
		return [
			"PATREON_ID",
			"NAME",
			"CURRENT",
			"PLEDGED_CENTS",
			"TOTAL_CENTS",
			"SINCE",
			"DESCRIPTION",
			"SOCIAL_CHIPS",
			"IMAGE_LOC",
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
			"PatreonId" => ["PATREON_ID", null, null],
			"Name" => ["NAME", null, null],
			"Current" => ["CURRENT", null, null],
			"PledgedCents" => ["PLEDGED_CENTS", null, null],
			"TotalCents" => ["TOTAL_CENTS", null, null],
			"Since" => ["SINCE", "date_create", function (?DateTime $in) { return is_null($in) ? null : $in->format("Y-m-d"); }],
			"Description" => ["DESCRIPTION", null, null],
			"SocialChips" => ["SOCIAL_CHIPS", null, null],
			"ImageLoc" => ["IMAGE_LOC", null, null],
		];
	}
}
