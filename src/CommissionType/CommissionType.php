<?php

namespace Catalyst\CommissionType;

use \Catalyst\Artist\Artist;
use \Catalyst\Database\{AbstractDatabaseModel, Column, DatabaseModelTrait, Tables};
use \Catalyst\Database\Query\{InsertQuery, SelectQuery};
use \Catalyst\Database\QueryAddition\{JoinClause, MultipleOrderByClause, OrderByClause, WhereClause};
use \Catalyst\Images\{DBImage, Folders, HasDBImageSetTrait, HasImageTrait, Image};
use \Catalyst\Tokens;
use \Catalyst\User\WishlistItem;
use \InvalidArgumentException;

/**
 * Represents a commission type
 * @method int getArtistPageId()
 * @method void setArtistPageId(int $artistPageId)
 * @method string getToken()
 * @method void setToken(string $token)
 * @method string getName()
 * @method void setName(string $name)
 * @method string getBlurb()
 * @method void setBlurb(string $blurb)
 * @method string getDescription()
 * @method void setDescription(string $description)
 * @method int getSort()
 * @method void setSort(int $sort)
 * @method string getBaseCost()
 * @method void setBaseCost(string $baseCost)
 * @method float getBaseUsdCost()
 * @method void setBaseUsdCost(float $baseUsdCost)
 * @method CommissionTypeAttribute[] getAttributes()
 * @method void setAttributes(CommissionTypeAttribute[] $attrs)
 * @method string getAttributeString()
 * @method void setAttributeString(string $attrs)
 * @method bool isAcceptingQuotes()
 * @method void setAcceptingQuotes(bool $acceptingQuotes)
 * @method bool isAcceptingRequests()
 * @method void setAcceptingRequests(bool $acceptingRequests)
 * @method bool isAcceptingTrades()
 * @method void setAcceptingTrades(bool $acceptingTrades)
 * @method bool isAcceptingCommissions()
 * @method void setAcceptingCommissions(bool $acceptingCommissions)
 * @method bool isVisible()
 * @method void setVisible(bool $visible)
 */
class CommissionType extends AbstractDatabaseModel {
	use HasImageTrait, HasDBImageSetTrait;

	/**
	 * Used for quick toggle buttons, defines HTML ID, getter, and setter
	 */
	public const QUICK_TOGGLE_BUTTONS = [
		["visible", "isVisible", "setVisible"],
		["quotes", "isAcceptingQuotes", "setAcceptingQuotes"],
		["requests", "isAcceptingRequests", "setAcceptingRequests"],
		["trades", "isAcceptingTrades", "setAcceptingTrades"],
		["commissions", "isAcceptingCommissions", "setAcceptingCommissions"],
	];

	/**
	 * Get columns to prefetch
	 *
	 * @return array
	 */
	public static function getPrefetchColumns() : array {
		return [
			"ARTIST_PAGE_ID",
			"TOKEN",
			"NAME",
			"BLURB",
			"DESCRIPTION",
			"SORT",
			"BASE_COST",
			"BASE_USD_COST",
			"ATTRS",
			"ACCEPTING_QUOTES",
			"ACCEPTING_REQUESTS",
			"ACCEPTING_TRADES",
			"ACCEPTING_COMMISSIONS",
			"VISIBLE",
		];
	}

	/**
	 * Get columns to prefetch
	 *
	 * @return array
	 */
	public static function getDeletedColumns() : array {
		return [
			"ARTIST_PAGE_ID",
			"TOKEN",
			"NAME",
			"BLURB",
			"DESCRIPTION",
			"SORT",
			"BASE_COST",
			"BASE_USD_COST",
			"ATTRS",
			"ACCEPTING_QUOTES",
			"ACCEPTING_REQUESTS",
			"ACCEPTING_TRADES",
			"ACCEPTING_COMMISSIONS",
			"VISIBLE",
		];
	}

	/**
	 * Values to insert upon CT deletion
	 *
	 * We don't delete any information such as name/etc because artist's will need this information in the future for previous commissions
	 */
	public function getDeletedValues() : array {
		return [
			"NAME" => "[Deleted] ".substr($this->getName(), 0, 245),
			"BLURB" => "Deleted commission type",
			"DESCRIPTION" => "Deleted commission type",
			"SORT" => 0,
			"ATTRS" => "",
			"ACCEPTING_QUOTES" => false,
			"ACCEPTING_REQUESTS" => false,
			"ACCEPTING_TRADES" => false,
			"ACCEPTING_COMMISSIONS" => false,
			"VISIBLE" => false,
			"DELETED" => true,
		];
	}

