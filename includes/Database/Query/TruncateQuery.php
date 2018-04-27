<?php

namespace Catalyst\Database\Query;

use \Catalyst\Database\Database;

/**
 * Represents a MySQL TRUNCATE query
 */
class TruncateQuery extends AbstractQuery {
	/**
	 * Executes the query
	 * 
	 * No columns OR values will be used in this query
	 * 
	 * @return bool if the query was successful
	 */
	public function execute() : bool {
		$this->verifyIntegrity();

		$initialQuery = "TRUNCATE `".$this->table."`";

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
