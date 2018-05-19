<?php

namespace Catalyst\Images;

use \Catalyst\Database\Column;
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\{DeleteQuery, MultiInsertQuery, UpdateQuery};
use \Catalyst\Tokens;
use \Catalyst\User\User;
use \BadMethodCallException;

/**
 * Represents an image with database association
 *
 * @method string getPath()
 * @method void setPath(string $path)
 * @method bool getNsfw()
 * @method void setNsfw(bool $nsfw)
 * @method string getCaption()
 * @method void setCaption(string $caption)
 * @method string getInfo()
 * @method void setInfo(string $info)
 * @method int getSort()
 * @method void setSort(int $sort)
 */
class DBImage extends Image {
	/**
	 * ID of the image in an image table
	 * @var int
	 */
	protected $id=0;
	/**
	 * Table the image is in
	 * @var string
	 */
	protected $table="";
	/**
	 * Columns settable and gettable by this
	 */
	protected $columns=[];

	/**
	 * Used to cache pending updates so we can update multiple columns at once
	 * @var array
	 */
	protected $pendingUpdates = [];
	/**
	 * Used to cache pending updates so we can update multiple columns at once
	 * @var self[]
	 */
	protected static $objectsPendingUpdates = [];
	/**
	 * Used to cache pending insertions for multiple insertions
	 * @var self[]
	 */
	protected static $pendingInsertions = [];

	/**
	 * IDs to delete
	 * @var int[][]
	 */
	static $toDelete = [];

	/**
	 * Create a new object to represent an image
	 * 
	 * @param int $id
	 * @param int $parentId
	 * @param array $dbInfo
	 * @param string $folder Folder in which the image is contained
	 * @param string $fileToken The parent object's file token
	 * @param string|null $path The path to the image, or null if default
	 * @param bool $nsfw If the image is mature or explicit
	 * @param string $caption
	 * @param string $captionInfo If the image is mature or explicit
	 * @param int $sort
	 * @param bool $pendingInsertion If the image needs to be inserted into DB
	 */
	public function __construct(int $id=0, int $parentId=0, array $dbInfo, string $folder, string $fileToken, ?string $path=null, bool $nsfw=false, string $caption="", string $captionInfo="", int $sort=0, bool $pendingInsertion=false) {
		$this->setId($id);
		$this->setTable($dbInfo["table"]);
		$this->setColumns([
			"ParentId" => [
				"column" => $dbInfo["column"]["parentId"],
				"value" => $parentId,
			],
			"Path" => [
				"column" => $dbInfo["column"]["path"],
				"value" => $path,
			],
			"Nsfw" => [
				"column" => $dbInfo["column"]["nsfw"],
				"value" => $nsfw,
			],
			"Caption" => [
				"column" => $dbInfo["column"]["caption"],
				"value" => $caption,
			],
			"Info" => [
				"column" => $dbInfo["column"]["info"],
				"value" => $captionInfo,
			],
			"Sort" => [
				"column" => $dbInfo["column"]["sort"],
				"value" => $sort,
			],
		]);
		parent::__construct($folder, $fileToken, $path, $nsfw, trim($caption.($captionInfo ? ("\n".$dbInfo["captionDelimiter"].$captionInfo) : '')));
		if ($pendingInsertion) {
			self::$pendingInsertions[] = $this;
		}
	}

	/**
	 * @return int
	 */
	public function getId() : int {
		return $this->id;
	}

