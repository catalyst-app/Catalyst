<?php

namespace Catalyst\Database\Query;

use \Catalyst\Database\Database;
use \Exception;
use \PDO;

/**
 * Represents a MySQL SELECT query
 */
class SelectQuery extends AbstractQuery {
	/**
	 * Executes the query
	 * 
	 * Bind columns and whatnot.  $values will NOT be used for this type of query
	 * 
	 * @return bool if the query was successful
	 */
	public function execute() : bool {
		$this->verifyIntegrity();

		$initialQuery = "SELECT ";
		
		$initialQuery .= implode(",", $this->columns);

		// from
		$initialQuery .= " FROM `".$this->table."`";

		// additional
		if (is_array($this->additionalCapabilities)) {
			foreach ($this->additionalCapabilities as $additionalCapability) {
				$initialQuery .= " ";
				// each additional capability should verify integrity in its getQueryString
				$initialQuery .= $additionalCapability->getQueryString();
			}
		}
		$initialQuery .= ";";

		$stmt = Database::getDbh()->prepare($initialQuery);

		$stmt->execute($this->getParamtersToBind());

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($data === false) {
			throw new Exception("SELECT query returned false");
		}
		$this->result = $data;

		self::$totalQueries++;

		return true;
	}
}
