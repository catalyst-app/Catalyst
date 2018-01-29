<?php

namespace Catalyst\Database;

/**
 * Represents a MySQL SELECT query
 */
class SelectQuery extends \Catalyst\Database\Query {
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
			$initalQuery .= "`".$column."`";
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

		$stmt = \Catalyst\Database\Database::getDbh()->prepare($initalQuery);
		if (!$stmt->execute($this->getParamtersToBind())) {
			error_log(__CLASS__." execution error: ".serialize($stmt->errorInfo())."\n".implode(" | ",array_map(function($in) { return "(".$in["line"].")"."->".$in["class"].$in["type"].$in["function"]; }, (new \Exception())->getTrace())));
			if (defined("IS_API") && IS_API) {
				\Catalyst\HTTPCode::set(500);
				\Catalyst\API\Response::sendErrorResponse(1, "An unknown database error occured");
			}
		}

		$this->result = $stmt->fetchAll();

		return true;
	}
}
