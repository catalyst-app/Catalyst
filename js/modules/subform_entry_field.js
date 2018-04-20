<?php
use \Catalyst\Form\Field\{SubformMultipleEntryField, SubformMultipleEntryFieldWithRows};
?>
$(document).on("click", <?= json_encode('.'.SubformMultipleEntryField::REMOVE_BUTTON_CLASS) ?>, function(e) {
	$(this).closest(<?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>).remove();
	window.log(<?= json_encode(basename(__FILE__)) ?>, ".on click <?= '.'.SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?> - removing entry item");
});
$(document).on("click", <?= json_encode('.'.SubformMultipleEntryFieldWithRows::REMOVE_CONTAINER_BUTTON_CLASS) ?>, function(e) {
	window.log(<?= json_encode(basename(__FILE__)) ?>, ".on click <?= '.'.SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?> - removing container");
	if ($(this).closest(".subform-entry-container").find(".subform-entry-sub-container").length == 1) {
		window.log(<?= json_encode(basename(__FILE__)) ?>, ".on click <?= '.'.SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?> - this was the last container.  Simulating click of addition button");
		$(this).closest(".subform-entry-container").next().click();
	}
	$(this).closest(".subform-entry-sub-container").remove();
});
$(document).on("click", <?= json_encode('.'.SubformMultipleEntryFieldWithRows::ADD_CONTAINER_BUTTON_CLASS) ?>, function(e) {
	window.log(<?= json_encode(basename(__FILE__)) ?>, ".on click <?= '.'.SubformMultipleEntryFieldWithRows::ADD_CONTAINER_BUTTON_CLASS ?> - adding container");

	var newContainer = $("<div></div>").addClass("subform-entry-sub-container col s12");

	var itemContainer = $("<div></div>").addClass("subform-entry-sub-container-items");

	newContainer.append(itemContainer);

	var rightBar = $("<div></div>").addClass(<?= json_encode(SubformMultipleEntryFieldWithRows::PROTECTED_RIGHT_CONTAINER_CLASS) ?>).addClass("right-align");

	rightBar.html($(this).prev().attr("data-right-bar").replace(/{uniq}/g, Date.now()));

	newContainer.append(rightBar);

	$(this).prev().append(newContainer);
	
	window.log(<?= json_encode(basename(__FILE__)) ?>, ".on click <?= '.'.SubformMultipleEntryFieldWithRows::ADD_CONTAINER_BUTTON_CLASS ?> - registering new container with sortable");

	$(this).prev().data("sortable").addContainer($(this).prev().find(".subform-entry-sub-container-items:last").get(0));
});

window.log(<?= json_encode(basename(__FILE__)) ?>, "main - registering "+$(".subform-entry-container").length+" subforms with sortable");

function registerSubformContainer(container) {
	if ($(container).find(".subform-entry-sub-container").length > 0) {
		window.log(<?= json_encode(basename(__FILE__)) ?>, "registerSubformContainer - "+$(container).attr("id")+" contains sub-containers.  Treating as such");

		var sortable = new Draggable.Sortable($(container).find(".subform-entry-sub-container-items").get(), {
			draggable: <?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>,
		});
		
		$(container).data("sortable", sortable);
		
		var lastOverContainer;

		sortable.on("drag:start", function(e) {
			lastOverContainer = e.sourceContainer;
		}).on("sortable:sorted", function(e) {			
			if ($(e.newContainer).find(":not(.draggable-mirror)").last().hasClass(<?= json_encode(SubformMultipleEntryField::ENTRY_ITEM) ?>)) {
				$(e.newContainer).find(<?= json_encode(SubformMultipleEntryFieldWithRows::PROTECTED_RIGHT_CONTAINER_CLASS) ?>).remove().insertAfter($(e.newContainer).find(<?= json_encode(".".SubformMultipleEntryField::ENTRY_ITEM.":last") ?>));
				window.log(<?= json_encode(basename(__FILE__)) ?>, "sortable:sorted - dragged object went on the wrong side of the <?= SubformMultipleEntryFieldWithRows::PROTECTED_RIGHT_CONTAINER_CLASS ?>, fixing");
			}

			if (lastOverContainer === e.dragEvent.overContainer) {
				return;
			}

			lastOverContainer = e.dragEvent.overContainer;
		}).on("mirror:created", function(e) {
			window.log(<?= json_encode(basename(__FILE__)) ?>, "mirror:created - setting mirror max-width to width of original, removing remove button");
			$(e.mirror).css("max-width", $(e.originalSource).width()).find(".<?= SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?>").remove();
		});
	} else {
		window.log(<?= json_encode(basename(__FILE__)) ?>, "registerSubformContainer - "+$(container).attr("id")+" does NOT contain sub-containers.  Treating as such");
		new Draggable.Sortable(container, {
			draggable: <?= json_encode('.'.SubformMultipleEntryField::ENTRY_ITEM) ?>,
			appendTo: '.subform-entry-container'
		}).on("mirror:created", function(e) {
			window.log(<?= json_encode(basename(__FILE__)) ?>, "mirror:created - setting mirror max-width to width of original, removing remove button");
			$(e.mirror).css("max-width", $(e.originalSource).width()).find(".<?= SubformMultipleEntryField::REMOVE_BUTTON_CLASS ?>").remove();
		});
	}
}

for (var i = $(".subform-entry-container").length - 1; i >= 0; i--) {
	registerSubformContainer($(".subform-entry-container")[i]);
}
