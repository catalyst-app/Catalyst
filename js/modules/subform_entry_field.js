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
	new Draggable.Sortable(document.querySelectorAll(".subform-entry-container"), {
		draggable: <?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>,
		appendTo: 'body'
	}).on("mirror:created", function(e) {
		$(e.mirror).width($(e.originalSource).width()).find(".<?= SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?>").remove();
	});
} catch(e) {}
