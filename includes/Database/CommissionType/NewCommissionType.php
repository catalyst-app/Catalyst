<?php

namespace Catalyst\Database\CommissionType;

class NewCommissionType {
	public const SUCCESS = 0;
	public const NOT_LOGGED_IN = 1;
	public const NOT_AN_ARTIST = 2;
	public const NAME_INVALID = 3;
	public const BLURB_INVALID = 4;
	public const DESCRIPTION_INVALID = 5;
	public const BASE_COST_INVALID = 6;
	public const USD_BASE_COST_INVALID = 7;
	public const IMAGES_INVALID = 8;
	public const MODIFIERS_INVALID = 9;
	public const ATTRS_INVALID = 10;
	public const PAYMENT_OPTIONS_INVALID = 11;
	public const STAGES_INVALID = 12;
	public const PHYSICAL_ADDR_NEEDED_INVALID = 13;
	public const ERROR_UNKNOWN = 14;

	public const PHRASES = [
		self::SUCCESS => "Success",
		self::NOT_LOGGED_IN => "You are not logged in.  Please refresh or try again.",
		self::NOT_AN_ARTIST => "You are not an artist.  Please create an artist page",
		self::NAME_INVALID => "Invalid name",
		self::BLURB_INVALID => "Invalid blurb",
		self::DESCRIPTION_INVALID => "Invalid description",
		self::BASE_COST_INVALID => "Invalid base cost",
		self::USD_BASE_COST_INVALID => "Invalid USD base cost (number only, 2 decimals max)",
		self::IMAGES_INVALID => "Invalid image(s)",
		self::MODIFIERS_INVALID => "Invalid modifiers",
		self::ATTRS_INVALID => "Invalid attributes",
		self::PAYMENT_OPTIONS_INVALID => "Invalid payment options",
		self::STAGES_INVALID => "Invalid stages",
		self::PHYSICAL_ADDR_NEEDED_INVALID => "Invalid value",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public static $lastErrId = -1;

	public static function getFormStructure() : array {
		return [
			[
				"distinguisher" => "newct",
				"ajax" => true,
				"eval" => 'window.location = "../EditCommissionTypes";',
				"method" => "POST",
				"handler" => "handler.php",
				"button" => "create",
				"additional_cases" => [
					self::NOT_LOGGED_IN => "alert('You are not logged in. Please refresh and try again');return;break;",
					self::NOT_AN_ARTIST => "alert('You are not an artist. Please refresh and try again');return;break;"
				],
				"additional_fields" => [],
				"success" => self::PHRASES[self::SUCCESS],
			],
			[
				"name" => "name",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"label" => "Name",
				"pattern" => ['^.{2,255}$', "2 to 255 characters"],
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::NAME_INVALID]],
				"error_code" => [self::NAME_INVALID],
			],
			[
				"name" => "blurb",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"label" => "Blurb",
				"pattern" => ['^.{2,255}$', "2 to 255 characters"],
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::BLURB_INVALID]],
				"error_code" => [self::BLURB_INVALID],
			],
			[
				"name" => "desc",
				"wrapper_classes" => "col s12",
				"type" => "markdown-textarea",
				"label" => "Description",
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::DESCRIPTION_INVALID]],
				"error_code" => [self::DESCRIPTION_INVALID],
			],
			[
				"name" => "basecost",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"label" => "Base cost",
				"pattern" => ['^.{1,64}$', "1 to 64 characters"],
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::BASE_COST_INVALID]],
				"error_code" => [self::BASE_COST_INVALID],
				"after_html" => '<p class="col s12 no-top-margin">The cost of the commission at its <i>lowest</i> (no modifiers or anything).  Please include units.</p>'
			],
			[
				"name" => "basecostusd",
				"wrapper_classes" => "col s12",
				"type" => "number",
				"label" => "Base cost in USD",
				"pattern" => ['^\d+(|\.\d{1,2})$', "Please use a valid number with maximum 2 digits"],
				"required" => true,
				"validate" => true,
				"error_text" => [self::PHRASES[self::USD_BASE_COST_INVALID]],
				"error_code" => [self::USD_BASE_COST_INVALID],
				"after_html" => '<p class="col s12 no-margin">This will be used for searching only and not shown to the buyer.</p><p class="col s12 no-top-margin"><a href="http://www.xe.com/currencyconverter/">Here</a> is a calculator you can use for most currencies.</p>',
				"other_attributes" => [
					"min" => "0",
					"max" => "9999.99",
					"step" => "0.01"
				]
			],
			[
				"name" => "imgs",
				"wrapper_classes" => "col s12",
				"type" => "image",
				"multiple" => true,
				"maxsize" => "2M",
				"label" => "Images (2MB limit ea.)",
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::IMAGES_INVALID]],
				"error_code" => [self::IMAGES_INVALID],
			],
			[
				"name" => "mods",
				"type" => "custom",
				"custom_js" => '',
				"custom_html" => '
	<div class="row col s12">
		<p>Modifiers</p>
		<p>These are options for a commission - think of it like a pizza order - which the commissioner may choose a multitude of.</p>
		<div id="modifier-container-container" class="col s12">
			<div class="divider col s12"></div>
			<div class="modifier-container">
				<div class="right right-align">
					<i class="material-icons small remove-modifier-container">clear</i>
					<p class="no-top-margin">
						<input type="checkbox" class="filled-in" id="'.($a=microtime(true)).'">
						<label for="'.$a.'">Multiple</label>
					</p>
				</div>
			</div>
			<div class="divider col s12"></div>
		</div>
		<div class="col s12">
			<div class="btn" id="add-commission-type-mod-container-btn">Add group</div>
		</div>
		<div class="col s12 l4 input-field">
			<input type="text" pattern="^.{2,60}$" maxlength="60" id="add-commission-type-mod-name" class="validate">
			<label for="add-commission-type-mod-name" data-error="2 to 64 characters">Name</label>
		</div>
		<div class="col s12 m6 l3 input-field">
			<input type="text" pattern="^.{2,64}$" id="add-commission-type-mod-cost" maxlength="64" class="validate">
			<label for="add-commission-type-mod-cost" data-error="2 to 64 characters">Cost (with units)</label>
		</div>
		<div class="col s12 m6 l3 input-field">
			<input type="number" step="0.01" min="0" max="9999.99" id="add-commission-type-mod-usd-cost" class="validate">
			<label for="add-commission-type-mod-usd-cost" data-error="Between $0 and $10000, maximum 2 decimals">USD equivalent (decimal)</label>
		</div>
		<div class="col s12 l2 btn-large" id="new-commission-type-add-modifier-btn">Add</div>
	</div>',
				"custom_js_getter" => 'var mods=JSON.stringify($(".modifier-container").get().map(function(e) {
	return {
		multiple: $(e).find("input[type=checkbox]").is(":checked"),
		mods: $(e).find(".btn").get().map(function(e) {
			return e.dataset;
		})
	};
}).filter(function(e) {
	return e.mods.length > 0;
}));',
				"custom_js_get_var" => 'mods',
				"error_text" => [self::PHRASES[self::MODIFIERS_INVALID]],
				"error_code" => [self::MODIFIERS_INVALID],
			],
			[
				"name" => "attrs",
				"type" => "custom",
				"custom_js" => '',
				"custom_html" => '
	<div class="row col s12">
		<p>Attributes</p>
		<p>These will be used when searching for your page.  Please select <strong>all relevant items</strong>.</p>
		<div id="attr-container-container" class="col s12">
			<div class="divider col s12"></div>
			'.implode("", array_map(function($in) {
				return '
				<p class="col s12 no-bottom-margin">'.$in["label"].'</p>
				<div class="attr-container col s12">'.
				implode("",array_map(function($in) {
					return '<div class="btn attr-button toggle-btn tooltipped off" data-key="'.htmlspecialchars($in[0]).'" data-tooltip="'.htmlspecialchars($in[2]).'" data-position="bottom" data-delay="10">'.htmlspecialchars($in[1]).'</div>';
				}, $in["items"])).
				'</div>
				<div class="divider col s12"></div>';
			}, \Catalyst\Database\CommissionType\Attributes::get())).'
		</div>
	</div>',
				"custom_js_getter" => '
	var attrs=JSON.stringify(
		$(".attr-button.toggle-btn.on").get().map(function(e) {
			return $(e).attr("data-key");
		})
	);',
				"custom_js_get_var" => 'attrs',
				"error_text" => [self::PHRASES[self::ATTRS_INVALID]],
				"error_code" => [self::ATTRS_INVALID],
			],
			[
				"name" => "payments",
				"type" => "custom",
				"custom_js" => '',
				"custom_html" => '
	<div class="row col s12">
		<p>Payment Types</p>
		<p>Please list the options your customers have to choose from when commissioning you.</p>
		<div id="payment-type-container" class="col s12">
		</div>
		<div class="col s12 m4 l3 input-field">
			<input type="text" pattern="^.{2,64}$" id="add-commission-type-payment-type" maxlength="64" class="validate">
			<label for="add-commission-type-payment-type" data-error="2 to 64 characters">Type</label>
		</div>
		<div class="col s12 m8 l9 input-field">
			<input type="text" id="add-commission-type-payment-type-addr" class="validate">
			<label for="add-commission-type-payment-type-addr" data-error="2 or more characters">Address</label>
		</div>
		<div class="col s12 input-field">
			<input type="text" id="add-commission-type-payment-type-instructions" class="validate">
			<label for="add-commission-type-payment-type-instructions" data-error="2 or more characters">Instructions</label>
		</div>
		<div class="btn" id="add-commission-type-payment-type-btn">Add</div>
	</div>',
				"custom_js_getter" => '
	var paymentTypes=JSON.stringify(
		$(".payment-type").get().map(function(e) {
			return e.dataset;
		})
	);',
				"custom_js_get_var" => 'paymentTypes',
				"error_text" => [self::PHRASES[self::PAYMENT_OPTIONS_INVALID]],
				"error_code" => [self::PAYMENT_OPTIONS_INVALID],
			],
			[
				"name" => "stages",
				"type" => "custom",
				"custom_js" => '',
				"custom_html" => '
	<div class="row col s12">
		<p>Stages</p>
		<p>Please list the stages which the commission will undergo (e.g. sketching, coloring, etc.).</p>
		<div id="stage-container" class="col s12">
		</div>
		<div class="col s12 input-field">
			<input type="text" id="add-commission-type-stage" maxlength="255" pattern="^.{2,255}$" class="validate">
			<label for="add-commission-type-stage" data-error="2 to 255 characters">Stage</label>
		</div>
		<div class="btn" id="add-commission-type-stage-btn">Add</div>
	</div>',
				"custom_js_getter" => '
	var stages=JSON.stringify(
		$(".stage").get().map(function(e) {
			return $(e).attr("data-stage");
		})
	);',
				"custom_js_get_var" => 'stages',
				"error_text" => [self::PHRASES[self::STAGES_INVALID]],
				"error_code" => [self::STAGES_INVALID],
			],
			[
				"name" => "addrneeded",
				"wrapper_classes" => "col s12",
				"type" => "checkbox",
				"label" => "A commissioner should provide their physical address (usually for shipping purposes)",
				"required" => false,
				"error_text" => [self::PHRASES[self::PHYSICAL_ADDR_NEEDED_INVALID]],
				"error_code" => [self::PHYSICAL_ADDR_NEEDED_INVALID]
			],
		];
	}

	public static function create(
		string $name,
		string $blurb,
		string $desc,
		string $basecost,
		float $basecostusd,
		?array $imgs,
		array $modGroups,
		array $attrs,
		array $payments,
		array $stages,
		bool $addrneeded,
		\Catalyst\Artist\Artist $artist
	) : int {
		$aid = $artist->getId();
		$stmt = $GLOBALS["dbh"]->prepare("
			SELECT
				`SORT`
			FROM
				`".DB_TABLES["commission_types"]."`
			WHERE
				`ARTIST_PAGE_ID` = :ARTIST_PAGE_ID;");
		$stmt->bindParam(":ARTIST_PAGE_ID", $aid);
		$stmt->execute();

		$sort = ($stmt->rowCount() ? (max(array_column($stmt->fetchAll(), "SORT"))+1) : 0);

		$stmt->closeCursor();

		$stmt = $GLOBALS["dbh"]->prepare("
			INSERT INTO `".DB_TABLES["commission_types"]."`
				(
					`ARTIST_PAGE_ID`,
					`TOKEN`,
					`NAME`,
					`BLURB`,
					`DESCRIPTION`,
					`SORT`,
					`BASE_COST`,
					`BASE_USDEQ`,
					`ATTRS`,
					`PHYSICAL_ADDR_NEEDED`
				)
			VALUES
				(
					:ARTIST_PAGE_ID,
					:TOKEN,
					:NAME,
					:BLURB,
					:DESCRIPTION,
					:SORT,
					:BASE_COST,
					:BASE_USDEQ,
					:ATTRS,
					:PHYSICAL_ADDR_NEEDED
				);
			");
		$token = \Catalyst\Tokens::generateUniqueCommissionTypeToken();
		$attrs = json_encode($attrs);
		$physicalint = $addrneeded ? 1 : 0;

		$stmt->bindParam(":ARTIST_PAGE_ID", $aid);
		$stmt->bindParam(":TOKEN", $token);
		$stmt->bindParam(":NAME", $name);
		$stmt->bindParam(":BLURB", $blurb);
		$stmt->bindParam(":DESCRIPTION", $desc);
		$stmt->bindParam(":SORT", $sort);
		$stmt->bindParam(":BASE_COST", $basecost);
		$stmt->bindParam(":BASE_USDEQ", $basecostusd);
		$stmt->bindParam(":ATTRS", $attrs);
		$stmt->bindParam(":PHYSICAL_ADDR_NEEDED", $physicalint);

		if (!$stmt->execute()) {
			error_log(" Add commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		$ctid = $GLOBALS["dbh"]->lastInsertId();

		$imgs = \Catalyst\Form\FileUpload::uploadImages($imgs, \Catalyst\Form\FileUpload::COMMISSION_TYPE_IMAGE, $token);

		if (!is_null($imgs)) {
			$stmt = $GLOBALS["dbh"]->prepare("
				INSERT INTO `".DB_TABLES["commission_type_images"]."`
					(
						`COMMISSION_TYPE_ID`,
						`CAPTION`,
						`PATH`,
						`NSFW`,
						`PRIMARY`,
						`SORT`
					)
				VALUES
					".implode(",", array_fill(0, count($imgs), "(?, '', ?, 0, ?, ?)"))."
				;");
			$arr = [];
			$i = 0;
			foreach ($imgs as $img) {
				$arr[] = $ctid;
				$arr[] = $img;
				$arr[] = 0;
				$arr[] = $i++;
			}
			$arr[2] = 1;
			if (!$stmt->execute($arr)) {
				error_log(" Add commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
				return self::ERROR_UNKNOWN;
			}
		}

		if (!empty($modGroups)) {
			$flattenedMods = [];
			$i = 0;
			foreach ($modGroups as $modGroup) {
				foreach ($modGroup["mods"] as $mod) {
					$flattenedMods[] = [
						$ctid,
						$mod["name"],
						$mod["cost"],
						$mod["costUsd"],
						$i,
						$modGroup["multiple"] ? 1 : 0
					];
				}
				$i++;
			}

			$stmt = $GLOBALS["dbh"]->prepare("
				INSERT INTO `".DB_TABLES["commission_type_modifiers"]."`
					(
						`COMMISSION_TYPE_ID`,
						`NAME`,
						`PRICE`,
						`USDEQ`,
						`GROUP`,
						`MULTIPLE`
					)
				VALUES
					".implode(",", array_fill(0, count($flattenedMods), "(?, ?, ?, ?, ?, ?)"))."
				;");
			if (!$stmt->execute(array_merge(...$flattenedMods))) {
				error_log(" Add commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
				return self::ERROR_UNKNOWN;
			}
		}

		if (!empty($payments)) {
			$arr = [];

			foreach ($payments as $payment) {
				$arr[] = $ctid;
				$arr[] = $payment["type"];
				$arr[] = $payment["addr"];
				$arr[] = $payment["instructions"];
			}

			$stmt = $GLOBALS["dbh"]->prepare("
				INSERT INTO `".DB_TABLES["commission_type_payment_options"]."`
					(
						`COMMISSION_TYPE_ID`,
						`TYPE`,
						`ADDRESS`,
						`INSTRUCTIONS`
					)
				VALUES
					".implode(",", array_fill(0, count($payments), "(?, ?, ?, ?)"))."
				;");
			if (!$stmt->execute($arr)) {
				error_log(" Add commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
				return self::ERROR_UNKNOWN;
			}
		}

		if (!empty($stages)) {
			$arr = [];

			foreach ($stages as $stage) {
				$arr[] = $ctid;
				$arr[] = $stage;
			}

			$stmt = $GLOBALS["dbh"]->prepare("
				INSERT INTO `".DB_TABLES["commission_type_stages"]."`
					(
						`COMMISSION_TYPE_ID`,
						`STAGE`
					)
				VALUES
					".implode(",", array_fill(0, count($stages), "(?, ?)"))."
				;");
			if (!$stmt->execute($arr)) {
				error_log(" Add commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
				return self::ERROR_UNKNOWN;
			}
		}

		return self::SUCCESS;
	}
}
