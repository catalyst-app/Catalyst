<?php

namespace Catalyst\CommissionType;

use \Catalyst\Images\{Folders, HasImageTrait, Image};
use \InvalidArgumentException;

class CommissionType {
	use HasImageTrait;

	private $id;

	private $cache = [];

	public function __construct(int $id) {
		$stmt = $GLOBALS["dbh"]->prepare("
			SELECT
				`".DB_TABLES["commission_types"]."`.`ARTIST_PAGE_ID`,
				`".DB_TABLES["commission_types"]."`.`TOKEN`,
				`".DB_TABLES["commission_types"]."`.`NAME`,
				`".DB_TABLES["commission_types"]."`.`BLURB`,
				`".DB_TABLES["commission_types"]."`.`DESCRIPTION`,
				`".DB_TABLES["commission_types"]."`.`SORT`,
				`".DB_TABLES["commission_types"]."`.`BASE_COST`,
				`".DB_TABLES["commission_types"]."`.`BASE_USD_COST`,
				`".DB_TABLES["commission_types"]."`.`ATTRS`,
				`".DB_TABLES["commission_types"]."`.`OPEN`,
				`".DB_TABLES["commission_type_images"]."`.`CAPTION`,
				`".DB_TABLES["commission_type_images"]."`.`PATH`,
				`".DB_TABLES["commission_type_images"]."`.`NSFW`,
				`".DB_TABLES["commission_type_images"]."`.`PRIMARY`
			FROM
				`".DB_TABLES["commission_types"]."`
			LEFT JOIN
				`".DB_TABLES["commission_type_images"]."`
				ON
					`".DB_TABLES["commission_types"]."`.`ID` = `".DB_TABLES["commission_type_images"]."`.`COMMISSION_TYPE_ID`
			WHERE
				`".DB_TABLES["commission_types"]."`.`ID` = :ID
			ORDER BY
				`".DB_TABLES["commission_type_images"]."`.`SORT`;");
		$stmt->bindParam(":ID", $id);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			throw new InvalidArgumentException("Commission type ID ".$id." does not exist in the database.");
		}

		$results = $stmt->fetchAll();
		$stmt->closeCursor();

		$this->cache = [
			"ARTIST_PAGE_ID" => $results[0]["ARTIST_PAGE_ID"],
			"TOKEN" => $results[0]["TOKEN"],
			"NAME" => $results[0]["NAME"],
			"BLURB" => $results[0]["BLURB"],
			"DESCRIPTION" => $results[0]["DESCRIPTION"],
			"SORT" => $results[0]["SORT"],
			"BASE_COST" => $results[0]["BASE_COST"],
			"BASE_USD_COST" => $results[0]["BASE_USD_COST"],
			"ATTRS" => $results[0]["ATTRS"],
			"PHYSICAL_ADDR_NEEDED" => (bool)$results[0]["PHYSICAL_ADDR_NEEDED"],
			"OPEN" => $results[0]["OPEN"],
			"IMAGES" => (
					is_null($results[0]["PATH"])
				?
					[["default.png", "", false, true]]
				:
					array_map(function($in) {
						return [$in["TOKEN"].$in["PATH"], $in["CAPTION"], (bool)$in["NSFW"], (bool)$in["PRIMARY"]];
					}, $results)
			)
		];

		$this->id = $id;
	}

	public static function getIdFromToken(string $token) : int {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ID` FROM `".DB_TABLES["commission_types"]."` WHERE `TOKEN` = :TOKEN AND `DELETED` = 0;");
		$stmt->bindParam(":TOKEN", $token);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			return -1;
		}

		$result = $stmt->fetchAll()[0]["ID"];

		$stmt->closeCursor();

		return $result;
	}

	public function getId() : int {
		return $this->id;
	}

	public function getArtistPageId() : int {
		if (array_key_exists("ARTIST_PAGE_ID", $this->cache)) {
			return $this->cache["ARTIST_PAGE_ID"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ARTIST_PAGE_ID` FROM `".DB_TABLES["commission_types"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["TOKEN"] = $stmt->fetchAll()[0]["TOKEN"];

		$stmt->closeCursor();

		return $result;
	}

	public function getArtistPage() : \Catalyst\Artist\Artist {
		return (new \Catalyst\Artist\Artist($this->getArtistPageId()));
	}

	public function getToken() : string {
		if (array_key_exists("TOKEN", $this->cache)) {
			return $this->cache["TOKEN"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `TOKEN` FROM `".DB_TABLES["commission_types"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["TOKEN"] = $stmt->fetchAll()[0]["TOKEN"];

		$stmt->closeCursor();

		return $result;
	}

