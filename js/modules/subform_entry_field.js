<?php
use \Catalyst\Form\Field\SubformMultipleEntryField;
?>
$(document).on("click", ".<?= SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?>", function(e) {
	$(this).closest(<?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>).remove();
});
try {
	new Draggable.Sortable(document.querySelectorAll(".subform-entry-container"), {
		draggable: <?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>,
		appendTo: 'body'
	}).on("mirror:created", function(e) {
		$(e.mirror).width($(e.originalSource).width()).find(".<?= SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?>").remove();
	});
} catch(e) {}
