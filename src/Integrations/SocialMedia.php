<?php

namespace Catalyst\Integrations;

use \Catalyst\Database\{Column, Tables};
use \Catalyst\Database\QueryAddition\OrderByClause;
use \Catalyst\Database\Query\SelectQuery;
use \Catalyst\Form\FormRepository;
use \Catalyst\Images\Folders;

/**
 * Represents social-media related things
 */
class SocialMedia {
	/**
	 * All the metadata for the social chips, filled by getMeta
	 * @var array|null
	 */
	protected static $meta = null;

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

		$orderClause = new OrderByClause();
		$orderClause->setColumn(new Column("SORT_ORDER", Tables::INTEGRATIONS_META));
		$orderClause->setOrder("ASC");
		$stmt->addAdditionalCapability($orderClause);

		$stmt->execute();

		self::$meta = array_merge(...array_map([__CLASS__, "processMetaRow"], $stmt->getResult()));

		return self::$meta;
	}

	/**
	 * Processes a database meta row into a proper associative array
	 * 
	 * @param array $row Database row
	 * @return array Processed row, name => {path, name, classes, visible}
	 */
	protected static function processMetaRow(array $row) : array {
		return [
			$row["INTEGRATION_NAME"] => [
				"path" => ROOTDIR.Folders::INTEGRATION_ICONS."/".$row["IMAGE_PATH"],
				"name" => $row["DEFAULT_HUMAN_NAME"],
				"classes" => $row["CHIP_CLASSES"],
				"visible" => $row["VISIBLE"]
			]
		];
	}

	/**
	 * Get an array of options for use with a SelectField
	 * 
	 * @return string[][]
	 */
	public static function getOtherNetworkAddSelectArray() : array {
		$arr = array_filter(self::getMeta(), function($in) { return $in["visible"]; });
		return array_map(function($a, $b) {
			return [$a, $b["name"]];
		}, array_keys($arr), $arr);
	}

	/**
	 * G(uesses)ets a integration type from a URL
	 * 
	 * @param string $url URL to use
	 * @return string Best guess as to what the type is
	 */
	public static function getTypeFromUrl(string $url) : string {
		if (strpos($url, "mailto:") === 0) {
			return "EMAIL";
		}
		$url = str_replace("www.", "", $url);
		$typeRegexDefs = [
			['/500px\.com$/', '500PX'],
			['/aminoapps\.com$/', 'AMINO'],
			['/archiveofourown\.org$/', 'AO3'],
			['/bandcamp\.com$/', 'BANDCAMP'],
			['/deviantart\.com$/', 'DEVIANTART'],
			['/(discord\.gg|discordapp\.com|watchanimeattheoffice\.com|bigbeans\.solutions|dis\.gd)$/', 'DISCORD'],
			['/etsy\.com$/', 'ETSY'],
			['/(facebook\.com|akamaihd\.net|fbcdn\.net|fb\.me|fbsbx\.com)$/', 'FACEBOOK'],
			['/fanfiction\.net$/', 'FANFICTION'],
			['/(furaffinity\.net|facdn\.net)$/', 'FURAFFINITY'],
			['/furrynetwork\.com$/', 'FURRYNETWORK'],
			['/(googleusercontent\.com|plus\.google\.com)$/', 'GPLUS'],
			['/inkbunny\.net$/', 'INKBUNNY'],
			['/(instagram\.com|cdninstagram\.com)$/', 'INSTAGRAM'],
			['/ko-fi\.com$/', 'KOFI'],
			['/patreon\.com$/', 'PATREON'],
			['/paypal\.(me|com)$/', 'PAYPAL'],
			['/pscp\.tv$/', 'PERISCOPE'],
			['/picarto\.tv$/', 'PICARTO'],
			['/pinterest\.com$/', 'PINTEREST'],
			['/(redd\.it|reddit.com|reddit(static|media)\.com)$/', 'REDDIT'],
			['/catalystapp\.co$/', 'SELF'],
			['/snapchat\.com$/', 'SNAPCHAT'],
			['/(sofurry\.com|is(feathery|fluffy|furry|rubbery|scaly|winged)\.com)$/', 'SOFURRY'],
			['/(snd\.sc|soundcloud\.com)$/', 'SOUNDCLOUD'],
			['/spotify\.com$/', 'SPOTIFY'],
			['/((3|5)games1box\.com|aheadofthegame(the)?movie\.com|aperturelabo?ratories\.com|aperturescience\.com|as32590\.net|counter-?strike(tv|\d)?\.(com|net)(\.tw)?|counter-?strike(\.com)?\.tw|cs-conditionzero\.com|csonline\.com\.t(r|w)|csoturkey\.(info|net|com\.tr)|dayofdefeat(\d|mod|tv)?\.(com|net)|deathmatchclassic\.com|do(d|ta)\d?\.(net|com)|freetoplaythemovie\.com|gamerlife(the)?movie\.com|getinsidetheorangebox\.com|(half-?life|hl)\d?(|themovie|movie|portal|sucks|minerva|mac|auth)\.(com|net|org)|l4d2?\.com(\.cn)?|learn(ing)?withportals\.com|leftfourdead\.(com|net)|midnight-riders\.(com|net)|minervametastasis\.com|modexpo\.com|opposing-?force\.(com|net|org)|portal2-?(the)?game\.(com|net)|poweredbysteam\.net|shopatvalve\.com|sourcefilmmaker\.com|steam(|community|content|cybercafe|devdays|games|moves|movies|powered(games)?|server|static|user(content|s)|vr)\.(net|com|org|co\.nz|us)|teachwithportals\.com|team-?fortress(|classic|2|ii|tv)\.com|tf-?(classic|ii|2|c|source)\.(com|net|org)|theheartofracing\.org|thinkwithportals\.com|valve(store|sucks|s?softwares?|corp(|orat(e|ion)))?\.(net|org|com)|what(is|sinside)theorangebox\.com)$/', 'STEAM'],
			['/(t\.me|telegram\.(me|dog))$/', 'TELEGRAM'],
			['/trello\.com$/', 'TRELLO'],
			['/tumblr\.com$/', 'TUMBLR'],
			['/twitch\.tv$/', 'TWITCH'],
			['/(t\.co|twitter\.com)$/', 'TWITTER'],
			['/vimeo\.com$/', 'VIMEO'],
			['/weasyl\.com$/', 'WEASYL'],
			['/(youtube\.com|youtu\.be)$/', 'YOUTUBE'],
		];
		$host = parse_url($url, PHP_URL_HOST);
		foreach ($typeRegexDefs as list($regex, $type)) {
			if (preg_match($regex, strtolower($host))) {
				return $type;
			}
		}

		if (empty(parse_url($url, PHP_URL_PATH))) {
			return "DOMAIN";
		}

		return "CUSTOM";
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

		$meta = self::getMeta();

		foreach ($rows as $row) {
			$result[] = [
				"id" => array_key_exists("ID", $row) ? $row["ID"] : 0,
				"src" => $meta[$row["NETWORK"]]["path"],
				"label" => $row["DISP_NAME"],
				"href" => ($row["NETWORK"] == "EMAIL" ? "mailto:".$row["SERVICE_URL"] : $row["SERVICE_URL"]),
				"classes" => $meta[$row["NETWORK"]]["classes"],
				"tooltip" => $meta[$row["NETWORK"]]["name"]
			];
		}

		return $result;
	}

	/**
	 * Get the HTML for a given chip item (as generated by getChipArray)
	 * 
	 * @param array $chips
	 * @param bool $editMode If the chip is for editing
	 */
	public static function getChipHtml(array $chips, bool $editMode=false) : string {
		$str = '';
		$str .= '<div';
		$str .= ' class="center-on-small-only"';
		$str .= '>';

		foreach ($chips as $chip) {
			// we wrap the chip with a link
			if (!$editMode && isset($chip["href"]) && !is_null($chip["href"])) {
				$str .= '<a';
				$str .= ' target="_blank"';
				$str .= ' href="'.htmlspecialchars($chip["href"]).'"';
				$str .= '>';
			}

			$str .= '<div';
			$str .= ' class="';
			$str .= 'chip';
			$str .= ' hoverable';
			$str .= ' '.$chip["classes"];
			if ($editMode) {
				$str .= ' has-icon';
			} else {
				$str .= ' tooltipped';
			}
			$str .= '"';

			if (!$editMode) {
				$str .= ' data-tooltip="'.$chip["tooltip"].'"';
				$str .= ' data-position="bottom"';
				$str .= ' data-delay="50"';
			} else {
				$str .= ' data-id="'.$chip["id"].'"';
			}
			
			$str .= '>';

			$str .= '<img';
			$str .= ' src="'.htmlspecialchars($chip["src"]).'"';
			$str .= ' />';

			$str .= htmlspecialchars($chip["label"]);

			if ($editMode) {
				$str .= '<i';
				$str .= ' class="material-icons"';
				$str .= '>';
				$str .= 'clear';
				$str .= '</i>';
			}

			$str .= '</div>';
			
			if (!$editMode && isset($chip["href"]) && !is_null($chip["href"])) {
				$str .= '</a>';
			}
		}

		$str.= '</div>';

		return $str;
	}

	public static function getAddChip() : string { return self::getAddChipHtml(); } // BC, DEPRECATED

	/**
	 * Get "Add Network" chip
	 * 
	 * @return string Chip HTML
	 */
	public static function getAddChipHtml() : string {
		$str = '';
		$str .= '<a';
		$str .= ' class="modal-trigger"';
		$str .= ' href="#add-social-link-modal"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="chip hoverable user-color white-text"';
		$str .= '>';

		$str .= 'Add link or e-mail';
		
		$str .= '<i';
		$str .= ' class="material-icons"';
		$str .= '>';
		$str .= 'add';
		$str .= '</i>';

		$str .= '</div>';
		
		$str .= '</a>';

		$str .= '<a';
		$str .= ' class="modal-trigger"';
		$str .= ' href="#add-social-other-modal"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="chip hoverable user-color white-text"';
		$str .= '>';

		$str .= 'Add other';
		
		$str .= '<i';
		$str .= ' class="material-icons"';
		$str .= '>';
		$str .= 'add';
		$str .= '</i>';

		$str .= '</div>';
		
		$str .= '</a>';

		return $str;
	}

	/**
	 * Get the modals and forms for adding social networks
	 * 
	 * @param string $destination If the destination is Artist or User, MUST match one of these two
	 * @return string HTML
	 */
	public static function getAddModal(string $destination="User") : string {
		$str = '';
		
		$str .= '<input';
		$str .= ' type="hidden"';
		$str .= ' id="social-dest-type"';
		$str .= ' value="'.htmlspecialchars($destination).'"';
		$str .= '>';

		$str .= '<div';
		$str .= ' id="add-social-link-modal"';
		$str .= ' class="modal modal-fixed-footer"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="modal-content"';
		$str .= '>';

		$str .= FormRepository::getAddNetworkLinkForm()->getHtml(false);

		$str .= '</div>';

		$str .= '</div>';

		$str .= '<div';
		$str .= ' id="add-social-other-modal"';
		$str .= ' class="modal modal-fixed-footer"';
		$str .= '>';

		$str .= '<div';
		$str .= ' class="modal-content"';
		$str .= '>';

		$str .= FormRepository::getAddNetworkOtherForm()->getHtml(false);

		$str .= '</div>';

		$str .= '</div>';

		return $str;
	}
}
