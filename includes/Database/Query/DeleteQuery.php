<?php

namespace Catalyst\Database\Query;

use \Catalyst\Database\Database;

/**
 * Represents a MySQL DELETE query
 */
class DeleteQuery extends AbstractQuery {
	/**
	 * Executes the query
	 * 
	 * Bind columns and whatnot.  $values will NOT be used for this type of query
	 * 
	 * @return bool if the query was successful
	 */
	public function execute() : bool {
		$this->verifyIntegrity();

		$initialQuery = "DELETE `".$this->table."` FROM `".$this->table."`";

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

		self::$totalQueries++;

		return true;
	}
}
