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

		$methodDefinition = $this->getColumns()[$name];

		if ($type == "get" || $type == "is") {
			if ($type == "is") {
				return (bool)$this->getColumns()[$methodDefinition[0]]["value"];
			}
			return $this->getColumns()[$methodDefinition[0]]["value"];
		} else {
			if ($this->getColumns()[$methodDefinition[0]]["value"] == $arguments[0]) {
				return;
			}
			$this->getColumns()[$methodDefinition[0]]["value"] = $arguments[0];

			// using key => value allows easy and painless overwriting if the same value is changed twice
			$this->pendingUpdates[$this->getColumns()[$methodDefinition[0]]["column"]] = $arguments[0];

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


		$stmt->setTable($this->getTable());

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("ID", $this->getTable()), '=', $this->getId()]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();
	}
}
