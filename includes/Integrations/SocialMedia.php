<?php

namespace Catalyst\Integrations;

/**
 * Represents social-media related things
 */
class SocialMedia {
	/**
	 * All the metadata for the social chips, filled by getMeta
	 * @var array|null
	 */
	protected static $meta = null;

	// DEPRECATED
	public static function getArtistDisplayFromDatabase(\Catalyst\Artist\Artist $artist) : array {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ID`,`NETWORK`,`SERVICE_URL`,`DISP_NAME` FROM `".DB_TABLES["artist_social_media"]."` WHERE `ARTIST_ID` = :ARTIST_ID ORDER BY `SORT` ASC;");
		$id = $artist->getId();
		$stmt->bindParam(":ARTIST_ID", $id);
		$stmt->execute();

		$result = $stmt->fetchAll();

		$stmt->closeCursor();

		return $result;
	}

	/**
	 * Get meta information
	 * 
	 * @return array Associative array of response from database
	 */
	public static function getMeta() : array {
		if (!is_null(self::$meta)) {
			return self::$meta;
		}

		$stmt = new SelectQuery();

		$stmt->setTable(Tables::INTEGRATIONS_META);
		
		$stmt->addColumn(new Column("VISIBLE", Tables::INTEGRATIONS_META));
		$stmt->addColumn(new Column("INTEGRATION_NAME", Tables::INTEGRATIONS_META));
		$stmt->addColumn(new Column("IMAGE_PATH", Tables::INTEGRATIONS_META));
		$stmt->addColumn(new Column("DEFAULT_HUMAN_NAME", Tables::INTEGRATIONS_META));
		$stmt->addColumn(new Column("CHIP_CLASSES", Tables::INTEGRATIONS_META));

		$orderClause = new OrderByClause(new Column("SORT_ORDER", "ASC"));
		$stmt->addAdditionalCapability($orderClause);

		$stmt->execute();

		self::$meta = $stmt->getResult();

		return self::$meta;
	}

	/**
	 * Get a properly-structured array from a given set of chips
	 * 
	 * [
	 * id => int (0 by default),
	 * src => string|null
	 * label => string
	 * href => string
	 * classes => string (from Meta)
	 * tooltip => string (from Meta)
	 * ]
	 * 
	 * @return array
	 */
	public static function getChipArray(array $rows) : array {
		$result = [];

		$meta = \Catalyst\Database\Integrations\Meta::get();

		foreach ($rows as $row) {
			$result[] = [
				"id" => array_key_exists("ID", $row) ? $row["ID"] : 0,
				"src" => $meta[$row["NETWORK"]][0],
				"label" => $row["DISP_NAME"],
				"href" => $row["SERVICE_URL"],
				"classes" => $meta[$row["NETWORK"]][2],
				"tooltip" => $meta[$row["NETWORK"]][1]
			];
		}

		return $result;
	}

	public static function getChipHTML(array $chips, bool $showClearButton=false) : string {
		$result = '<div class="center-on-small-only">';

		foreach ($chips as $chip) {
			if (!is_null($chip["href"])) {
				$result .= '<a target="_blank" href="'.htmlspecialchars($chip["href"]).'">';
			}
			$result .= '<div class="chip hoverable tooltipped '.$chip["classes"].'" data-id="'.$chip["id"].'" data-tooltip="'.$chip["tooltip"].'" data-position="bottom" data-delay="50">';
			$result .= '<img src="'.htmlspecialchars($chip["src"]).'" />';
			$result .= htmlspecialchars($chip["label"]);
			if ($showClearButton) {
				$result .= '<i class="material-icons">clear</i>';
			}
			$result .= '</div>';
			if (!is_null($chip["href"])) {
				$result .= '</a>';
			}
		}

		return $result.'</div>';
	}

	public static function getAddChip() : string {
		$result  = '<a class="modal-trigger" href="#add-social-network-modal">';
		$result .= '<div class="chip hoverable user-color white-text">';
		$result .= 'Add network';
		$result .= '<i class="material-icons">add</i>';
		$result .= '</div>';
		$result .= '</a>';

		return $result;
	}

	public static function getAddModal() : string {
		$result  = '<div id="add-social-network-modal" class="modal modal-fixed-footer">';
		$result .= '<div class="modal-content">';
		$result .= \Catalyst\Form\FormHTML::generateForm(\Catalyst\Database\SocialMedia::getFormStructure());
		$result .= '</div>';
		$result .= '</div>';

		return $result;
	}

	public static function getArtistChipHTML(\Catalyst\Artist\Artist $artist) : string {
		return self::getChipHTML(self::getChipArray(self::getArtistDisplayFromDatabase($artist)));
	}
}
