<?php

namespace Catalyst\Database;

use \Catalyst\Database\Database;
use \Catalyst\HTTPCode;
use \Catalyst\API\{Endpoint, Response};

/**
 * Represents a MySQL SELECT query
 */
class SelectQuery extends Query {
	/**
	 * Executes the query
	 * 
	 * Bind columns and whatnot.  $values will NOT be used for this type of query
	 * 
	 * @return bool if the query was successful
	 */
	public function execute() : bool {
		$this->verifyIntegrity();

		$initalQuery = "SELECT ";
		
		// columns
		foreach ($this->columns as $column) {
			if (is_array($column)) {
				$initalQuery .= "`".$column[0]."`.`".$column[1]."`";
			} else {
				$initalQuery .= "`".$column."`";
			}
			if ($column != end($this->columns)) {
				$initalQuery .= ",";
			}
		}

		// from
		$initalQuery .= " FROM `".$this->table."`";

		// additional
		if (is_array($this->additionalCapabilities)) {
			foreach ($this->additionalCapabilities as $additionalCapability) {
				$initalQuery .= " ";
				$initalQuery .= (string)($additionalCapability);
			}
		}
		$initalQuery .= ";";

		$stmt = Database::getDbh()->prepare($initalQuery);
		if (!$stmt->execute($this->getParamtersToBind())) {
			error_log(__CLASS__." execution error: ".serialize($stmt->errorInfo())."\n".implode(" | ",array_map(function($in) { return "(".$in["line"].")"."->".$in["class"].$in["type"].$in["function"]; }, (new \Exception())->getTrace())));
			if (Endpoint::isApi()) {
				HTTPCode::set(500);
				Response::sendErrorResponse(1, "An unknown database error occured");
			}
		}

		$this->result = $stmt->fetchAll();

		return true;
	}
}
