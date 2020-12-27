<?php

namespace Catalyst\Integrations;

use \Catalyst\Database\Column;
use \Catalyst\Database\QueryAddition\{OrderByClause, WhereClause};
use \Catalyst\Database\Query\{DeleteQuery, InsertQuery, SelectQuery};

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
		$stmt->addColumn(new Column("SORT", $this->getSocialChipTable()));

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column($this->getSocialChipIdColumn(), $this->getSocialChipTable()), "=", $this->getId()]);
		$stmt->addAdditionalCapability($whereClause);

		$orderClause = new OrderByClause();
		$orderClause->setColumn(new Column("SORT", $this->getSocialChipTable()));
		$orderClause->setOrder("ASC");
		$stmt->addAdditionalCapability($orderClause);

		$stmt->execute();

		return $stmt->getResult();
	}

	public function deleteSocialChipsFromDatabase() : void {
		$stmt = new DeleteQuery();

		$stmt->setTable($this->getSocialChipTable());

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column($this->getSocialChipIdColumn(), $this->getSocialChipTable()), "=", $this->getId()]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();
	}

	/**
	 * @return string HTML of the chip
	 */
	public function addSocialChip(string $type, ?string $url, string $label) : string {
		// get next sort, forcing to 0 if no items exist
		$nextSort = max(...array_merge([0,0], array_column($this->getSocialChipsFromDatabase(), "SORT"))) + 1;

		$stmt = new InsertQuery();

		$stmt->setTable($this->getSocialChipTable());

		$stmt->addColumn(new Column($this->getSocialChipIdColumn(), $this->getSocialChipTable()));
		$stmt->addValue($this->getId());

		$stmt->addColumn(new Column("NETWORK", $this->getSocialChipTable()));
		$stmt->addValue($type);
		$stmt->addColumn(new Column("SERVICE_URL", $this->getSocialChipTable()));
		$stmt->addValue($url);
		$stmt->addColumn(new Column("DISP_NAME", $this->getSocialChipTable()));
		$stmt->addValue($label);
		$stmt->addColumn(new Column("SORT", $this->getSocialChipTable()));
		$stmt->addValue($nextSort);

		$stmt->execute();

		$id = $stmt->getResult();

		return SocialMedia::getChipHtml(SocialMedia::getChipArray([
			[
				"ID" => $id,
				"NETWORK" => $type,
				"SERVICE_URL" => $url,
				"DISP_NAME" => $label,
			]
		]), true);
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