	public function getName() : string {
		if (array_key_exists("NAME", $this->cache)) {
			return $this->cache["NAME"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `NAME` FROM `".DB_TABLES["commission_types"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["NAME"] = $stmt->fetchAll()[0]["NAME"];

		$stmt->closeCursor();

		return $result;
	}

	public function getBlurb() : string {
		if (array_key_exists("BLURB", $this->cache)) {
			return $this->cache["BLURB"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `BLURB` FROM `".DB_TABLES["commission_types"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["BLURB"] = $stmt->fetchAll()[0]["BLURB"];

		$stmt->closeCursor();

		return $result;
	}

	public function getDescription() : string {
		if (array_key_exists("DESCRIPTION", $this->cache)) {
			return $this->cache["DESCRIPTION"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `DESCRIPTION` FROM `".DB_TABLES["commission_types"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["DESCRIPTION"] = $stmt->fetchAll()[0]["DESCRIPTION"];

		$stmt->closeCursor();

		return $result;
	}

	public function getSort() : int {
		if (array_key_exists("SORT", $this->cache)) {
			return $this->cache["SORT"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `SORT` FROM `".DB_TABLES["commission_types"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["SORT"] = $stmt->fetchAll()[0]["SORT"];

		$stmt->closeCursor();

		return $result;
	}

	public function getBaseCost() : string {
		if (array_key_exists("BASE_COST", $this->cache)) {
			return $this->cache["BASE_COST"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `BASE_COST` FROM `".DB_TABLES["commission_types"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["BASE_COST"] = $stmt->fetchAll()[0]["BASE_COST"];

		$stmt->closeCursor();

		return $result;
	}

	public function getBaseCostUsd() : string {
		if (array_key_exists("BASE_USD_COST", $this->cache)) {
			return $this->cache["BASE_USD_COST"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `BASE_USD_COST` FROM `".DB_TABLES["commission_types"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["BASE_USD_COST"] = $stmt->fetchAll()[0]["BASE_USD_COST"];

		$stmt->closeCursor();

		return $result;
	}

	public function getHumanAttrs() : array {
		if (array_key_exists("HUMAN_ATTRS", $this->cache)) {
			return $this->cache["HUMAN_ATTRS"];
		}

		$result = $this->getAttrs();

		$attrs = \Catalyst\Database\CommissionType\Attributes::get();

		$keyedAttrs = [];

		foreach ($attrs as $attrg) {
			$i = 0;
			foreach (array_intersect($result, array_column($attrg["items"], 0)) as $attr) {
				if ($i++ == 0) {
					$keyedAttrs[$attrg["label"]] = [];
				}
				$keyedAttrs[$attrg["label"]][] = array_values(array_filter($attrg["items"], function($in) use ($attr) {
					return $in[0] == $attr;
				}))[0][1];
			}
		}

		$result = $this->cache["HUMAN_ATTRS"] = $keyedAttrs;

		return $result;
	}

	public function getAttrs() : array {
		if (array_key_exists("ATTRS", $this->cache)) {
			return json_decode($this->cache["ATTRS"], true);
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ATTRS` FROM `".DB_TABLES["commission_types"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["ATTRS"] = $stmt->fetchAll()[0]["ATTRS"];

		$stmt->closeCursor();

		return json_decode($result, true);
	}

	public function isPhysicalAddrNeeded() : bool {
		if (array_key_exists("PHYSICAL_ADDR_NEEDED", $this->cache)) {
			return $this->cache["PHYSICAL_ADDR_NEEDED"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `PHYSICAL_ADDR_NEEDED` FROM `".DB_TABLES["commission_types"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["PHYSICAL_ADDR_NEEDED"] = (bool)$stmt->fetchAll()[0]["PHYSICAL_ADDR_NEEDED"];

		$stmt->closeCursor();

		return $result;
	}

	public function isOpen() : bool {
		if (array_key_exists("OPEN", $this->cache)) {
			return $this->cache["OPEN"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `OPEN` FROM `".DB_TABLES["commission_types"]."` WHERE `ID` = :ID;");
		$stmt->bindParam(":ID", $this->id);
		$stmt->execute();

		$result = $this->cache["OPEN"] = $stmt->fetchAll()[0]["OPEN"];

		$stmt->closeCursor();

		return $result;
	}

	public function getImages() : array {
		if (array_key_exists("IMAGES", $this->cache)) {
			return $this->cache["IMAGES"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `CAPTION`, `PATH`, `NSFW`, `PRIMARY` FROM `".DB_TABLES["commission_type_images"]."` WHERE `COMMISSION_TYPE_ID` = :ARTIST_PAGE_ID ORDER BY `SORT` ASC;");
		$stmt->bindParam(":COMMISSION_TYPE_ID", $this->id);
		$stmt->execute();

		$result = $this->cache["IMAGES"] = (
				$stmt->rowCount() == 0
			?
				[["default.png", "", false, true]]
			:
				array_map(function($in) {
					return [$this->getToken().$in["PATH"], $in["CAPTION"], (bool)$in["NSFW"], (bool)$in["PRIMARY"]];
				}, $stmt->fetchAll())
		);

		$stmt->closeCursor();

		return $result;
	}

	public function getPrimaryImage() : array {
		$imagesThatArePrimary = array_values(array_filter($this->getImages(), function($in) { return $in[3]; }));
		return (count($imagesThatArePrimary) ? $imagesThatArePrimary[0] : ["default.png", "", false, true]);
	}

	public function getPrimaryImagePath() : ?string {
		$imagesThatArePrimary = array_values(array_filter($this->getImages(), function($in) { return $in[3]; }));
		if (count($imagesThatArePrimary)) {
			return str_replace($this->getToken(), "", $imagesThatArePrimary[0][0]); // TODO: REMOVE THE STR REPLACE
		} else {
			return null;
		}
	}

	public function isPrimaryImageNsfw() : bool {
		$imagesThatArePrimary = array_values(array_filter($this->getImages(), function($in) { return $in[3]; }));
		if (count($imagesThatArePrimary)) {
			return $imagesThatArePrimary[0][2];
		} else {
			return false;
		}
	}

	public function getModifiers() : array {
		if (array_key_exists("MODS", $this->cache)) {
			return $this->cache["MODS"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `NAME`, `PRICE`, `USDEQ`, `GROUP`, `MULTIPLE` FROM `".DB_TABLES["commission_type_modifiers"]."` WHERE `COMMISSION_TYPE_ID` = :COMMISSION_TYPE_ID AND `DELETED` = 0;");
		$stmt->bindParam(":COMMISSION_TYPE_ID", $this->id);
		$stmt->execute();

		$db = $stmt->fetchAll();
		$stmt->closeCursor();

		$arr = [];

		foreach ($db as $row) {
			if (!isset($arr[$row["GROUP"]])) {
				$arr[$row["GROUP"]] = [
					"multiple" => $row["MULTIPLE"],
					"items" => []
				];
			}
			$arr[$row["GROUP"]]["items"][] = [
				$row["NAME"],
				$row["PRICE"],
				$row["USDEQ"]
			];
		}

		$result = $this->cache["MODS"] = array_values($arr);

		return $result;
	}

	public function getPaymentOptions() : array {
		if (array_key_exists("PAYMENT_OPTIONS", $this->cache)) {
			return $this->cache["PAYMENT_OPTIONS"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `TYPE`, `ADDRESS`, `INSTRUCTIONS` FROM `".DB_TABLES["commission_type_payment_options"]."` WHERE `COMMISSION_TYPE_ID` = :COMMISSION_TYPE_ID AND `DELETED` = 0;");
		$stmt->bindParam(":COMMISSION_TYPE_ID", $this->id);
		$stmt->execute();

		$db = $stmt->fetchAll();
		$stmt->closeCursor();

		$result = $this->cache["PAYMENT_OPTIONS"] = array_map(function($in) {
			return [$in["TYPE"], $in["ADDRESS"], $in["INSTRUCTIONS"]];
		}, $db);

		return $result;
	}

	public function getStages() : array {
		if (array_key_exists("STAGES", $this->cache)) {
			return $this->cache["STAGES"];
		}

		$stmt = $GLOBALS["dbh"]->prepare("SELECT `STAGE` FROM `".DB_TABLES["commission_type_stages"]."` WHERE `COMMISSION_TYPE_ID` = :COMMISSION_TYPE_ID AND `DELETED` = 0;");
		$stmt->bindParam(":COMMISSION_TYPE_ID", $this->id);
		$stmt->execute();

		$stmt->closeCursor();

		$result = $this->cache["STAGES"] = array_column($stmt->fetchAll(), "STAGE");

		return $result;
	}

	public static function getForArtist(\Catalyst\Artist\Artist $artist) : array {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ID` FROM `".DB_TABLES["commission_types"]."` WHERE `ARTIST_PAGE_ID` = :ARTIST_PAGE_ID AND `DELETED` = 0 ORDER BY `SORT` ASC;");
		$aid = $artist->getId();
		$stmt->bindParam(":ARTIST_PAGE_ID", $aid);
		$stmt->execute();

		$rows = $stmt->fetchAll();

		$stmt->closeCursor();

		return array_map(function($in) { return new self($in["ID"]); }, $rows);
	}

	public function initializeImage() : void {
		$this->setImage(new Image(Folders::COMMISSION_TYPE_IMAGE, $this->getToken(), $this->getPrimaryImagePath(), $this->isPrimaryImageNsfw()));
	}
}