	/**
	 * We provide a public setter as this isn't a direct reflection of the DB
	 * @param int $id
	 */
	public function setId(int $id) : void {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getTable() : string {
		return $this->table;
	}

	/**
	 * @param string $table
	 */
	public function setTable(string $table) : void {
		$this->table = $table;
	}

	/**
	 * @return array
	 */
	protected function getColumns() : array {
		return $this->columns;
	}

	/**
	 * @param array $columns
	 */
	protected function setColumns(array $columns) : void {
		$this->columns = $columns;
	}

	/**
	 * dynamic getters and setters uwu
	 */
	public function __call(string $name, array $arguments) {
		$type = "";
		if (strpos($name, "get") === 0) {
			$type = "get";
			$name = substr($name, 3);
			if (count($arguments) !== 0) {
				throw new BadMethodCallException("Invalid number of parameters passed to ".__CLASS__."::".$name." - recieved ".count($arguments)." but expected 0.");
			}
		} elseif (strpos($name, "is") === 0) {
			$type = "is";
			$name = substr($name, 2);
			if (count($arguments) !== 0) {
				throw new BadMethodCallException("Invalid number of parameters passed to ".__CLASS__."::".$name." - recieved ".count($arguments)." but expected 0.");
			}
		} elseif (strpos($name, "set") === 0) {
			$type = "set";
			$name = substr($name, 3);
			if (count($arguments) !== 1) {
				throw new BadMethodCallException("Invalid number of parameters passed to ".__CLASS__."::".$name." - recieved ".count($arguments)." but expected 1.");
			}
		} else {
			throw new BadMethodCallException($name." is not a method of ".__CLASS__);
		}

		if (!array_key_exists($name, $this->getColumns())) {
			throw new BadMethodCallException($name." is not a method of ".__CLASS__);
		}

		$prop = $this->getColumns()[$name];

		if ($type == "get" || $type == "is") {
			if ($type == "is") {
				return (bool)$prop["value"];
			}
			return $prop["value"];
		} else {
			if ($prop["value"] == $arguments[0]) {
				return;
			}
			$prop["value"] = $arguments[0];

			// using key => value allows easy and painless overwriting if the same value is changed twice
			$this->pendingUpdates[$prop["column"]] = $arguments[0];

			// for shutdown
			self::$objectsPendingUpdates[] = $this;
		}
	}

	/**
	 * Delete the image from disk (won't work for default) and mark it to be deleted in DB
	 */
	public function delete() : void {
		parent::delete();

		if (empty($this->getTable()) || $this->getId() == 0) {
			return;
		}

		if (!array_key_exists($this->getTable(), self::$toDelete)) {
			self::$toDelete[$this->getTable()] = [];
		}

		self::$toDelete[$this->getTable()][] = $this->getId();
	}

	/**
	 * Used by shutdown function to save to database
	 */
	public static function writeAllChanges() : void {
		self::writeAllInsertions();
		self::writeAllUpdates();
		self::writeAllDeletions();
	}

	/**
	 * Writes all database updates
	 */
	public static function writeAllInsertions() : void {
		if (empty(self::$pendingInsertions)) {
			return;
		}

		$queries = [];

		foreach (self::$pendingInsertions as $pendingInsertion) {
			if (in_array($pendingInsertion->getTable(), self::$toDelete) && in_array($pendingInsertion, self::$toDelete[$pendingInsertion->getTable()])) {
				continue; // we've been marked for deletion, let's not insert
			}

			// create query if needed
			if (!array_key_exists($pendingInsertion->getTable(), $queries)) {
				$queries[$pendingInsertion->getTable()] = new MultiInsertQuery();

				$queries[$pendingInsertion->getTable()]->setTable($pendingInsertion->getTable());

				foreach ($pendingInsertion->getColumns() as $column) {
					$queries[$pendingInsertion->getTable()]->addColumn(new Column($column["column"], $pendingInsertion->getTable()));
				}
			}

			// add columns
			foreach ($pendingInsertion->getColumns() as $column) {
				$queries[$pendingInsertion->getTable()]->addValue($column["value"]);
			}

			$pendingInsertion->pendingUpdates = [];
		}

		foreach ($queries as $query) {
			$query->execute();
		}
	}

	/**
	 * Writes all database updates
	 */
	public static function writeAllUpdates() : void {
		if (empty(self::$objectsPendingUpdates)) {
			return;
		}

		foreach (self::$objectsPendingUpdates as $object) {
			$object->writeUpdates();
		}
	}

	/**
	 * Write the database updates for this class
	 */
	public function writeUpdates() : void {
		if (empty($this->pendingUpdates)) {
			return;
		}

		// don't update if we're deleting, that's redundant!
		if (array_key_exists($this->getTable(), self::$toDelete) && in_array($this->getId(), self::$toDelete[$this->getTable()])) {
			return;
		}

		$stmt = new UpdateQuery();

		$stmt->setTable($this->getTable());

		foreach ($this->pendingUpdates as $key => $value) {
			$stmt->addColumn(new Column($key, $this->getTable()));
			$stmt->addValue($value);
		}

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("ID", $this->getTable()), "=", $this->getId()]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();

		$this->pendingUpdates = [];
	}

	/**
	 * Write the queued deletions to DB
	 */
	public static function writeAllDeletions() : void {
		if (empty(self::$toDelete)) {
			return;
		}

		foreach (self::$toDelete as $table => $ids) {
			if (empty($ids)) {
				continue;
			}

			$stmt = new DeleteQuery();

			$stmt->setTable($table);

			$whereClause = new WhereClause();
			$whereClause->addToClause([new Column("ID", $table), 'IN', $ids]);
			$stmt->addAdditionalCapability($whereClause);

			$stmt->execute();
		}

		self::$toDelete = [];
	}
}
