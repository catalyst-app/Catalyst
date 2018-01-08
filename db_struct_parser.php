<?php

$input = @file_get_contents($argv[1]);

if ($input === false) {
	throw new InvalidArgumentException();
}

preg_match_all("/([a-zA-Z0-9_]*)\s*\{([^}]*)}/m", $input, $tables);

$tables = array_merge(
	...array_map(function($i) use ($tables) {
		return [
			$tables[1][$i] => array_merge(
				...array_map(
					function($in) {
						if (strpos($in, '//') !== false) {
							$in = trim(substr($in, 0, strpos($in, '//')));
						}
						if (strlen($in) === 0) {
							return [];
						}
						$split = preg_split("/\s+/", $in);
						return [$split[0] => array_slice($split, 1)];
					},
					array_filter(
						array_map(
							"trim",
							explode("\n", $tables[2][$i])
						)
					)
				)
			)
		];
	}, array_keys($tables[0]))
);

$sql = "";

foreach ($tables as $name => $columns) {
	echo "Parsing table ".$name."\n";
	$sql .= "DROP TABLE IF EXISTS `".$name."`;\n";
	$sql .= "CREATE TABLE `".$name."` (\n";

	foreach ($columns as $colname => $col) {
		$colname = strtoupper($colname);
		echo "  Parsing column ".$colname."\n";
		
		$end = "";
		if (next($columns) !== false) {
			$end = ",";
		}

		$sql .= "  `".$colname."` ";

		$type = strtoupper(ltrim(preg_split("/(\(|=)/", $col[0])[0], "?"));

		preg_match("/\((.*)\)/", $col[0], $setParams);

		$setParams = (isset($setParams[1]) ? $setParams[1] : null);

		$defaultParams = "";

		switch ($type) {
			case "INT":
				$type = "int";
				$defaultParams = "11";
				break;
			case "VC":
				$type = "varchar";
				$defaultParams = "255";
				break;
			case "TEXT":
			case "TEXT-MD":
				$type = "mediumtext COLLATE utf8mb4_unicode_ci";
				$defaultParams = "";
				break;
			case "SMALLTEXT":
				$type = "text COLLATE utf8mb4_unicode_ci";
				$defaultParams = "";
				break;
			case "BOOL":
				$type = "tinyint";
				$defaultParams = "1";
				break;
			case "DECIMAL":
				$type = "decimal";
				$defaultParams = "1";
				break;
			case "DATETIME":
				$type = "datetime";
				$defaultParams = "";
				break;
			case "SET":
				$type = "set";
				$defaultParams = "1";
				break;
			case "ENUM":
				$type = "enum";
				$defaultParams = "1";
				break;
			default:
				throw new LogicException($type." is not known");
				break;
		}

		$sql .= $type.(is_null($setParams) ? (empty($defaultParams) ? "" : "(".$defaultParams.")") : "(".$setParams.")");

		$sql .= " ".(strpos($col[0], "?") === 0 ? "DEFAULT NULL" : "NOT NULL");

		if (strpos($col[0], "=") !== false) {
			$sql .= " DEFAULT ".explode("=", $col[0])[1];
		}

		if ($colname == "ID") {
			$sql .= " AUTO_INCREMENT";
		}

		$sql .= $end."\n";
	}

	$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";
}

echo "\n\n".$sql;
