<?php

namespace Catalyst\Database;

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

		$initialQuery = "DELETE ";
		
		// join clauses go up here!
		if (is_array($this->additionalCapabilities)) {
			foreach ($this->additionalCapabilities as $additionalCapability) {
				if ($additionalCapability instanceof JoinClause) {
					$initialQuery .= $additionalCapability->getQueryString();
					$initialQuery .= " ";
				}
			}
		}
		
		// from
		$initialQuery .= "FROM `".$this->table."`";

		// additional
		if (is_array($this->additionalCapabilities)) {
			foreach ($this->additionalCapabilities as $additionalCapability) {
				if ($additionalCapability instanceof JoinClause) {
					continue;
				}
				$initialQuery .= " ";
				// each additional capability should verify integrity in its getQueryString
				$initialQuery .= $additionalCapability->getQueryString();
			}
		}
		$initialQuery .= ";";

		$stmt = Database::getDbh()->prepare($initialQuery);

		$stmt->execute($this->getParamtersToBind());

		return true;
	}
}