	/**
	 * Get ID from token, if exists
	 * 
	 * @param string $token commission type token
	 * @param bool $mustBeVisible if the CT must be visible
	 *   Might be better to impliment in client endpoint
	 * @return int ID if exists, -1 if not
	 */
	public static function getIdFromToken(string $token, bool $mustBeVisible=true) : int {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("TOKEN", self::getTable()), '=', $token]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DELETED", self::getTable()), '=', 0]);
		if ($mustBeVisible) {
			$whereClause->addToClause(WhereClause::AND);
			$whereClause->addToClause([new Column("VISIBLE", self::getTable()), '=', 0]);
		}
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		$result = $stmt->getResult();

		if (count($result) == 0) {
			return -1;
		}

		return $result[0]["ID"];
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
	 * The folder containing the image
	 * @return string
	 */
	public static function getImageFolder() : string {
		return Folders::COMMISSION_TYPE_IMAGE;
	}

	/**
	 * Returns info about the commission_type_images table
	 *
	 * @return array
	 */
	public static function getImageDbInfo() : array {
		return [
			"table" => Tables::COMMISSION_TYPE_IMAGES,
			"column" => [
				"parentId" => "COMMISSION_TYPE_ID",
				"path" => "PATH",
				"nsfw" => "NSFW",
				"caption" => "CAPTION",
				"info" => "COMMISSIONER",
				"sort" => "SORT",
			],
			"captionDelimiter" => "**Client:** ",
		];
	}

	/**
	 * Get the artist's page as an object
	 *
	 * @return Artist
	 */
	public function getArtistPage() : Artist {
		return $this->getDataFromCallableOrCache("ARTIST_PAGE_OBJ", function() : Artist {
			return new Artist($this->getArtistPageId());
		});
	}

