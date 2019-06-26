<?php
header("Content-Type: application/javascript; charset=UTF-8", true);

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

define("NO_SESSION", true);

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\Form\Field\ConfirmField;
?>
{
	<?php require_once REAL_ROOTDIR."js/modules/form_input_header.js"; ?>
	
	const className = <?= json_encode(ConfirmField::class) ?>;

	window.log("Form input handlers", "Registering "+className);

	window.formInputHandlers[className] = class {
		constructor(id, prompt) {
			this.fieldType = className;

			this.id = id;
			this.prompt = prompt;

			window.log(className, "Constructing an object to represent ID "+id+" with prompt \""+prompt+"\"");
		}

		verify() {
			window.log(this.properties.distinguisher, "Showing dialog");

			var result = confirm(this.prompt);

			window.log(this.properties.distinguisher, "Confirmation dialogue gave result "+result);

			return result;
		}
	}
};
