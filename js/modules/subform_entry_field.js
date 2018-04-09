<?php
use \Catalyst\Form\Field\{SubformMultipleEntryField, SubformMultipleEntryFieldWithRows};
?>
$(document).on("click", <?= json_encode('.'.SubformMultipleEntryField::REMOVE_BUTTON_CLASS) ?>, function(e) {
	$(this).closest(<?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>).remove();
});
$(document).on("click", <?= json_encode('.'.SubformMultipleEntryFieldWithRows::REMOVE_CONTAINER_BUTTON_CLASS) ?>, function(e) {
	if ($(this).closest(".subform-entry-container").find(".subform-entry-sub-container").length == 1) {
		$(this).closest(".subform-entry-container").next().click();
	}
	$(this).closest(".subform-entry-sub-container").remove();
});
$(document).on("click", <?= json_encode('.'.SubformMultipleEntryFieldWithRows::ADD_CONTAINER_BUTTON_CLASS) ?>, function(e) {
	var newContainer = $("<div></div>").addClass("subform-entry-sub-container col s12");

	var rightBar = $("<div></div>").addClass(<?= json_encode(SubformMultipleEntryFieldWithRows::PROTECTED_RIGHT_CONTAINER_CLASS) ?>);

	rightBar.html($(this).prev().attr("data-right-bar").replace("{uniq}", Date.now()));

	newContainer.append(rightBar);

	$(this).prev().append(newContainer);
	
	$(this).prev().data("sortable").addContainer($(this).prev().find(".subform-entry-sub-container:last").get(0));
});
for (var i = $(".subform-entry-container").length - 1; i >= 0; i--) {
	if ($($(".subform-entry-container")[i]).find(".subform-entry-sub-container").length > 0) {
		var container = $(".subform-entry-container")[i];
		var sortable = window.sortablee = new Draggable.Sortable($(container).find(".subform-entry-sub-container").get(), {
			draggable: <?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>,
		});
		
		$($(".subform-entry-container")[i]).data("sortable", sortable);
		
		var lastOverContainer;

		sortable.on("drag:start", function(e) {
			lastOverContainer = e.sourceContainer;
		}).on("sortable:sorted", function(e) {
			if ($(e.newContainer).find(":not(.draggable-mirror)").last().hasClass(<?= json_encode(SubformMultipleEntryField::ENTRY_ITEM) ?>)) {
				$(e.newContainer).find(<?= json_encode(SubformMultipleEntryFieldWithRows::PROTECTED_RIGHT_CONTAINER_CLASS) ?>).remove().insertAfter($(e.newContainer).find(<?= json_encode(".".SubformMultipleEntryField::ENTRY_ITEM.":last") ?>));
			}

			if (lastOverContainer === e.dragEvent.overContainer) {
				return;
			}

			lastOverContainer = e.dragEvent.overContainer;
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
