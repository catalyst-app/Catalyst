<?php

namespace Catalyst\User;

use \Catalyst\Database\{AbstractDatabaseModel, Column, Tables};
use \Catalyst\Database\Query\SelectQuery;
use \Catalyst\Database\QueryAddition\{OrderByClause};
use \Catalyst\Images\{DBImage, Folders, HasDBImageSetTrait, HasImageTrait, Image};
use \Catalyst\Page\Values;
use \Catalyst\User\User;
use \Catalyst\Tokens;
use \Exception;
use \InvalidArgumentException;

/**
 * @method string getName()
 * @method void setName(string $name)
 * @method string getDescription()
 * @method void setDescription(string $description)
 * @method string getSocialMediaChips()
 * @method void setSocialMediaChips(string $socialMediaChips)
 * @method string getImagePath()
 * @method void setImagePath(string $imagePath)
 * @method int getSort()
 * @method void setSort(int $sort)
 */
class Staff extends AbstractDatabaseModel {
	use HasImageTrait;

	/**
	 * @var self[]|null
	 */
	protected static $all = null;

	/**
	 * Get the table the object's data is stored in
	 * 
	 * Specified in DatabaseModelTrait
	 * @return string
	 */
	public static function getTable() : string {
		return Tables::STAFF;
	}

	/**
	 * The folder containing the image
	 * @return string
	 */
	public static function getImageFolder() : string {
		return Folders::STAFF_ICONS;
	}

	/**
	 * Initialize the primary image
	 */
	public function initializeImage() : void {
		$this->setImage(new Image(
			self::getImageFolder(),
			"",
			$this->getImagePath(),
			false
		));
	}

	/**
	 * Get an array of all
	 * 
	 * @return self[]
	 */
	public static function getAll() : array {
		if (!is_null(self::$all)) {
			return self::$all;
		}

		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));

		$orderClause = new OrderByClause();

		$orderClause->setColumn(new Column("SORT", self::getTable()));
		$orderClause->setOrder("ASC");

		$stmt->addAdditionalCapability($orderClause);

		$stmt->execute();

		$staff = [];

		foreach ($stmt->getResult() as $character) {
			$staff[] = new self($character["ID"]);
		}

		return self::$all = $staff;
	}
	/**
	 * Create a character
	 *
	 * @param array $values
	 * @return self
	 */
	public static function create(array $values) : self {
		throw new Exception("no");
	}

	/**
	 * Get deleted values for when a character is delet
	 * @return array
	 */
	public function getDeletedValues() : array {
		return [
		];
	}

	/**
	 * @return array
	 */
	public static function getPrefetchColumns() : array {
		return [
			"NAME",
			"DESCRIPTION",
			"SOCIAL_MEDIA_CHIPS",
			"IMAGE_PATH",
			"SORT",
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
			"Name" => ["NAME", null, null],
			"Description" => ["DESCRIPTION", null, null],
			"SocialMediaChips" => ["SOCIAL_MEDIA_CHIPS", null, null],
			"ImagePath" => ["IMAGE_PATH", null, null],
			"Sort" => ["SORT", null, null],
		];
	}
}
