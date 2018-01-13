<?php

namespace Catalyst\Integrations;

class SocialMedia {
	public static function getUserDisplayFromDatabase(\Catalyst\User\User $user) : array {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ID`,`NETWORK`,`SERVICE_URL`,`DISP_NAME` FROM `".DB_TABLES["user_social_media"]."` WHERE `USER_ID` = :USER_ID ORDER BY `SORT` ASC;");
		$id = $user->getId();
		$stmt->bindParam(":USER_ID", $id);
		$stmt->execute();

		$result = $stmt->fetchAll();

		$stmt->closeCursor();

		return $result;
	}

	public static function getArtistDisplayFromDatabase(\Catalyst\Artist\Artist $artist) : array {
		$stmt = $GLOBALS["dbh"]->prepare("SELECT `ID`,`NETWORK`,`SERVICE_URL`,`DISP_NAME` FROM `".DB_TABLES["artist_social_media"]."` WHERE `ARTIST_ID` = :ARTIST_ID ORDER BY `SORT` ASC;");
		$id = $artist->getId();
		$stmt->bindParam(":ARTIST_ID", $id);
		$stmt->execute();

		$result = $stmt->fetchAll();

		$stmt->closeCursor();

		return $result;
	}

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

	public static function getChipHTML(array $chips) : string {
		$result = "";

		foreach ($chips as $chip) {
			if (!is_null($chip["href"])) {
				$result .= '<a target="_blank" href="'.htmlspecialchars($chip["href"]).'">';
			}
			$result .= '<div class="chip hoverable tooltipped '.$chip["classes"].'" data-id="'.$chip["id"].'" data-tooltip="'.$chip["tooltip"].'" data-position="bottom" data-delay="50">';
			$result .= '<img src="'.htmlspecialchars($chip["src"]).'" />';
			$result .= htmlspecialchars($chip["label"]);
			if (preg_match("/Dashboard\\/.*\.php$/", $_SERVER["PHP_SELF"]) || preg_match("/Artist\\/Edit\\/.*\.php$/", $_SERVER["PHP_SELF"])) {
				$result .= '<i class="material-icons">clear</i>';
			}
			$result .= '</div>';
			if (!is_null($chip["href"])) {
				$result .= '</a>';
			}
		}

		return $result;
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

	public static function getUserChipHTML(\Catalyst\User\User $user) : string {
		return self::getChipHTML(self::getChipArray(self::getUserDisplayFromDatabase($user)));
	}

	public static function getArtistChipHTML(\Catalyst\Artist\Artist $artist) : string {
		return self::getChipHTML(self::getChipArray(self::getArtistDisplayFromDatabase($artist)));
	}
}
