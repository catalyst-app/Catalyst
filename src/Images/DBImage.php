<?php

namespace Catalyst\Images;

use \Catalyst\Database\Column;
use \Catalyst\Database\QueryAddition\WhereClause;
use \Catalyst\Database\Query\DeleteQuery;
use \Catalyst\Tokens;
use \Catalyst\User\User;

/**
 * Represents an image with database association
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
	 * Create a new object to represent an image
	 * 
	 * @param int $id
	 * @param string $table
	 * @param string $folder Folder in which the image is contained
	 * @param string $fileToken The parent object's file token
	 * @param string|null $path The path to the image, or null if default
	 * @param bool $nsfw If the image is mature or explicit
	 */
	public function __construct(int $id=0, string $table="", string $folder, string $fileToken, ?string $path, bool $nsfw=false, string $caption="") {
		$this->setId($id);
		$this->setTable($table);
		parent::__construct($folder, $fileToken, $path, $nsfw, $caption);
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
	 * Delete the image from disk (won't work for default)
	 */
	public function delete() : void {
		if (!is_null($this->getPath())) {
			unlink($this->getFilesystemPath());
		}
		$this->setPath("deleted_image.png");
		$this->setNsfw(false);
		$this->setCaption("Deleted image");

		$stmt = new DeleteQuery();

		$stmt->setTable($this->getTable());

		$whereClause = new WhereClause();
		$whereClause->addToClause([new Column("ID", $this->getTable()), '=', $this->getId()]);
		$stmt->addAdditionalCapability($whereClause);

		$stmt->execute();
	}
}
