<?php

namespace Catalyst\Database\CommissionType;

class EditCommissionType {
	public const SUCCESS = 0;
	public const NOT_LOGGED_IN = 1;
	public const NOT_AN_ARTIST = 2;
	public const INVALID_CT = 2;
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
	public const OPEN_INVALID = 14;
	public const ERROR_UNKNOWN = 15;

	public const PHRASES = [
		self::SUCCESS => "Success",
		self::NOT_LOGGED_IN => "You are not logged in.  Please refresh or try again.",
		self::NOT_AN_ARTIST => "You are not an artist.  Please create an artist page",
		self::INVALID_CT => "You are not an artist.  Please create an artist page",
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
		self::PHYSICAL_ADDR_NEEDED_INVALID => "Invalid checkbox value",
		self::OPEN_INVALID => "Invalid checkbox value",
		self::ERROR_UNKNOWN => "An unknown error has occured.  Please try again.  If the problem persists, please contact support.  Error ID: ",
	];

	public static $lastErrId = -1;

	public static function getFormStructure(\Catalyst\CommissionType\CommissionType $type=null) : array {
		return [
			[
				"distinguisher" => "editct",
				"ajax" => true,
				"eval" => 'window.location = "../EditCommissionTypes";',
				"method" => "POST",
				"handler" => "handler.php",
				"button" => "update",
				"additional_cases" => [
					self::NOT_LOGGED_IN => "alert('You are not logged in. Please refresh and try again');return;break;",
					self::NOT_AN_ARTIST => "alert('You are not an artist. Please refresh and try again');return;break;",
					self::INVALID_CT => "alert('This is not your commission type. Please refresh and try again');return;break;"
				],
				"additional_fields" => [
					"token" => '$("input.token-input").val()'
				],
				"success" => self::PHRASES[self::SUCCESS],
			],
			[
				"name" => "name",
				"wrapper_classes" => "col s12",
				"type" => "text",
				"label" => "Name",
				"default" => is_null($type) ? "" : $type->getName(),
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
				"default" => is_null($type) ? "" : $type->getBlurb(),
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
				"default" => is_null($type) ? "" : $type->getDescription(),
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
				"default" => is_null($type) ? "" : $type->getBaseCost(),
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
				"default" => is_null($type) ? "" : $type->getBaseCostUsd(),
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
				"maxsize" => "10M",
				"label" => "Images (10MB limit ea.)",
				"required" => false,
				"validate" => true,
				"error_text" => [self::PHRASES[self::IMAGES_INVALID]],
				"error_code" => [self::IMAGES_INVALID],
				"after_html" => '<p class="col s12 no-margin">Only use this if you would like to add examples</p><p class="col s12 no-top-margin">Go <a href="'.ROOTDIR.'Artist/EditCommissionTypeImages/'.(is_null($type) ? '' : $type->getToken()).'">here</a> to edit existing images</p>'
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
			<div class="divider col s12"></div>'.
			(function() use ($type) {
				if (is_null($type)) {
					return '';
				}
				$mods = $type->getModifiers();
				if (count($mods) == 0) {
					return '
					<div class="modifier-container">
						<div class="right right-align">
							<i class="material-icons small remove-modifier-container">clear</i>
							<p class="no-top-margin">
								<input type="checkbox" class="filled-in" id="'.($a=microtime(true)).'">
								<label for="'.$a.'">Multiple</label>
							</p>
						</div>
					</div>
					<div class="divider col s12"></div>';
				}
				$str = '';
				foreach ($mods as $modg) {
					$str .= '
					<div class="modifier-container">
						<div class="right right-align">
							<i class="material-icons small remove-modifier-container">clear</i>
							<p class="no-top-margin">
								<input type="checkbox"'.($modg["multiple"] ? ' checked="checked"' : '').' class="filled-in" id="'.($a=(microtime(true).rand())).'">
								<label for="'.$a.'">Multiple</label>
							</p>
						</div>';
					foreach ($modg["items"] as $mod) {
						$str .= '<div class="btn commission-type-mod" data-name="'.htmlspecialchars($mod[0]).'" data-cost="'.htmlspecialchars($mod[1]).'" data-cost-usd="'.htmlspecialchars($mod[2]).'">'.htmlspecialchars($mod[0]).' (+'.htmlspecialchars($mod[1]).')<i class="material-icons remove-modifier right">clear</i></div>';
					}
					$str .= '
					</div>
					<div class="divider col s12"></div>';
				}
				return $str;
			})()
			.'
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
			'.implode("", array_map(function($in) use ($type) {
				if (is_null($type)) {
					return '';
				}
				$attrs = $type->getAttrs();
				return '
				<p class="col s12 no-bottom-margin">'.$in["label"].' (<a href="#" class="attr-invert-btn">Invert</a>)</p>
				<div class="attr-container col s12">'.
				implode("",array_map(function($in) use ($attrs) {
					return '<div class="btn attr-button toggle-btn tooltipped '.(in_array($in[0], $attrs) ? 'on' : 'off').'" data-key="'.htmlspecialchars($in[0]).'" data-tooltip="'.htmlspecialchars($in[2]).'" data-position="bottom" data-delay="10">'.htmlspecialchars($in[1]).'</div>';
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
		<div id="payment-type-container" class="col s12">'.
		implode("",array_map(function($in) {
			return '<div class="col s12 payment-type" data-type="'.$in[0].'" data-addr="'.$in[1].'" data-instructions="'.$in[2].'"><div class="left"><p class="no-margin"><strong>'.$in[0].': </strong>'.$in[1].'</p><p class="no-top-margin">'.$in[2].'</p></div><i class="material-icons small right remove-payment-type">clear</i></div>';
		}, is_null($type) ? [] : $type->getPaymentOptions()))
		.'</div>
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
		'.implode("",array_map(function($in) {
			return '<div class="col s12 stage" data-stage="'.$in.'"><div class="left"><p class="no-margin">'.$in.'</p></div><i class="material-icons small right remove-stage">clear</i></div>';
		}, is_null($type) ? [] : $type->getStages())).'
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
				"default" => is_null($type) ? false : $type->isPhysicalAddrNeeded(),
				"error_text" => [self::PHRASES[self::PHYSICAL_ADDR_NEEDED_INVALID]],
				"error_code" => [self::PHYSICAL_ADDR_NEEDED_INVALID]
			],
			[
				"name" => "open",
				"wrapper_classes" => "col s12",
				"type" => "checkbox",
				"label" => "I am accepting commissions of this type",
				"required" => false,
				"default" => is_null($type) ? false : $type->isOpen(),
				"error_text" => [self::PHRASES[self::OPEN_INVALID]],
				"error_code" => [self::OPEN_INVALID]
			],
		];
	}

