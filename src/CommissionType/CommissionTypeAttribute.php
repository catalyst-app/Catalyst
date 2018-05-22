<?php

namespace Catalyst\CommissionType;

use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\{JoinClause, MultipleOrderByClause, OrderByClause};
use \Catalyst\Database\Query\SelectQuery;
use \InvalidArgumentException;

/**
 * Used to handle array of commission type attributes
 */
class CommissionTypeAttribute {
	/**
	 * Cache of attributes, in db-esque keyed format
	 * @var null|array
	 */
	protected static $attributeMetadataCache = null;
	/**
	 * Cache of attributes, as objects
	 * @var null|self[]
	 */
	protected static $allAttributes = null;
	/**
	 * Cache of groups, in id => label
	 * @var null|string[]
	 */
	protected static $groups = null;

	/**
	 * Effectively an ID for attributes
	 *
	 * _Also can be thought of as a tag_
	 * 
	 * @var string
	 */
	protected $setKey = '';

	/**
	 * Create an attribute object
	 *
	 * @param string $setKey the ID/key of the attribute
	 */
	public function __construct(string $setKey = '') {
		if (is_null(self::$attributeMetadataCache)) {
			self::getAllAttributes(); // fill cache
		}

		if (!array_key_exists($setKey, self::$attributeMetadataCache)) {
			throw new InvalidArgumentException($setKey." is not a known key");
		}

		$this->setKey = $setKey;
	}

	/**
	 * @return string
	 */
	public function getSetKey() : string {
		return $this->setKey;
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return self::$attributeMetadataCache[$this->getSetKey()]["NAME"];
	}

	/**
	 * @return string
	 */
	public function getDescription() : string {
		return self::$attributeMetadataCache[$this->getSetKey()]["DESCRIPTION"];
	}

	/**
	 * @return int
	 */
	public function getGroupId() : int {
		return self::$attributeMetadataCache[$this->getSetKey()]["GROUP_ID"];
	}

	/**
	 * @return string
	 */
	public function getGroupLabel() : string {
		return self::$groups[self::$attributeMetadataCache[$this->getSetKey()]["GROUP_ID"]];
	}

	/**
	 * @return int
	 */
	public function getSort() : int {
		return self::$attributeMetadataCache[$this->getSetKey()]["SORT"];
	}


	/**
	 * Gets all attributes as self and populates metadata
	 * 
	 * @return self[] attributes
	 */
	protected static function getAllAttributes() : array {
		if (!is_null(self::$allAttributes)) {
			return self::$allAttributes;
		}
		$stmt = new SelectQuery();

		$stmt->setTable(Tables::COMMISSION_TYPE_ATTRIBUTES);

		$stmt->addColumn(new Column("SET_KEY", Tables::COMMISSION_TYPE_ATTRIBUTES));
		$stmt->addColumn(new Column("NAME", Tables::COMMISSION_TYPE_ATTRIBUTES));
		$stmt->addColumn(new Column("DESCRIPTION", Tables::COMMISSION_TYPE_ATTRIBUTES));
		$stmt->addColumn(new Column("GROUP_ID", Tables::COMMISSION_TYPE_ATTRIBUTES));
		$stmt->addColumn(new Column("LABEL", Tables::COMMISSION_TYPE_ATTRIBUTE_GROUPS));
		$stmt->addColumn(new Column("SORT", Tables::COMMISSION_TYPE_ATTRIBUTES));

		$joinClause = new JoinClause();
		$joinClause->setType(JoinClause::LEFT);
		$joinClause->setJoinTable(Tables::COMMISSION_TYPE_ATTRIBUTE_GROUPS);
		$joinClause->setLeftColumn(new Column("ID", Tables::COMMISSION_TYPE_ATTRIBUTE_GROUPS));
		$joinClause->setRightColumn(new Column("GROUP_ID", Tables::COMMISSION_TYPE_ATTRIBUTES));
		$stmt->addAdditionalCapability($joinClause);

		$orderByClause = new MultipleOrderByClause();

		$primaryOrderByClause = new OrderByClause();
		$primaryOrderByClause->setColumn(new Column("ID", Tables::COMMISSION_TYPE_ATTRIBUTE_GROUPS));
		$primaryOrderByClause->setOrder("ASC");
		$orderByClause->addClause($primaryOrderByClause);

		$secondOrderByClause = new OrderByClause();
		$secondOrderByClause->setColumn(new Column("SORT", Tables::COMMISSION_TYPE_ATTRIBUTES));
		$secondOrderByClause->setOrder("ASC");
		$orderByClause->addClause($secondOrderByClause);

		$stmt->addAdditionalCapability($orderByClause);

		$stmt->execute();

		$rawAttributes = $stmt->getResult();

		self::$attributeMetadataCache = [];
		self::$groups = [];
		self::$allAttributes = [];

		foreach ($rawAttributes as $row) {
			// the logic in this clusterfuck is as follows:
			// if the group isn't known, define it
			// then, fill in the metadata for the attribute
			// then construct the attribute object (as it relies on the other two due to standardization by attribute key)
			if (!array_key_exists($row["GROUP_ID"], self::$groups)) {
				self::$groups[$row["GROUP_ID"]] = $row["LABEL"];
			}
			self::$attributeMetadataCache[$row["SET_KEY"]] = [
				"SET_KEY" => $row["SET_KEY"],
				"NAME" => $row["NAME"],
				"DESCRIPTION" => $row["DESCRIPTION"],
				"GROUP_ID" => $row["GROUP_ID"],
				"SORT" => $row["SORT"],
			];
			self::$allAttributes[] = new self($row["SET_KEY"]);
		}

		return self::$allAttributes;
	}

	/**
	 * Get the attributes in such a way that the resulting array can be used in a ToggleableButtonSetField
	 * 
	 * @return array
	 */
	public static function getButtonSet() : array {
		$buttonSet = [];
		
		foreach (self::getAllAttributes() as $attribute) {
			if (!array_key_exists($attribute->getGroupLabel(), $buttonSet)) {
				$buttonSet[$attribute->getGroupLabel()] = [];
			}

			$buttonSet[$attribute->getGroupLabel()][] = [
				$attribute->getSetKey(),
				$attribute->getName(),
				$attribute->getDescription(),
			];
		}

		return $buttonSet;
	}

	/**
	 * Get the attributes from a space-delimited string
	 *
	 * @param string $in
	 * @return self[]
	 */
	public static function getObjectsFromString(string $in) : array {
		return array_map(function($key) {
			return new self($key);
		}, array_filter(explode(" ", $in)));
	}

	/**
	 * Get the space-delimited string from a series of attributes
	 *
	 * @param self[] $in
	 * @return string
	 */
	public static function getStringFromObjects(array $in) : string {
		return implode(" ", array_map(function(self $key) {
			return $key->getSetKey();
		}, $in));
	}

	/**
	 * Get a string representation of the object (just the set key)
	 *
	 * @return string
	 */
	public function __toString() : string {
		return $this->getSetKey();
	}
}