	/**
	 * Returns an array of CommissionTypeModifier
	 * 
	 * @return CommissionTypeModifier[]
	 */
	public function getModifiers() : array {
		return $this->getDataFromCallableOrCache("MODIFIER_OBJS", function() : array {
			$stmt = new SelectQuery();

			$stmt->setTable(Tables::COMMISSION_TYPE_MODIFIERS);

			$stmt->addColumn(new Column("ID", Tables::COMMISSION_TYPE_MODIFIERS));
			$stmt->addColumn(new Column("NAME", Tables::COMMISSION_TYPE_MODIFIERS));
			$stmt->addColumn(new Column("PRICE", Tables::COMMISSION_TYPE_MODIFIERS));
			$stmt->addColumn(new Column("USDEQ", Tables::COMMISSION_TYPE_MODIFIERS));
			$stmt->addColumn(new Column("GROUP", Tables::COMMISSION_TYPE_MODIFIERS));
			$stmt->addColumn(new Column("MULTIPLE", Tables::COMMISSION_TYPE_MODIFIERS));
			$stmt->addColumn(new Column("SORT", Tables::COMMISSION_TYPE_MODIFIERS));

			$whereClause = new WhereClause();
			$whereClause->addToClause([new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_MODIFIERS), '=', $this->getId()]);
			$whereClause->addToClause(WhereClause::AND);
			$whereClause->addToClause([new Column("DELETED", Tables::COMMISSION_TYPE_MODIFIERS), '=', 0]);
			$stmt->addAdditionalCapability($whereClause);

			$orderByClause = new MultipleOrderByClause();

			$groupOrderByClause = new OrderByClause();
			$groupOrderByClause->setColumn(new Column("GROUP", Tables::COMMISSION_TYPE_MODIFIERS));
			$groupOrderByClause->setOrder("ASC");
			$orderByClause->addClause($groupOrderByClause);

			$sortOrderByClause = new OrderByClause();
			$sortOrderByClause->setColumn(new Column("SORT", Tables::COMMISSION_TYPE_MODIFIERS));
			$sortOrderByClause->setOrder("ASC");
			$orderByClause->addClause($sortOrderByClause);

			$stmt->addAdditionalCapability($orderByClause);

			$stmt->execute();

			$modifiers = [];

			foreach ($stmt->getResult() as $modifier) {
				$modifier = new CommissionTypeModifier($modifier["ID"], [
					"NAME" => $modifier["NAME"],
					"PRICE" => $modifier["PRICE"],
					"USDEQ" => (float)$modifier["USDEQ"],
					"GROUP" => $modifier["GROUP"],
					"MULTIPLE" => $modifier["MULTIPLE"],
					"SORT" => $modifier["SORT"],
					"DELETED" => 0,
				], false); // do not prefetch as we know it all already and to reduce load
				$modifiers[] = $modifier;
			}

			return array_values($modifiers);
		});
	}

	/**
	 * @return CommissionTypePaymentOption[]
	 */
	public function getPaymentOptions() : array {
		return $this->getDataFromCallableOrCache("PAYMENT_OBJS", function() : array {
			$stmt = new SelectQuery();

			$stmt->setTable(Tables::COMMISSION_TYPE_PAYMENT_OPTIONS);

			$stmt->addColumn(new Column("ID", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
			$stmt->addColumn(new Column("TYPE", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
			$stmt->addColumn(new Column("ADDRESS", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
			$stmt->addColumn(new Column("INSTRUCTIONS", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
			$stmt->addColumn(new Column("SORT", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));

			$whereClause = new WhereClause();
			$whereClause->addToClause([new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS), '=', $this->getId()]);
			$whereClause->addToClause(WhereClause::AND);
			$whereClause->addToClause([new Column("DELETED", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS), '=', 0]);
			$stmt->addAdditionalCapability($whereClause);

			$orderByClause = new OrderByClause();
			$orderByClause->setColumn(new Column("SORT", Tables::COMMISSION_TYPE_PAYMENT_OPTIONS));
			$orderByClause->setOrder("ASC");
			$stmt->addAdditionalCapability($orderByClause);

			$stmt->execute();

			$options = [];

			foreach ($stmt->getResult() as $option) {
				$options[] = new CommissionTypePaymentOption($option["ID"], [
					"COMMISSION_TYPE_ID" => $this->getId(),
					"TYPE" => $option["TYPE"], 
					"ADDRESS" => $option["ADDRESS"], 
					"INSTRUCTIONS" => $option["INSTRUCTIONS"],
					"SORT" => $option["SORT"],
					"DELETED" => 0,
				], false);
			}

			return $options;
		});
	}

	/**
	 * @return CommissionTypeStage[]
	 */
	public function getStages() : array {
		return $this->getDataFromCallableOrCache("STAGE_OBJS", function() : array {
			$stmt = new SelectQuery();

			$stmt->setTable(Tables::COMMISSION_TYPE_STAGES);

			$stmt->addColumn(new Column("ID", Tables::COMMISSION_TYPE_STAGES));
			$stmt->addColumn(new Column("STAGE", Tables::COMMISSION_TYPE_STAGES));
			$stmt->addColumn(new Column("SORT", Tables::COMMISSION_TYPE_STAGES));

			$whereClause = new WhereClause();
			$whereClause->addToClause([new Column("COMMISSION_TYPE_ID", Tables::COMMISSION_TYPE_STAGES), '=', $this->getId()]);
			$whereClause->addToClause(WhereClause::AND);
			$whereClause->addToClause([new Column("DELETED", Tables::COMMISSION_TYPE_STAGES), '=', 0]);
			$stmt->addAdditionalCapability($whereClause);

			$orderByClause = new OrderByClause();
			$orderByClause->setColumn(new Column("SORT", Tables::COMMISSION_TYPE_STAGES));
			$orderByClause->setOrder("ASC");
			$stmt->addAdditionalCapability($orderByClause);

			$stmt->execute();

			$stages = [];

			foreach ($stmt->getResult() as $stage) {
				$stages[] = new CommissionTypeStage($stage["ID"], [
					"COMMISSION_TYPE_ID" => $this->getId(),
					"STAGE" => $stage["STAGE"],
					"SORT" => $stage["SORT"],
					"DELETED" => 0,
				], false);
			}

			return $stages;
		});
	}

	/**
	 * @param Artist $artist
	 * @return self[]
	 */
	public static function getForArtist(Artist $artist, bool $onlyPublic=false) : array {
		$stmt = new SelectQuery();

		$stmt->setTable(self::getTable());

		$stmt->addColumn(new Column("ID", self::getTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("ARTIST_PAGE_ID", self::getTable()), '=', $artist->getId()]);
		$whereClause->addToClause(WhereClause::AND);
		$whereClause->addToClause([new Column("DELETED", self::getTable()), '=', 0]);
		if ($onlyPublic) {
			$whereClause->addToClause(WhereClause::AND);
			$whereClause->addToClause([new Column("VISIBLE", self::getTable()), '=', 1]);
		}
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
			$this->setImage(new Image(self::getImageFolder(), $this->getToken(), null, false));
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

		$stmt->addColumn(new Column("ID", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("CAPTION", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("COMMISSIONER", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("PATH", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("NSFW", Tables::COMMISSION_TYPE_IMAGES));
		$stmt->addColumn(new Column("SORT", Tables::COMMISSION_TYPE_IMAGES));

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
			$images[] = new DBImage(
				$results[$i]["ID"],
				$this->getId(),
				self::getImageDbInfo(),
				self::getImageFolder(),
				$this->getToken(),
				$results[$i]["PATH"],
				$results[$i]["NSFW"],
				$results[$i]["CAPTION"],
				$results[$i]["COMMISSIONER"],
				$results[$i]["SORT"]
			);
		}

		$this->setImageSet($images);
	}

	/**
	 * See AbstractDatabaseModel for spec
	 *
	 * @return array
	 */
	public static function getModifiableProperties() : array {
		return [
			"ArtistPageId" => ["ARTIST_PAGE_ID", null, null],
			"Token" => ["TOKEN", null, null],
			"Name" => ["NAME", null, null],
			"Blurb" => ["BLURB", null, null],
			"Description" => ["DESCRIPTION", null, null],
			"Sort" => ["SORT", null, null],
			"BaseCost" => ["BASE_COST", null, null],
			"BaseUsdCost" => ["BASE_USD_COST", null, null],
			"Attributes" => ["ATTRS", [CommissionTypeAttribute::class, "getObjectsFromString"], [CommissionTypeAttribute::class, "getStringFromObjects"]],
			"AttributeString" => ["ATTRS", null, null],
			"AcceptingQuotes" => ["ACCEPTING_QUOTES", "boolval", null],
			"AcceptingRequests" => ["ACCEPTING_REQUESTS", "boolval", null],
			"AcceptingTrades" => ["ACCEPTING_TRADES", "boolval", null],
			"AcceptingCommissions" => ["ACCEPTING_COMMISSIONS", "boolval", null],
			"Visible" => ["VISIBLE", "boolval", null],
		];
	}

	/**
	 * Create a commission type
	 *
	 * @param array $values
	 * @return self
	 */
	public static function create(array $values) : self {
		// per array_merge docs:
		// If the input arrays have the same string keys, then the latter value
		//  for that key will overwrite the previous one
		$values = array_merge([
			"TOKEN" => Tokens::generateCommissionTypeToken(),
			"SORT" => 0,
			"ATTRS" => "",
			"ACCEPTING_QUOTES" => false,
			"ACCEPTING_REQUESTS" => false,
			"ACCEPTING_TRADES" => false,
			"ACCEPTING_COMMISSIONS" => false,
			"VISIBLE" => false,
			"_modifiers" => [],
			"_payment_options" => [],
			"_stages" => [],
			"_images" => [], // preload with an array of Image
			"_image_meta" => [], // preload with properly formatted array
		], $values);

		$stmt = new InsertQuery();

		$stmt->setTable(self::getTable());

		foreach (["ARTIST_PAGE_ID", "TOKEN", "NAME", "BLURB", "DESCRIPTION", "SORT", "BASE_COST", "BASE_USD_COST", "ATTRS", "ACCEPTING_QUOTES", "ACCEPTING_REQUESTS", "ACCEPTING_TRADES", "ACCEPTING_COMMISSIONS", "VISIBLE"] as $column) {
			$stmt->addColumn(new Column($column, self::getTable()));
			$stmt->addValue($values[$column]);
		}

		$stmt->execute();

		$commissionType = new self($stmt->getResult(), $values);

		foreach ($values["_images"] as $image) {
			$commissionType->addImage(
				$image->getPath(),
				!!$values["_image_meta"][$image->getUploadName()]["nsfw"],
				$values["_image_meta"][$image->getUploadName()]["caption"],
				$values["_image_meta"][$image->getUploadName()]["info"],
				$values["_image_meta"][$image->getUploadName()]["sort"]
			);
		}

		foreach ($values["_modifiers"] as $modifier) {
			CommissionTypeModifier::create(array_merge(["COMMISSION_TYPE_ID" => $commissionType->getId()], $modifier));
		}

		foreach ($values["_payment_options"] as $modifier) {
			CommissionTypePaymentOption::create(array_merge(["COMMISSION_TYPE_ID" => $commissionType->getId()], $modifier));
		}

		foreach ($values["_stages"] as $modifier) {
			CommissionTypeStage::create(array_merge(["COMMISSION_TYPE_ID" => $commissionType->getId()], $modifier));
		}

		return $commissionType;
	}

	/**
	 * Responsible for cleaning up all the inner stuff (modifiers, stages, payment options)
	 */
	public function additionalDeletion() : void {
		foreach ($this->getModifiers() as $modifier) {
			$modifier->delete();
		}
		foreach ($this->getPaymentOptions() as $paymentOption) {
			$paymentOption->delete();
		}
		foreach ($this->getStages() as $stage) {
			$stage->delete();
		}
		foreach (WishlistItem::getForCommissionType($this) as $wishlistItem) {
			$wishlistItem->delete();
		}
	}
}
