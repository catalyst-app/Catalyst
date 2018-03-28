<?php
use \Catalyst\Form\Field\SubformMultipleEntryField;
?>
$(document).on("click", ".<?= SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?>", function(e) {
	$(this).closest(<?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>).remove();
});
$(document).on("click", ".add-sub-container-field-btn", function(e) {
	$(this).prev().append($("<div></div>").addClass("subform-entry-sub-container col s12"));
});
try {
	for (var i = $(".subform-entry-container").length - 1; i >= 0; i--) {
		if ($($(".subform-entry-container")[i]).find(".subform-entry-sub-container").length > 0) {
			new Draggable.Sortable($($(".subform-entry-container")[i]).find(".subform-entry-sub-container").get(), {
				draggable: <?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>,
				appendTo: '.subform-entry-container'
			}).on("mirror:created", function(e) {
				$(e.mirror).css("max-width", $(e.originalSource).width()).find(".<?= SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?>").remove();
			});
		} else {
			new Draggable.Sortable($(".subform-entry-container")[i], {
				draggable: <?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>,
				appendTo: '.subform-entry-container'
			}).on("mirror:created", function(e) {
				$(e.mirror).css("max-width", $(e.originalSource).width()).find(".<?= SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?>").remove();
			});
		}
	}
} catch(e) {}
