<?php

namespace Catalyst\Database;

use \Catalyst\Database\Database;
use \Catalyst\HTTPCode;
use \Catalyst\API\{Endpoint, Response};

/**
 * Represents a MySQL UPDATE query
 */
class UpdateQuery extends AbstractQuery {
	/**
	 * Executes the query
	 * 
	 * Bind columns and whatnot. Also fills in ? for PDO binding
	 * 
	 * @return bool if the query was successful
	 */
	public function execute() : bool {
		$this->verifyIntegrity();

		$initialQuery = "UPDATE `".$this->table."` ";

		// join clauses go up here!
		if (is_array($this->additionalCapabilities)) {
			foreach ($this->additionalCapabilities as $additionalCapability) {
				if ($additionalCapability instanceof JoinClause) {
					$initialQuery .= $additionalCapability->getQueryString();
					$initialQuery .= " ";
				}
			}
		}
		
		$initialQuery .= " SET ".implode(" = ?, ", $this->columns)." = ? ";

		// additional
		if (is_array($this->additionalCapabilities)) {
			foreach ($this->additionalCapabilities as $additionalCapability) {
				if ($additionalCapability instanceof JoinClause) {
					continue;
				}
				// each additional capability should verify integrity in its getQueryString
				$initialQuery .= $additionalCapability->getQueryString();
			}
		}
		$initialQuery .= ";";

		$stmt = Database::getDbh()->prepare($initialQuery);

		$stmt->execute($this->getParamtersToBind());

		$this->result = Database::getDbh()->lastInsertId();

		return true;
	}
}