	public static function update(
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
		bool $physicalneeded,
		bool $open,
		\Catalyst\CommissionType\CommissionType $type
	) : int {
		$stmt = $GLOBALS["dbh"]->prepare("
			UPDATE
				".DB_TABLES["commission_types"]."
			SET
				`NAME` = :NAME,
				`BLURB` = :BLURB,
				`DESCRIPTION` = :DESCRIPTION,
				`BASE_COST` = :BASE_COST,
				`BASE_USDEQ` = :BASE_USDEQ,
				`ATTRS` = :ATTRS,
				`PHYSICAL_ADDR_NEEDED` = :PHYSICAL_ADDR_NEEDED,
				`OPEN` = :OPEN
			WHERE
				`ID` = :ID;
			");
		$attrs = json_encode($attrs);
		$ctid = $type->getId();
		$panint = $physicalneeded ? 1 : 0;
		$openint = $open ? 1 : 0;

		$stmt->bindParam(":NAME", $name);
		$stmt->bindParam(":BLURB", $blurb);
		$stmt->bindParam(":DESCRIPTION", $desc);
		$stmt->bindParam(":BASE_COST", $basecost);
		$stmt->bindParam(":BASE_USDEQ", $basecostusd);
		$stmt->bindParam(":ATTRS", $attrs);
		$stmt->bindParam(":PHYSICAL_ADDR_NEEDED", $panint);
		$stmt->bindParam(":OPEN", $openint);
		$stmt->bindParam(":ID", $ctid);

		if (!$stmt->execute()) {
			error_log(" Edit commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
			return self::ERROR_UNKNOWN;
		}

		$imgs = \Catalyst\Form\FileUpload::uploadImages($imgs, \Catalyst\Form\FileUpload::COMMISSION_TYPE_IMAGE, $type->getToken());

		if (!is_null($imgs)) {
			$stmt = 

			$i = 0;
			$stmt = $GLOBALS["dbh"]->prepare("
				SELECT
					`SORT`
				FROM
					`".DB_TABLES["commission_type_images"]."`
				WHERE
					`COMMISSION_TYPE_ID` = :COMMISSION_TYPE_ID
				;");
			$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
			$stmt->execute();

			$result = $stmt->fetchAll();

			if (count($result) == 0) {
				$i = 0;
			} else if (count($result) == 1) {
				$i = $result[0]["SORT"]+1;
			} else {
				$i = max(...array_column($result, "SORT"))+1;
			}

			$arr = [];
			foreach ($imgs as $img) {
				$arr[] = $ctid;
				$arr[] = $img;
				$arr[] = 0;
				$arr[] = $i++;
			}
			if ($type->getImages()[0][0] == "default.png") {
				$arr[2] = 1; // set primary as none exist
			}
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
			if (!$stmt->execute($arr)) {
				error_log(" Edit commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
				return self::ERROR_UNKNOWN;
			}
		}

		$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["commission_type_modifiers"]."` SET `DELETED` = 1 WHERE `COMMISSION_TYPE_ID` = :COMMISSION_TYPE_ID;");
		$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
		$stmt->execute();

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
				SELECT
					`ID`, `NAME`, `PRICE`, `USDEQ`
				FROM 
					`".DB_TABLES["commission_type_modifiers"]."`
				WHERE 
					`COMMISSION_TYPE_ID` = :COMMISSION_TYPE_ID;");
			$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
			if (!$stmt->execute()) {
				error_log(" Edit commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
				return self::ERROR_UNKNOWN;
			}

			$existing = $stmt->fetchAll();

			foreach ($flattenedMods as $mod) {
				$filtered = array_values(array_filter($existing, function($in) use ($mod) {
					return $mod[1] == $in["NAME"] && $mod[2] == $in["PRICE"] && $mod[3] == $in["USDEQ"];
				}));
				if (empty($filtered)) {
					$stmt = $GLOBALS["dbh"]->prepare("
						INSERT INTO
							`".DB_TABLES["commission_type_modifiers"]."`
							(
								`COMMISSION_TYPE_ID`,
								`NAME`,
								`PRICE`,
								`USDEQ`,
								`GROUP`,
								`MULTIPLE`
							)
						VALUES
							(
								:COMMISSION_TYPE_ID,
								:NAME,
								:PRICE,
								:USDEQ,
								:GROUP,
								:MULTIPLE
							);");
					$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
					$stmt->bindParam(":NAME", $mod[1]);
					$stmt->bindParam(":PRICE", $mod[2]);
					$stmt->bindParam(":USDEQ", $mod[3]);
					$stmt->bindParam(":GROUP", $mod[4]);
					$stmt->bindParam(":MULTIPLE", $mod[5]);
					if (!$stmt->execute()) {
						error_log(" Edit commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
						return self::ERROR_UNKNOWN;
					}
				} else {
					$stmt = $GLOBALS["dbh"]->prepare("
						UPDATE
							`".DB_TABLES["commission_type_modifiers"]."`
						SET
							`GROUP` = :GROUP,
							`MULTIPLE` = :MULTIPLE,
							`DELETED` = 0
						WHERE
							`ID` = :ID;");
					$stmt->bindParam(":GROUP", $mod[4]);
					$stmt->bindParam(":MULTIPLE", $mod[5]);
					$stmt->bindParam(":ID", $filtered[0]["ID"]);
					if (!$stmt->execute()) {
						error_log(" Edit commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
						return self::ERROR_UNKNOWN;
					}
				}
			}
		}

		$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["commission_type_payment_options"]."` SET `DELETED` = 1 WHERE `COMMISSION_TYPE_ID` = :COMMISSION_TYPE_ID;");
		$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
		$stmt->execute();

		if (!empty($payments)) {
			$arr = [];

			foreach ($payments as $payment) {
				$arr[] = [
					$ctid,
					$payment["type"],
					$payment["addr"],
					$payment["instructions"]
				];
			}

			$stmt = $GLOBALS["dbh"]->prepare("
				SELECT
					`ID`, `TYPE`, `ADDRESS`, `INSTRUCTIONS`
				FROM 
					`".DB_TABLES["commission_type_payment_options"]."`
				WHERE 
					`COMMISSION_TYPE_ID` = :COMMISSION_TYPE_ID;");
			$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
			if (!$stmt->execute()) {
				error_log(" Edit commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
				return self::ERROR_UNKNOWN;
			}

			$existing = $stmt->fetchAll();

			foreach ($arr as $payment) {
				$filtered = array_values(array_filter($existing, function($in) use ($payment) {
					return $payment[1] == $in["TYPE"] && $payment[2] == $in["ADDRESS"] && $payment[3] == $in["INSTRUCTIONS"];
				}));
				if (empty($filtered)) {
					$stmt = $GLOBALS["dbh"]->prepare("
						INSERT INTO
							`".DB_TABLES["commission_type_payment_options"]."`
							(
								`COMMISSION_TYPE_ID`,
								`TYPE`,
								`ADDRESS`,
								`INSTRUCTIONS`
							)
						VALUES
							(
								:COMMISSION_TYPE_ID,
								:TYPE,
								:ADDRESS,
								:INSTRUCTIONS
							);");
					$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
					$stmt->bindParam(":TYPE", $payment[1]);
					$stmt->bindParam(":ADDRESS", $payment[2]);
					$stmt->bindParam(":INSTRUCTIONS", $payment[3]);
					if (!$stmt->execute()) {
						error_log(" Edit commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
						return self::ERROR_UNKNOWN;
					}
				} else {
					$stmt = $GLOBALS["dbh"]->prepare("
						UPDATE
							`".DB_TABLES["commission_type_payment_options"]."`
						SET
							`DELETED` = 0
						WHERE
							`ID` = :ID;");
					$stmt->bindParam(":ID", $filtered[0]["ID"]);
					if (!$stmt->execute()) {
						error_log(" Edit commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
						return self::ERROR_UNKNOWN;
					}
				}
			}
		}

		$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["commission_type_stages"]."` SET `DELETED` = 1 WHERE `COMMISSION_TYPE_ID` = :COMMISSION_TYPE_ID;");
		$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
		$stmt->execute();

		if (!empty($stages)) {
			$stmt = $GLOBALS["dbh"]->prepare("
				SELECT
					`ID`, `STAGE`
				FROM 
					`".DB_TABLES["commission_type_stages"]."`
				WHERE 
					`COMMISSION_TYPE_ID` = :COMMISSION_TYPE_ID;");
			$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
			if (!$stmt->execute()) {
				error_log(" Edit commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
				return self::ERROR_UNKNOWN;
			}

			$existing = $stmt->fetchAll();

			foreach ($stages as $stage) {
				$filtered = array_values(array_filter($existing, function($in) use ($stage) {
					return $stage == $in["STAGE"];
				}));
				if (empty($filtered)) {
					$stmt = $GLOBALS["dbh"]->prepare("
						INSERT INTO
							`".DB_TABLES["commission_type_stages"]."`
							(
								`COMMISSION_TYPE_ID`,
								`STAGE`
							)
						VALUES
							(
								:COMMISSION_TYPE_ID,
								:STAGE
							);");
					$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
					$stmt->bindParam(":STAGE", $stage);
					if (!$stmt->execute()) {
						error_log(" Edit commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
						return self::ERROR_UNKNOWN;
					}
				} else {
					$stmt = $GLOBALS["dbh"]->prepare("
						UPDATE
							`".DB_TABLES["commission_type_stages"]."`
						SET
							`DELETED` = 0
						WHERE
							`ID` = :ID;");
					$stmt->bindParam(":ID", $filtered[0]["ID"]);
					if (!$stmt->execute()) {
						error_log(" Edit commission type error: **".(self::$lastErrId = microtime(true))."**, ".serialize($stmt->errorInfo()));
						return self::ERROR_UNKNOWN;
					}
				}
			}
		}

		return self::SUCCESS;
	}

	public static function updateImages(\Catalyst\CommissionType\CommissionType $type, array $images) : int {
		$current = $type->getImages();
		$currentPaths = array_column($current, 0);

		$newPaths = array_map(function($in) use ($type) { return $type->getToken().$in; }, array_column($images, 0));

		$toRemove = array_diff($currentPaths, $newPaths);

		foreach ($toRemove as $path) {
			\Catalyst\Form\FileUpload::delete($path, \Catalyst\Form\FileUpload::COMMISSION_TYPE_IMAGE);
		}

		if (count($toRemove) != 0) {
			$stmt = $GLOBALS["dbh"]->prepare("DELETE FROM `".DB_TABLES["commission_type_images"]."` WHERE `COMMISSION_TYPE_ID` = ? AND `PATH` IN (".implode(",", array_fill(0, count($toRemove), "?")).");");
			$ctid = $type->getId();
			$stmt->execute(array_merge([$ctid], array_map(function($in) use ($type) { return str_replace($type->getToken(), "", $in); }, $toRemove)));
		}

		if (count($images) != 0) {
			$GLOBALS["dbh"]->beginTransaction();
			$i=0;
			foreach ($images as $image) {
				$stmt = $GLOBALS["dbh"]->prepare("UPDATE `".DB_TABLES["commission_type_images"]."` SET `CAPTION` = :CAPTION, `NSFW` = :NSFW, `PRIMARY` = :PRIMARY, `SORT` = :SORT WHERE `COMMISSION_TYPE_ID` = :COMMISSION_TYPE_ID AND `PATH` = :PATH;");
				$nsfw = ($image[2] ? 1 : 0);
				$primary = ($image[3] ? 1 : 0);

				$stmt->bindParam(":CAPTION", $image[1]);
				$stmt->bindParam(":NSFW", $nsfw);
				$stmt->bindParam(":PRIMARY", $primary);
				$stmt->bindParam(":SORT", $i);

				$ctid = $type->getId();
				$stmt->bindParam(":COMMISSION_TYPE_ID", $ctid);
				$stmt->bindParam(":PATH", $image[0]);

				$stmt->execute();

				$i++;
			}
			$GLOBALS["dbh"]->commit();
		}

		return self::SUCCESS;
	}
}
