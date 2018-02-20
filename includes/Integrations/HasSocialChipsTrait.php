<?php

namespace Catalyst\Integrations;

use \Catalyst\Database\{Column, OrderByClause, SelectQuery, WhereClause};

/**
 * Used by classes which have social media chips
 */
trait HasSocialChipsTrait {
	/**
	 * Used to get the unique ID of the item in the social chip table
	 * 
	 * @return int unique id
	 */
	abstract public function getId() : int;

	/**
	 * Get the table the object's social chips are stored in
	 * 
	 * @return string Table name
	 */
	abstract public function getSocialChipTable() : string;

	/**
	 * Get the column in which "getId" is stored
	 * 
	 * @return string Column name
	 */
	abstract public function getSocialChipIdColumn() : string;

	/**
	 * Get social chips from the database for the object
	 * 
	 * @return array Social chips
	 */
	public function getSocialChipsFromDatabase() : array {
		$stmt = new SelectQuery();

		$stmt->setTable($this->getSocialChipTable());

		$stmt->addColumn(new Column("ID", $this->getSocialChipTable()));
		$stmt->addColumn(new Column("NETWORK", $this->getSocialChipTable()));
		$stmt->addColumn(new Column("SERVICE_URL", $this->getSocialChipTable()));
		$stmt->addColumn(new Column("DISP_NAME", $this->getSocialChipTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column($this->getSocialChipIdColumn(), $this->getSocialChipTable()), "=", $this->getId()]);
		$stmt->addAdditionalCapability($whereClause);

		$orderClause = new OrderByClause();
		$orderClause->setColumn(new Column("SORT", $this->getSocialChipTable()));
		$stmt->addAdditionalCapability($orderClause);

		$stmt->execute();

		return $stmt->getResult();
	}

	/**
	 * Get the HTML for the object's social chips
	 * 
	 * @param bool $editMode if the chip is in edit mode
	 * @return string The HTML for the object's chips
	 */
	public function getSocialChipHtml(bool $editMode=false) : string {
		return SocialMedia::getChipHtml(SocialMedia::getChipArray($this->getSocialChipsFromDatabase()), $editMode);
	}
}
