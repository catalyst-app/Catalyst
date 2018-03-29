<?php
use \Catalyst\Form\Field\SubformMultipleEntryField;
?>
$(document).on("click", ".<?= SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?>", function(e) {
	$(this).closest(<?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>).remove();
});
// $(document).on("click", ".add-sub-container-field-btn", function(e) {
// 	$(this).prev().append($("<div></div>").addClass("subform-entry-sub-container col s12"));
// 	$(this).prev().data("sortable").destroy();

// 	var container = $(this).prev();
// 	var sortable = window.sortablee = new Draggable.Sortable($(container).find(".subform-entry-sub-container").get(), {
// 		draggable: <?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>,
// 	});
	
// 	$($(".subform-entry-container")[i]).data("sortable", sortable);
	
// 	var lastOverContainer;

// 	sortable.on("drag:start", function(e) {
// 		lastOverContainer = e.sourceContainer;
// 	}).on("sortable:sorted", function(e) {
// 		if (lastOverContainer === e.dragEvent.overContainer) {
// 			return;
// 		}

// 		lastOverContainer = e.dragEvent.overContainer;
// 	}).on("mirror:created", function(e) {
// 		$(e.mirror).css("max-width", $(e.originalSource).width()).find(".<?= SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?>").remove();
// 	});
// });
for (var i = $(".subform-entry-container").length - 1; i >= 0; i--) {
	// if ($($(".subform-entry-container")[i]).find(".subform-entry-sub-container").length > 0) {
		// var container = $(".subform-entry-container")[i];
		// var sortable = window.sortablee = new Draggable.Sortable($(container).find(".subform-entry-sub-container").get(), {
		// 	draggable: <?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>,
		// });
		
		// $($(".subform-entry-container")[i]).data("sortable", sortable);
		
		// var lastOverContainer;

		// sortable.on("drag:start", function(e) {
		// 	lastOverContainer = e.sourceContainer;
		// }).on("sortable:sorted", function(e) {
		// 	if (lastOverContainer === e.dragEvent.overContainer) {
		// 		return;
		// 	}

		// 	lastOverContainer = e.dragEvent.overContainer;
		// }).on("mirror:created", function(e) {
		// 	$(e.mirror).css("max-width", $(e.originalSource).width()).find(".<?= SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?>").remove();
		// });
	// } else {
		new Draggable.Sortable($(".subform-entry-container")[i], {
			draggable: <?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>,
			appendTo: '.subform-entry-container'
		}).on("mirror:created", function(e) {
			$(e.mirror).css("max-width", $(e.originalSource).width()).find(".<?= SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?>").remove();
		});
	// }
}
